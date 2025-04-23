<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Events\BookingStatusUpdated;
use App\Events\NewBooking;
use App\Events\PaymentReceived;

class BookingController extends Controller
{
    // Display a list of bookings
    public function index()
    {
        $bookings = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services', 'payment'])->get();
        return view('bookings.index', compact('bookings'));
    }

    // Create a new booking with real-time notifications and payment handling
    public function store(Request $request)
    {
        // Define base validation rules
        $rules = [
            'type' => 'required|in:Gardening,Landscaping',
            'homeowner_id' => 'required|exists:users,id',
            'service_ids' => 'required|array',
            'service_ids.*' => 'exists:services,id',
            'address' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'total_price' => 'required|numeric|min:0',
            'special_instructions' => 'nullable|string|max:500',
            // New payment-related validation rules
            'payment_type' => 'required|in:Full Payment,Down Payment',
            'payment_method' => 'required|string|in:Credit Card,Debit Card,GCash,PayMaya,Bank Transfer',
            'amount_paid' => 'required|numeric|min:0',
            'remaining_balance' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Paid,Partially Paid,Unpaid',
        ];

        // Add conditional validation rules
        if ($request->get('type') === 'Gardening') {
            $rules['gardener_id'] = 'required|exists:users,id';
        } elseif ($request->get('type') === 'Landscaping') {
            $rules['serviceprovider_id'] = 'required|exists:users,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error', 
                'messages' => $validator->errors()
            ], 422);
        }

        // Create the booking
        $booking = Booking::create([
            'type' => $request->type,
            'homeowner_id' => $request->homeowner_id,
            'gardener_id' => $request->gardener_id ?? null,
            'serviceprovider_id' => $request->serviceprovider_id ?? null,
            'address' => $request->address,
            'date' => $request->date,
            'time' => $request->time,
            'total_price' => $request->total_price,
            'special_instructions' => $request->special_instructions,
            'payment_status' => $request->payment_status,
        ]);

        // Attach services to the booking
        foreach ($request->service_ids as $service_id) {
            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service_id,
            ]);
        }

        // Create payment record
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_type' => $request->payment_type,
            'payment_method' => $request->payment_method,
            'amount_paid' => $request->amount_paid,
            'remaining_balance' => $request->remaining_balance,
            'payment_date' => now(),
            'payment_status' => $request->payment_status,
            'transaction_id' => 'TXN' . time() . rand(1000, 9999), // Generate a simple transaction ID
        ]);

        // Determine which provider to notify
        $providerId = null;
        if ($request->type === 'Gardening') {
            $providerId = $request->gardener_id;
        } else if ($request->type === 'Landscaping') {
            $providerId = $request->serviceprovider_id;
        }

        // Broadcast the event to both homeowner and service provider
        event(new NewBooking($booking));
        
        // Broadcast payment received event
        event(new PaymentReceived($payment, $booking));

        return response()->json([
            'message' => 'Booking created successfully with Booking Number: ' . $booking->id,
            'type' => 'success',
            'booking' => $booking->load(['homeowner', 'services', 'payment']),
        ], 201);
    }

    public function get_pending_bookings($userId)
    {
        $user_type = auth()->user()->user_type;

        $bookings = Booking::where('status', 'Pending')
            ->with(['homeowner', 'gardener', 'serviceProvider', 'services', 'payment'])
            ->when($user_type === 'gardener', fn($q) => $q->where('gardener_id', $userId))
            ->when($user_type === 'homeowner', fn($q) => $q->where('homeowner_id', $userId))
            ->when($user_type === 'service_provider', fn($q) => $q->where('serviceprovider_id', $userId))
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'message' => 'Bookings retrieved successfully.',
            'bookings' => $bookings,
        ], 200);
    }
    
    public function updateStatus(Request $request, $id)
    {
        \Log::info('Update status request received', ['id' => $id, 'request' => $request->all()]);
        
        try {
            $request->validate([
                'status' => 'required|string|in:pending,accepted,declined,completed',
            ]);

            $booking = Booking::findOrFail($id);
            $oldStatus = $booking->status;
            
            \Log::info('Updating booking status', [
                'booking_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $request->status
            ]);
            
            $booking->status = $request->status;
            $booking->save();

            // Broadcast the status update
            event(new BookingStatusUpdated($booking, $oldStatus));

            return response()->json($booking);
            
        } catch (\Exception $e) {
            \Log::error('Booking status update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // Process additional payment (e.g., remaining balance)
    public function processPayment(Request $request, $bookingId)
    {
        try {
            $request->validate([
                'payment_method' => 'required|string|in:Credit Card,Debit Card,GCash,PayMaya,Bank Transfer',
                'amount' => 'required|numeric|min:0',
            ]);

            $booking = Booking::with('payment')->findOrFail($bookingId);
            $currentPayment = $booking->payment;

            if (!$currentPayment) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'No payment record found for this booking'
                ], 404);
            }

            // Validate payment amount
            if ($request->amount > $currentPayment->remaining_balance) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Payment amount exceeds remaining balance'
                ], 422);
            }

            // Update payment record
            $currentPayment->amount_paid += $request->amount;
            $currentPayment->remaining_balance -= $request->amount;
            
            // Update payment status if fully paid
            if ($currentPayment->remaining_balance <= 0) {
                $currentPayment->payment_status = 'Paid';
                $booking->payment_status = 'Paid';
                $booking->save();
            }
            
            $currentPayment->save();

            // Create payment transaction record
            $transaction = PaymentTransaction::create([
                'payment_id' => $currentPayment->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'transaction_date' => now(),
                'transaction_id' => 'TXN' . time() . rand(1000, 9999),
            ]);

            return response()->json([
                'type' => 'success',
                'message' => 'Payment processed successfully',
                'payment' => $currentPayment,
                'transaction' => $transaction
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    // Get payment details for a booking
    public function getPaymentDetails($bookingId)
    {
        try {
            $booking = Booking::with(['payment', 'payment.transactions'])->findOrFail($bookingId);
            
            return response()->json([
                'type' => 'success',
                'payment' => $booking->payment,
                'booking' => $booking
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // Get gardener's bookings
    public function getGardenerBookings($gardenerId)
    {
        $bookings = Booking::where('gardener_id', $gardenerId)
            ->with(['homeowner', 'services', 'payment'])
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'message' => 'Bookings retrieved successfully.',
            'bookings' => $bookings,
        ], 200);
    }

    // Get service provider's bookings
    public function getServiceProviderBookings($serviceProviderId)
    {
        $bookings = Booking::where('serviceprovider_id', $serviceProviderId)
            ->with(['homeowner', 'services', 'payment'])
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'message' => 'Bookings retrieved successfully.',
            'bookings' => $bookings,
        ], 200);
    }

    public function getHomeownerBookings($homeownerId)
    {
        $bookings = Booking::where('homeowner_id', $homeownerId)
            ->with([
                'gardener', 
                'serviceProvider', 
                'services',
                'homeowner', // In case you need homeowner details
                'payment'    // Include payment details
            ])
            ->orderBy('date', 'desc')
            ->get();
    
        return response()->json([
            'message' => 'Homeowner bookings retrieved successfully.',
            'bookings' => $bookings,
        ], 200);
    }

    public function countBookings($userId)
    {
        $user = User::findOrFail($userId);
        
        $count = Booking::when($user->user_type === 'gardener', function($query) use ($userId) {
                    return $query->where('gardener_id', $userId);
                })
                ->when($user->user_type === 'service_provider', function($query) use ($userId) {
                    return $query->where('serviceprovider_id', $userId);
                })
                ->when($user->user_type === 'homeowner', function($query) use ($userId) {
                    return $query->where('homeowner_id', $userId);
                })
                ->count();

        return response()->json([
            'count' => $count,
            'message' => 'Booking count retrieved successfully'
        ], 200);
    }
    
    // Get booking details
    public function show($bookingId)
    {
        $booking = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services', 'payment'])
            ->findOrFail($bookingId);

        return response()->json([
            'message' => 'Booking retrieved successfully',
            'booking' => $booking,
        ], 200);
    }
}