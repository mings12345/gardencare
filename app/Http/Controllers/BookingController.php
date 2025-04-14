<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
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
            // Notify the relevant service provider
        if ($booking->type == 'Gardening') {
            $this->notifyGardener($booking);
        } else if ($booking->type == 'Landscaping') {
            $this->notifyServiceProvider($booking);
        }

        return response()->json([
            'message' => 'Booking created successfully',
            'type' => 'success',
            'booking' => $booking->load(['homeowner', 'services']),
        ], 201);
    }

        private function notifyGardener($booking)
    {
        $gardener = Gardener::find($booking->gardener_id);
        
        // Create notification record
        Notification::create([
            'user_id' => $gardener->user_id,
            'title' => 'New Booking Request',
            'message' => 'You have a new gardening booking request',
            'data' => json_encode($booking),
            'read' => false
        ]);
        
        event(new NewBookingEvent($gardener->user_id, $booking));
    }

    private function notifyServiceProvider($booking)
    {
        $provider = ServiceProvider::find($booking->serviceprovider_id);
        
        // Create notification record
        Notification::create([
            'user_id' => $provider->user_id,
            'title' => 'New Booking Request',
            'message' => 'You have a new landscaping booking request',
            'data' => json_encode($booking),
            'read' => false
        ]);
        
        event(new NewBookingEvent($provider->user_id, $booking));
    }

        public function getGardenerBookings(Request $request)
    {
        $gardener = Gardener::where('user_id', auth()->id())->first();
        
        if (!$gardener) {
            return response()->json(['message' => 'Gardener not found'], 404);
        }
        
        $bookings = Booking::where('gardener_id', $gardener->id)
                        ->where('type', 'Gardening')
                        ->with('homeowner', 'services')
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return response()->json(['bookings' => $bookings]);
    }

        public function getServiceProviderBookings(Request $request)
    {
        $provider = ServiceProvider::where('user_id', auth()->id())->first();
        
        if (!$provider) {
            return response()->json(['message' => 'Service provider not found'], 404);
        }
        
        $bookings = Booking::where('serviceprovider_id', $provider->id)
                        ->where('type', 'Landscaping')
                        ->with('homeowner', 'services')
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return response()->json(['bookings' => $bookings]);
    }


        public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Verify the user has permission to update this booking
        if ($booking->type == 'Gardening') {
            $gardener = Gardener::where('user_id', auth()->id())->first();
            if (!$gardener || $booking->gardener_id != $gardener->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } else {
            $provider = ServiceProvider::where('user_id', auth()->id())->first();
            if (!$provider || $booking->serviceprovider_id != $provider->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        }
        
        $booking->status = $request->status;
        $booking->save();
        
        // Notify homeowner about status change
        Notification::create([
            'user_id' => $booking->homeowner_id,
            'title' => 'Booking Status Updated',
            'message' => "Your booking has been {$request->status}",
            'data' => json_encode($booking),
            'read' => false
        ]);
        
        return response()->json(['message' => 'Booking status updated', 'booking' => $booking]);
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