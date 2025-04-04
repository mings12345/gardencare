<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>All Bookings</h1>

        @if($bookings->isEmpty())
            <p>No bookings found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Homeowner</th>
                        <th>Gardener</th>
                        <th>Service Provider</th>
                        <th>Address</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Total Price</th>
                        <th>Special Instructions</th>
                        <th>Datetime Submitted</th>
                        <th>Services</th>
                    </tr>
                </thead> 
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->type }}</td>
                            <td>{{ optional($booking->homeowner)->name ?? 'N/A' }}</td>
                            <td>{{ optional($booking->gardener)->name ?? 'N/A' }}</td>
                            <td>{{ optional($booking->serviceProvider)->name ?? 'N/A' }}</td>
                            <td>{{ $booking->address }}</td>
                            <td>{{ $booking->date }}</td>
                            <td>{{ $booking->time }}</td>
                            <td>{{ $booking->total_price }}</td>
                            <td>{{ $booking->special_instructions ?? 'None' }}</td>
                            <td>{{ $booking->created_at}}</td>
                            <td>
                                <ul>
                                    @foreach($booking->services as $service)
                                        <li>{{ $service->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
