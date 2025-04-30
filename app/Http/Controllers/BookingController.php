<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Events\BookingStatusUpdated;
use App\Events\NewBooking;
use  App\Models\Payment;
use  App\Models\Setting;
use App\Models\WalletTransaction;   
use Illuminate\Support\Facades\DB;
class BookingController extends Controller
{
    // Display a list of bookings
    public function index()
    {
        $bookings = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services'])->get();
        return view('bookings.index', compact('bookings'));
    }

    // Create a new booking with real-time notifications
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
            'payment_status' => 'required|in:pending,paid,partially_paid',
            'payment.amount_paid' => 'required|numeric|min:0',
            'payment.sender_no' => 'required|string|max:20',
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
           
        ]);

        // Attach services to the booking
        foreach ($request->service_ids as $service_id) {
            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service_id,
            ]);
        }

       $payment =  Payment::create([
            'booking_id' => $booking->id,
            'amount_paid' => $request->input('payment.amount_paid'),
            'payment_date' => now(),
            'sender_no' => $request->input('payment.sender_no'),
        ]);

        // Update the homeowner's balance
        auth()->user()->decrement('balance', $payment->amount_paid);

        WalletTransaction::create([
            'user_id' => auth()->user()->id,
            'amount' => $payment->amount_paid,
            'transaction_type' => 'debit',
            'description' => 'Booking payment for booking ID: ' . $booking->id,
        ]);

        // Broadcast the event to both homeowner and service provider
          event(new NewBooking($booking));

        return response()->json([
            'message' => 'Booking created successfully with Booking Number: ' . $booking->id,
            'type' => 'success',
            'booking' => $booking->load(['homeowner', 'services']),
        ], 201);
    }


    public function getTotalEarnings()
{
    $user = auth()->user();
    
    $totalEarnings = Payment::whereHas('booking', function($query) use ($user) {
        $query->whereAny( ['gardener_id','serviceprovider_id'], $user->id)
              ->where('status', 'completed');
    })
    ->where('payment_status', 'Received')
    ->sum( DB::raw('amount_paid-admin_fee') );

    return response()->json([
        'total_earnings' => (float)$totalEarnings
    ]);
}

    Public function get_pending_bookings($userId)
    {
        $user_type = auth()->user()->user_type;

        $bookings = Booking::where('status', 'Pending')
            ->with(['homeowner', 'gardener', 'serviceProvider', 'services'])
            ->when($user_type === 'gardener',fn($q)=>$q->where('gardener_id', $userId))
            ->when($user_type === 'homeowner',fn($q)=>$q->where('homeowner_id', $userId))
            ->when($user_type === 'service_provider',fn($q)=>$q->where('serviceprovider_id', $userId))
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
                DB::beginTransaction();
                
                $request->validate([
                    'status' => 'required|string|in:pending,accepted,declined,completed',
                ]);
        
                $booking = Booking::with(['payments'=>fn($q)=>$q->where('payment_status','Pending'),'homeowner','serviceProvider','gardener'])
                                ->findOrFail($id);
                $oldStatus = $booking->status;
                
                $setting = Setting::first();
                $admin_fee_percent = $setting?->admin_admin_fee_percentage ?? 3;
                $admin_wallet = User::find($setting?->admin_user_wallet);
        
                if($request->status == 'accepted') {
                    $this->handleAcceptedStatus($booking, $admin_fee_percent, $admin_wallet);
                } 
                elseif($request->status == 'completed') {
                    $this->handleCompletedStatus($booking, $admin_fee_percent, $admin_wallet);
                }
                
                $booking->status = $request->status;
                $booking->save();
        
                event(new BookingStatusUpdated($booking, $oldStatus));
                
                DB::commit();
        
                return response()->json($booking);
                
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Booking status update failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['message' => $e->getMessage()], 422);
            }
        }

    // New helper methods
protected function handleAcceptedStatus($booking, $admin_fee_percent, $admin_wallet)
{
    $booking->payments->each(function($payment) use ($admin_fee_percent, $admin_wallet, $booking) {
        $amount_paid = $payment->amount_paid;
        $admin_fee = $amount_paid * ($admin_fee_percent / 100);
        $provider_amount = $amount_paid - $admin_fee;

        $payment->update([
            'payment_status' => 'Received',
            'receiver_no' => auth()->user()->account ?? '09xxxxxxxx',
            'amount_paid' => $amount_paid,
            'admin_fee' => $admin_fee
        ]);

        // Credit to service provider or gardener
        $recipient = $booking->serviceProvider ?? $booking->gardener;
        if ($recipient) {
            $recipient->increment('balance', $provider_amount);
            WalletTransaction::create([
                'user_id' => $recipient->id,
                'amount' => $provider_amount,
                'transaction_type' => 'credit',
                'description' => ($booking->serviceProvider ? 'Service Provider' : 'Gardener') . 
                                ' fee for booking ID: ' . $booking->id,
            ]);
        }

        // Credit admin fee
        if ($admin_wallet) {
            $admin_wallet->increment('balance', $admin_fee);
            WalletTransaction::create([
                'user_id' => $admin_wallet->id,
                'amount' => $admin_fee,
                'transaction_type' => 'credit',
                'description' => 'Admin fee for booking ID: ' . $booking->id,
            ]);
        }
    });
}

protected function handleCompletedStatus($booking, $admin_fee_percent, $admin_wallet)
{
    $total_price = $booking->total_price;
    $total_paid = Payment::where('booking_id', $booking->id)
    ->where('payment_status', 'Received')
    ->sum('amount_paid');
    $total_balance = $total_price - $total_paid; 
    
    if($total_balance > 0) {
        $admin_fee = $total_balance * ($admin_fee_percent / 100);
        $provider_amount = $total_balance - $admin_fee;

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount_paid' => $total_balance,
            'payment_date' => now(),
            'admin_fee' => $admin_fee,
            'payment_status' => 'Received',
            'sender_no' => User::find($booking->homeowner_id)->account,
            'receiver_no' => auth()->user()->account ?? '09xxxxxxxx',
        ]);

        // Debit from homeowner
        $booking->homeowner->decrement('balance', $total_balance);
        WalletTransaction::create([
            'user_id' => $booking->homeowner->id,
            'amount' => $total_balance,
            'transaction_type' => 'debit',
            'description' => 'Final payment for booking ID: ' . $booking->id,
        ]);

        // Credit to service provider or gardener
        $recipient = $booking->serviceProvider ?? $booking->gardener;
        if ($recipient) {
            $recipient->increment('balance', $provider_amount);
            WalletTransaction::create([
                'user_id' => $recipient->id,
                'amount' => $provider_amount,
                'transaction_type' => 'credit',
                'description' => ($booking->serviceProvider ? 'Service Provider' : 'Gardener') . 
                                ' fee for booking ID: ' . $booking->id,
            ]);
        }

        // Credit admin fee
        if ($admin_wallet) {
            $admin_wallet->increment('balance', $admin_fee);
            WalletTransaction::create([
                'user_id' => $admin_wallet->id,
                'amount' => $admin_fee,
                'transaction_type' => 'credit',
                'description' => 'Admin fee for booking ID: ' . $booking->id,
            ]);
        }
    }
}
    
public function getAllBookings($userId)
{
    $user = User::findOrFail($userId);
    
    // Print the user data to debug the issue
    \Log::info("User data for ID {$userId}:", [
        'user_type' => $user->user_type,
        'token' => auth()->user()->id == $userId ? 'Valid token' : 'Invalid token match'
    ]);
    
    // Make sure the authenticated user can only access their own bookings
    if (auth()->user()->id != $userId) {
        return response()->json(['message' => 'Unauthorized access.'], 403);
    }
    
    $query = function($userId, $userType) {
        // Debug the query parameters
        \Log::info("Building query with:", ['userId' => $userId, 'userType' => $userType]);
        
        return Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services'])
            ->when($userType === 'gardener', function($q) use ($userId) {
                return $q->where('gardener_id', $userId);
            })
            ->when($userType === 'serviceprovider_id', function($q) use ($userId) {
                // Make sure the column name matches what's in your database
                return $q->where('serviceprovider_id', $userId); // Changed from serviceprovider_id
            })
            ->when($userType === 'homeowner', function($q) use ($userId) {
                return $q->where('homeowner_id', $userId);
            })
            ->orderBy('date', 'desc');
    };
    
    $recent = (clone $query($userId, $user->user_type))
        ->whereIn('status', ['pending', 'accepted'])
        ->get();
        
    $past = (clone $query($userId, $user->user_type))
        ->whereIn('status', ['completed', 'declined'])
        ->get();

    return response()->json([
        'message' => 'All bookings retrieved successfully.',
        'recent_bookings' => $recent,
        'past_bookings' => $past,
    ], 200);
}
    // Get gardener's bookings
    public function getGardenerBookings($gardenerId)
    {
        $bookings = Booking::where('gardener_id', $gardenerId)
            ->with(['homeowner', 'services'])
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
            ->with(['homeowner', 'services'])
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
                'homeowner',
                'payments'// In case you need homeowner details
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
        $booking = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services', 'payments'])
            ->findOrFail($bookingId);

        return response()->json([
            'message' => 'Booking retrieved successfully',
            'booking' => $booking,
        ], 200);
    }
}