<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Bookings</h5>
                        <p class="card-text">{{ $totalBookings }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pending Bookings</h5>
                        <p class="card-text">{{ $pendingBookings }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Completed Bookings</h5>
                        <p class="card-text">{{ $completedBookings }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Table -->
        <h2>All Bookings</h2>
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
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->type }}</td>
                    <td>{{ $booking->homeowner->name ?? 'N/A' }}</td>
                    <td>{{ $booking->gardener->name ?? 'N/A' }}</td>
                    <td>{{ $booking->serviceProvider->name ?? 'N/A' }}</td>
                    <td>{{ $booking->address }}</td>
                    <td>{{ $booking->date }}</td>
                    <td>{{ $booking->time }}</td>
                    <td>${{ number_format($booking->total_price, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $booking->status == 'completed' ? 'success' : ($booking->status == 'confirm' ? 'primary' : 'warning') }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-info">View</a>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>