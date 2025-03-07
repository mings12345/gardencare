<?php

namespace App\Http\Controllers;

use App\Events\ServiceProviderBookingEvent; // Import the ServiceProviderBookingEvent
use App\Models\Booking;
use App\Models\BookingService;
use Illuminate\Http\Request;
use App\Events\GardenerBookingEvent;
use Validator;

class BookingController extends Controller
{
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

        // Trigger the appropriate event based on the booking type
        if ($request->get('type') === 'Gardening') {
            event(new GardenerBookingEvent($booking));
        } elseif ($request->get('type') === 'Landscaping') {
            event(new ServiceProviderBookingEvent($booking));
        }

        return response()->json([
            'message' => 'Booking created successfully',
            'type' => 'success',
            'booking' => $booking->load('services'), // Load services to return in response
        ], 201);
    }

    // Get bookings for a specific gardener
    public function getGardenerBookings($gardenerId)
    {
        $bookings = Booking::where('gardener_id', $gardenerId)
            ->with(['services', 'homeowner', 'gardener'])
            ->get();

        return response()->json(['bookings' => $bookings]);
    }

    // Get bookings for a specific service provider
    public function getServiceProviderBookings($serviceProviderId)
    {
        $bookings = Booking::where('serviceprovider_id', $serviceProviderId)
            ->with(['services', 'homeowner', 'serviceProvider'])
            ->get();

        return response()->json(['bookings' => $bookings]);
    }

    // Display all bookings in the view
    public function index()
    {
        // Fetch bookings with related data
        $bookings = Booking::with([
            'services', // Load the service details
            'homeowner',
            'gardener',
            'serviceProvider'
        ])->get();

        return view('bookings.index', compact('bookings'));
    }
}