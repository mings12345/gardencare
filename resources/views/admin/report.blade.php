<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Booking Report</h1>

        <!-- Bookings Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Homeowner</th>
                    <th>Gardener</th>
                    <th>Service Provider</th>
                    <th>Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->homeowner->name ?? 'N/A' }}</td>
                    <td>{{ $booking->gardener->name ?? 'N/A' }}</td>
                    <td>{{ $booking->serviceProvider->name ?? 'N/A' }}</td>
                    <td>{{ $booking->date }}</td>
                    <td>${{ number_format($booking->total_price, 2) }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>