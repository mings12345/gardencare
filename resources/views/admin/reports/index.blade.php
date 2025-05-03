@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>Reports Overview</h1>

    <h3>User Reports</h3>
    <ul>
        <li>Total Users: {{ $totalUsers }}</li>
        <li>Homeowners: {{ $homeowners }}</li>
        <li>Gardeners: {{ $gardeners }}</li>
        <li>Service Providers: {{ $serviceProviders }}</li>
    </ul>

    <h3>Booking Reports</h3>
    <ul>
        <li>Total Bookings: {{ $totalBookings }}</li>
        <li>Completed: {{ $completedBookings }}</li>
        <li>Cancelled: {{ $cancelledBookings }}</li>
        <li>Pending: {{ $pendingBookings }}</li>
    </ul>

    <h3>Payment Reports</h3>
    <ul>
        <li>Total Revenue: ₱{{ number_format($totalRevenue, 2) }}</li>
        <li>Today’s Revenue: ₱{{ number_format($dailyRevenue, 2) }}</li>
    </ul>

    <h3>Feedback Reports</h3>
    <ul>
        <li>Average Rating: {{ number_format($averageRating, 2) }}</li>
        <li>Low Ratings (<3): {{ $lowRatings }}</li>
    </ul>
</div>
@endsection
