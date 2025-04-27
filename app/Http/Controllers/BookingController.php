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

        // Broadcast the event to both homeowner and service provider
          event(new NewBooking($booking));

        return response()->json([
            'message' => 'Booking created successfully with Booking Number: ' . $booking->id,
            'type' => 'success',
            'booking' => $booking->load(['homeowner', 'services']),
        ], 201);
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
        $request->validate([
            'status' => 'required|string|in:pending,accepted,declined,completed',
        ]);

        $booking = Booking::with(['payments'=>fn($q)=>$q->where('payment_status','Pending'),'homeowner','serviceProvider','gardener'])->findOrFail($id);
        $oldStatus = $booking->status;
        
        \Log::info('Updating booking status', [
            'booking_id' => $id,
            'old_status' => $oldStatus,
            'new_status' => $request->status
        ]);

        $setting = Setting::first()?->admin_admin_fee_percentage??3;
        $admin_fee_percent = $setting?->admin_admin_fee_percentage??3;
        $admin_wallet = User::find($setting->admin_user_wallet);
        if($request->status == 'accepted'){
            $booking->payments->each(function($payment) use($admin_fee_percent) {
                $amount_paid = $payment->amount_paid;
                $admin_fee = $amount_paid * ($admin_fee_percent / 100);
                $payment->update(
                    [
                        'payment_status' => 'Received',
                        'receiver_no' => auth()->user()->account??'09xxxxxxxx',
                        'amount_paid'  => $amount_paid  - $admin_fee,
                        'admin_fee' => $admin_fee
                    ]);

                    //add the to the provider 
                    $payment->serviceProvider?->increment('balance', $amount_paid-$admin_fee);

                    //add the to the gardener
                    $payment->gardener?->increment('balance', $amount_paid-$admin_fee);

                    //Debit Admin fee to admin wallet
                    $admin_wallet?->increment('balance', $admin_fee);
            });
        }elseif($request->status == 'completed'){
            $total_price = $booking->total_price;
            $total_paid = Payment::where('booking_id', $booking->id)->where('payment_status','Received')->sum( fn($q)=>$q->amount_paid+$q->admin_fee );
            $total_balance =  $total_price-$total_paid; 
            if($total_balance > 0){
                $admin_fee = $total_balance * ($admin_fee_percent / 100);
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount_paid' => $total_balance - $admin_fee,
                    'payment_date' => now(),
                    'admin_fee' => $admin_fee,
                    'payment_status' => 'Received',
                    'sender_no' => $booking->payments->first()->sender_no,
                    'receiver_no' => auth()->user()->account??'09xxxxxxxx',
                ]);

                //credit full amount to homeowner
                $payment->homeowner?->decrement('balance', $total_balance);

                //add the to the provider 
                $payment->serviceProvider?->increment('balance', $total_balance-$admin_fee);

                //add the to the gardener
                $payment->gardener?->increment('balance', $total_balance-$admin_fee);

                //Debit Admin fee to admin wallet
                $admin_wallet?->increment('balance', $admin_fee);
            }
        }
        
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
        return response()->json($e->errors(), 422);
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