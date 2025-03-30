<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Message;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Display a list of bookings
    public function index()
    {
        // Fetch all bookings with related data (e.g., homeowner, gardener, services)
        $bookings = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services'])->get();

        // Pass the bookings to the view
        return view('bookings.index', compact('bookings'));
    }

    // Create a new booking
    public function store(Request $request)
    {
        // Define base validation rules
        $rules = [
            'type' => 'required|in:Gardening,Landscaping',
            'homeowner_id' => 'required|exists:users,id',
            'service_ids' => 'required|array',
            'address' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'total_price' => 'required|numeric',
            'special_instructions' => 'nullable|string',
        ];

        // Add conditional validation rules based on the booking type
        if ($request->get('type') === 'Gardening') {
            $rules['gardener_id'] = 'required|exists:users,id';
        } elseif ($request->get('type') === 'Landscaping') {
            $rules['serviceprovider_id'] = 'required|exists:users,id';
        }

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['type' => 'error', 'messages' => $validator->errors()], 422);
        }

        // Create the booking
        $booking = Booking::create([
            'type' => $request->get('type'),
            'homeowner_id' => $request->get('homeowner_id'),
            'gardener_id' => $request->get('gardener_id') ?? null,
            'serviceprovider_id' => $request->get('serviceprovider_id') ?? null,
            'address' => $request->get('address'),
            'date' => $request->get('date'),
            'time' => $request->get('time'),
            'total_price' => $request->get('total_price'),
            'special_instructions' => $request->get('special_instructions'),
        ]);

        // Attach services to the booking
        foreach ($request->get('service_ids') as $service_id) {
            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service_id,
            ]);
        }

        // Send notification to the service provider (gardener or landscaper)
        $notificationService = app(\App\Services\NotificationService::class);
        $recipientId = $request->get('type') === 'Gardening' 
            ? $request->get('gardener_id') 
            : $request->get('serviceprovider_id');
            
        $userType = $request->get('type') === 'Gardening' ? 'gardener' : 'service_provider';
        
        $notificationService->sendToUser(
            $recipientId,
            $userType,
            [
                'title' => 'New Booking Received',
                'body' => 'You have a new booking request',
                'type' => 'new_booking',
                'booking_id' => $booking->id,
            ]
        );

        return response()->json([
            'message' => 'Booking created successfully',
            'type' => 'success',
            'booking' => $booking->load('services'), // Load services to return in response
        ], 201);
    }

    public function getMessages(Booking $booking)
    {
        // Verify the authenticated user is part of this booking
        $user = Auth::user();
        
        if (!$user || !in_array($user->id, [$booking->homeowner_id, $booking->gardener_id, $booking->serviceprovider_id])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = Message::where('booking_id', $booking->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a new message for a booking
     */
    public function sendMessage(Request $request, Booking $booking)
    {
        $user = Auth::user();
        
        // Verify user is part of this booking
        if (!$user || !in_array($user->id, [$booking->homeowner_id, $booking->gardener_id, $booking->serviceprovider_id])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'booking_id' => $booking->id,
            'sender_id' => $user->id,
            'receiver_id' => $this->getReceiverId($booking, $user),
            'message' => $request->message,
        ]);

        return response()->json($message, 201);
    }

    /**
     * Helper method to determine receiver ID
     */
    private function getReceiverId(Booking $booking, $user)
    {
        if ($user->id === $booking->homeowner_id) {
            return $booking->gardener_id ?? $booking->serviceprovider_id;
        }
        
        return $booking->homeowner_id;
    }
}