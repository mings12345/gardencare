<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Notifications\NewBookingNotification;
use App\Events\NewBookingEvent;

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
            'status' => 'pending',
        ]);

        // Attach services to the booking
        foreach ($request->service_ids as $service_id) {
            BookingService::create([
                'booking_id' => $booking->id,
                'service_id' => $service_id,
            ]);
        }

        // Send notifications
        $this->sendBookingNotification($booking);
        $this->broadcastBookingEvent($booking);

        return response()->json([
            'message' => 'Booking created successfully',
            'type' => 'success',
            'booking' => $booking->load(['homeowner', 'services']),
        ], 201);
    }

    /**
     * Send database notification to the provider
     */
    protected function sendBookingNotification(Booking $booking)
    {
        try {
            if ($booking->type === 'Gardening' && $booking->gardener_id) {
                $gardener = User::find($booking->gardener_id);
                $gardener->notify(new NewBookingNotification($booking));
            } elseif ($booking->type === 'Landscaping' && $booking->serviceprovider_id) {
                $provider = User::find($booking->serviceprovider_id);
                $provider->notify(new NewBookingNotification($booking));
            }
        } catch (\Exception $e) {
            \Log::error('Notification failed: '.$e->getMessage());
        }
    }

    /**
     * Broadcast real-time event via Pusher
     */
    protected function broadcastBookingEvent(Booking $booking)
    {
        try {
            $channelType = $booking->type === 'Gardening' ? 'gardener' : 'provider';
            $providerId = $booking->type === 'Gardening' 
                ? $booking->gardener_id 
                : $booking->serviceprovider_id;

            if ($providerId) {
                event(new NewBookingEvent($booking, $channelType));
            }
        } catch (\Exception $e) {
            \Log::error('Pusher broadcast failed: '.$e->getMessage());
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

    // Update booking status with validation
    public function updateStatus(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected,cancelled,completed',
            'reason' => 'nullable|string|max:255',
        ]);

        $booking = Booking::findOrFail($bookingId);
        
        // Additional validation for status transitions
        if ($booking->status === 'completed' && $validated['status'] !== 'completed') {
            return response()->json([
                'message' => 'Completed bookings cannot be modified'
            ], 422);
        }

        $booking->update([
            'status' => $validated['status'],
            'status_reason' => $validated['reason'] ?? null,
        ]);

        // Here you could add notifications to the homeowner about status change
        // $this->sendStatusChangeNotification($booking);

        return response()->json([
            'message' => 'Booking status updated successfully',
            'booking' => $booking,
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