<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class BookingController extends Controller
{
    // Display a list of bookings
    public function index()
    {
        $bookings = Booking::with(['homeowner', 'gardener', 'serviceProvider', 'services'])->get();
        return view('bookings.index', compact('bookings'));
    }

    // Create a new booking (notifications removed)
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

        try {
            $provider = $request->gardener_id 
                ? Gardener::find($request->gardener_id)
                : ServiceProvider::find($request->serviceprovider_id);
        
            $homeowner = Homeowner::find($request->homeowner_id);
        
            if ($provider && $homeowner) {
                Notification::create([
                    'user_id' => $request->homeowner_id,
                    'title' => 'Booking Confirmed',
                    'message' => 'Your booking with ' . $provider->name . ' has been confirmed',
                    'type' => 'booking',
                    'data' => [
                        'booking_id' => $booking->id,
                        'type' => 'booking_created',
                        'provider_name' => $provider->name,
                        'date' => $booking->date,
                        'time' => $booking->time
                    ]
                ]);
        
                Notification::create([
                    'user_id' => $provider->user_id ?? null, // This must exist
                    'title' => 'New Booking Request',
                    'message' => 'New booking from ' . $homeowner->name . ' for ' . $booking->date,
                    'type' => 'booking',
                    'data' => [
                        'booking_id' => $booking->id,
                        'type' => 'new_booking',
                        'homeowner_name' => $homeowner->name,
                        'address' => $booking->address,
                        'date' => $booking->date,
                        'time' => $booking->time,
                        'services' => $booking->services->pluck('name'),
                        'total_price' => $booking->total_price
                    ]
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("Notification error for booking {$booking->id}: " . $e->getMessage());
            // Optionally continue silently or respond with a warning
        }

        return response()->json([
            'message' => 'Booking created successfully',
            'type' => 'success',
            'booking' => $booking->load(['homeowner', 'services']),
        ], 201);
    }

        public function respondToBooking(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
            'reason' => 'nullable|string|max:255',
        ]);

        $booking = Booking::findOrFail($bookingId);
        
        // Update booking status
        $booking->update([
            'status' => $validated['status'],
            'status_reason' => $validated['reason'] ?? null,
        ]);

        // Create notification for homeowner
        Notification::create([
            'user_id' => $booking->homeowner_id,
            'title' => 'Booking ' . ucfirst($validated['status']),
            'message' => 'Your booking has been ' . $validated['status'] . ' by ' . $booking->provider->name,
            'type' => 'booking',
            'data' => [
                'booking_id' => $booking->id,
                'type' => 'booking_updated',
                'status' => $validated['status']
            ]
        ]);

        return response()->json([
            'message' => 'Booking response submitted successfully',
            'booking' => $booking,
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