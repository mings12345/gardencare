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
         // Send notification to the appropriate provider
         $this->sendBookingNotification($booking);
        return response()->json([
            'message' => 'Booking created successfully',
            'type' => 'success',
            'booking' => $booking->load(['homeowner', 'services']), // Load services to return in response
        ], 201);
    }   

    protected function sendBookingNotification(Booking $booking)
    {
        if ($booking->type === 'Gardening' && $booking->gardener_id) {
            $gardener = User::find($booking->gardener_id);
            $gardener->notify(new NewBookingNotification($booking));
        } elseif ($booking->type === 'Landscaping' && $booking->serviceprovider_id) {
            $provider = User::find($booking->serviceprovider_id);
            $provider->notify(new NewBookingNotification($booking));
        }
    }

    public function getGardenerBookings($gardenerId)
    {
        $bookings = Booking::where('gardener_id', $gardenerId)
            ->with(['homeowner', 'services']) // Include related data
            ->orderBy('date', 'desc') // Order by date
            ->get();

        return response()->json([
            'message' => 'Bookings retrieved successfully.',
            'bookings' => $bookings,
        ], 200);
    }

    public function getServiceProviderBookings($serviceProviderId)
    {
        $bookings = Booking::where('serviceprovider_id', $serviceProviderId)
            ->with(['homeowner', 'services']) // Include related data
            ->orderBy('date', 'desc') // Order by date
            ->get();

        return response()->json([
            'message' => 'Bookings retrieved successfully.',
            'bookings' => $bookings,
        ], 200);
    }

      // Update booking status (accept/reject)
      public function updateStatus(Request $request, $bookingId)
      {
          $validated = $request->validate([
              'status' => 'required|in:accepted,rejected,cancelled,completed',
              'reason' => 'nullable|string|max:255',
          ]);
  
          $booking = Booking::findOrFail($bookingId);
          $booking->update([
              'status' => $validated['status'],
              'status_reason' => $validated['reason'] ?? null,
          ]);
  
          // Here you could add notifications to the homeowner about status change
  
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