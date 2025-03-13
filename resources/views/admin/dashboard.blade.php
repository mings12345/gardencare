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

        <!-- Booking Management Card -->
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('admin.manageBookings') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Booking Management</h5>
                            <p class="card-text">Click to view booking details</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- User Management Card -->
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('admin.manageUsers') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">User Management</h5>
                            <p class="card-text">Click to view user details</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Service Management Card -->
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('admin.manageServices') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Service Management</h5>
                            <p class="card-text">Click to view service details</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Feedback Management Card -->
        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('admin.manageFeedback') }}" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Feedback Management</h5>
                            <p class="card-text">Click to view feedback details</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>