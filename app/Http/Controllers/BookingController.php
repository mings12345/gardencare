<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingService;
use Illuminate\Http\Request;
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
            'gardener_id' => $request->get('gardener_id'),
            'serviceprovider_id' => $request->get('serviceprovider_id'),
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

        return response()->json([
            'message' => 'Booking created successfully',
            'type' => 'success',
            'booking' => $booking,
        ], 201);
    }

    // Get bookings for a specific gardener
    public function getGardenerBookings($gardenerId)
    {
        $bookings = Booking::where('gardener_id', $gardenerId)->with('services')->get();

        return response()->json([
            'bookings' => $bookings,
        ]);
    }

    // Get bookings for a specific service provider
    public function getServiceProviderBookings($serviceProviderId)
    {
        $bookings = Booking::where('serviceprovider_id', $serviceProviderId)
            ->with('services')
            ->get();

        return response()->json([
            'bookings' => $bookings,
        ]);
    }
    // Add this method to your BookingController
public function index()
{
    // Fetch all bookings with their related services
    $bookings = Booking::with('services')->get();

    // Pass the bookings to the view
    return view('bookings.index', compact('bookings'));
}
}