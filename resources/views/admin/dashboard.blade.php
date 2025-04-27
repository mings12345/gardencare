<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Sidebar styling */
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #4CAF50; /* Green theme */
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Main content styling */
        .main-content {
            margin-left: 250px; /* Same as sidebar width */
            padding: 20px;
            background-color: #f5f5f5; /* Light background */
        }

        /* Card grid styling */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            transition: transform 0.2s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .card-text {
            font-size: 16px;
            color: #666;
        }

        /* Gardening-themed background */
        body {
            background-image: url('https://www.transparenttextures.com/patterns/leaves.png'); /* Subtle leaf pattern */
            background-repeat: repeat;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('admin.manageBookings') }}">
            <i class="fas fa-calendar-alt"></i> Manage Bookings
        </a>
        <a href="{{ route('admin.manageUsers') }}">
            <i class="fas fa-users"></i> Manage Users
        </a>
        <a href="{{ route('admin.manageServices') }}">
            <i class="fas fa-tools"></i> Manage Services
        </a>
        <a href="{{ route('admin.manageFeedback') }}">
            <i class="fas fa-comments"></i> Manage Feedback
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Admin Dashboard</h1>

        <!-- Card Grid -->
        <div class="card-grid">
            <!-- Total Bookings Card -->
            <a href="{{ route('admin.manageBookings') }}" class="text-decoration-none">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Bookings</h5>
                        <p class="card-text">{{ $totalBookings }}</p>
                    </div>
                </div>
            </a>

            <!-- Total Users Card -->
            <a href="{{ route('admin.manageUsers') }}" class="text-decoration-none">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text">{{ $totalHomeowners + $totalGardeners + $totalServiceProviders }}</p>
                    </div>
                </div>
            </a>

            <!-- Total Earnings Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Earnings</h5>
                    <p class="card-text">${{ number_format($totalEarnings, 2) }}</p>
                </div>
            </div>

            <!-- Total Services Card -->
            <a href="{{ route('admin.manageServices') }}" class="text-decoration-none">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Services</h5>
                        <p class="card-text">{{ $services->count() }}</p>
                    </div>
                </div>
            </a>

            <!-- Feedback Management Card -->
            <a href="{{ route('admin.manageFeedback') }}" class="text-decoration-none">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">View Feedback</h5>
                        <p class="card-text">{{ $feedbacks->count() }} Feedbacks</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>