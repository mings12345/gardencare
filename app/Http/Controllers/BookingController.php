<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Events\BookingStatusUpdated;
use App\Events\NewBooking;

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
            'payment_intent_id' => 'required|string',
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

             // Verify payment intent
        $paymentVerified = $this->verifyPaymentIntent(
            $request->payment_intent_id,
            $request->total_price
        );

        if (!$paymentVerified) {
            return response()->json([
                'type' => 'error',
                'message' => 'Payment verification failed'
            ], 402);
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
            'status' => 'pending',
            'payment_status' => 'paid',
            'payment_method' => 'card',
            'stripe_payment_intent_id' => $request->payment_intent_id,
        ]);

        // Attach services to the booking
        foreach ($request->service_ids as $service_id) {
            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service_id,
            ]);
        }

         // Determine which provider to notify
         $providerId = null;
         if ($request->type === 'Gardening') {
             $providerId = $request->gardener_id;
         } else if ($request->type === 'Landscaping') {
             $providerId = $request->serviceprovider_id;
         }

             // Create payment record
        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $request->homeowner_id,
            'amount' => $request->total_price,
            'payment_method' => 'card',
            'transaction_id' => $request->payment_intent_id,
            'status' => 'succeeded',
            'currency' => 'php',
        ]);

            
         
        // Broadcast the event to both homeowner and service provider
          event(new NewBooking($booking));

        return response()->json([
            'message' => 'Booking created successfully',
            'type' => 'success',
            'booking' => $booking->load(['homeowner', 'services']),
        ], 201);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,accepted,declined,completed,cancelled',
        ]);

        $booking = Booking::findOrFail($id);
        $oldStatus = $booking->status;
        
        // Update the status
        $booking->status = $request->status;
        $booking->save();

        // Broadcast the status update
        event(new BookingStatusUpdated($booking, $oldStatus));

        return response()->json($booking);
    }

    protected function verifyPaymentIntent($paymentIntentId, $amount)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            return $paymentIntent->status === 'succeeded' && 
                   $paymentIntent->amount === (int)($amount * 100);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function sendBookingNotification(Booking $booking)
    {
        $notificationData = [
            'title' => 'New Booking Request',
            'body' => "You have a new {$booking->type} booking request",
            'booking_id' => $booking->id,
            'type' => 'booking_request',
            'created_at' => now()->toDateTimeString(),
        ];

        if ($booking->type === 'Gardening' && $booking->gardener_id) {
            event(new BookingNotification(
                $booking->gardener_id, 
                $notificationData
            ));
        } elseif ($booking->type === 'Landscaping' && $booking->serviceprovider_id) {
            event(new BookingNotification(
                $booking->serviceprovider_id, 
                $notificationData
            ));
        }
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
                'homeowner' // In case you need homeowner details
            ])
            ->orderBy('date', 'desc')
            ->get();
    
        return response()->json([
            'message' => 'Homeowner bookings retrieved successfully.',
            'bookings' => $bookings,
        ], 200);
    }

    // Get booking details
    public function show($bookingId)
    {
        $booking = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services'])
            ->findOrFail($bookingId);

        return response()->json([
            'message' => 'Booking retrieved successfully',
            'booking' => $booking,
        ], 200);
    }
}