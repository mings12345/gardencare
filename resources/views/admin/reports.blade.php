<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | GardenCare Admin</title>
    <!-- Include all the same CSS and JS as your dashboard -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Paste all the same CSS from your dashboard.blade.php here */
    </style>
</head>
<body>
    <!-- Sidebar (same as dashboard) -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>GardenCare Admin</h3>
        </div>
        <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
        <!-- ... other menu items ... -->
        <a href="{{ route('admin.reports') }}" class="active">
            <i class="fas fa-chart-bar"></i> <span>Reports</span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Reports Dashboard</h1>
            <!-- Include the same user profile dropdown as dashboard -->
            <div class="user-profile" onclick="toggleDropdown()">
                <img src="https://ui-avatars.com/api/?name=Admin&background=4CAF50&color=fff" alt="Admin">
                <span>Admin</span>
                <div class="user-profile-dropdown" id="profileDropdown">
                    <a href="{{ route('admin.profile') }}"><i class="fas fa-user"></i> Profile</a>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

        <!-- Reports Content -->
        <div class="alert alert-info">
            Analyze and export system reports and statistics.
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bookings Overview</h5>
                        <canvas id="bookingsChart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Earnings Overview</h5>
                        <canvas id="earningsChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Export Reports</h5>
                <form action="{{ route('admin.exportReports') }}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="type">Report Type</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">Select report type</option>
                                <option value="bookings">Bookings</option>
                                <option value="earnings">Earnings</option>
                                <option value="users">Users</option>
                                <option value="services">Services</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i> Export
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include same scripts as dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Include the same toggleDropdown function from dashboard
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.user-profile') && !event.target.closest('.user-profile')) {
                const dropdowns = document.getElementsByClassName('user-profile-dropdown');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        });

        // Bookings Chart
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        const bookingsChart = new Chart(bookingsCtx, {
            type: 'line',
            data: {
                labels: @json($bookingData['labels']),
                datasets: [{
                    label: 'Bookings',
                    data: @json($bookingData['data']),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Earnings Chart
        const earningsCtx = document.getElementById('earningsChart').getContext('2d');
        const earningsChart = new Chart(earningsCtx, {
            type: 'bar',
            data: {
                labels: @json($earningsData['labels']),
                datasets: [{
                    label: 'Earnings (₱)',
                    data: @json($earningsData['data']),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>