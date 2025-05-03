<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | GardenCare Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --accent-color: #8BC34A;
            --light-color: #F1F8E9;
            --dark-color: #1B5E20;
            --text-color: #333;
            --text-light: #666;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: var(--text-color);
        }

        /* Sidebar styling */
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--dark-color) 100%);
        }

        .main-content {
            background-color: #f8fafc;
        }

        .card {
            border-radius: 12px;
            border: none;
            box-shadow: var(--shadow);
            transition: var(--transition);
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            color: var(--primary-color);
        }

        .chart-container {
            position: relative;
            height: 300px;
            padding: 1rem;
        }

        .report-filter-card {
            background-color: white;
            border-left: 4px solid var(--accent-color);
        }

        .export-btn {
            background-color: var(--primary-color);
            border: none;
            padding: 0.5rem 1.5rem;
            transition: var(--transition);
        }

        .export-btn:hover {
            background-color: var(--dark-color);
            transform: translateY(-2px);
        }

        .stat-card {
            border-top: 4px solid var(--accent-color);
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-card .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .stat-card i {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
        }

        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
            }
            
            .stat-card .stat-value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Your existing sidebar content -->
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1><i class="fas fa-chart-line me-3"></i> Reports Dashboard</h1>
            <!-- User profile dropdown -->
        </div>

        <!-- Welcome Alert -->
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <div>
                <strong>Analytics Ready!</strong> View and export system reports and statistics.
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Quick Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body position-relative">
                        <i class="fas fa-calendar-check"></i>
                        <div class="stat-value">{{ $totalBookings }}</div>
                        <div class="stat-label">Total Bookings</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body position-relative">
                        <i class="fas fa-users"></i>
                        <div class="stat-value">{{ $totalUsers }}</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body position-relative">
                        <i class="fas fa-peso-sign"></i>
                        <div class="stat-value">₱{{ number_format($totalEarnings, 2) }}</div>
                        <div class="stat-label">Total Earnings</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body position-relative">
                        <i class="fas fa-star"></i>
                        <div class="stat-value">{{ number_format($averageRating, 1) }}/5</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Bookings Overview</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="bookingsDropdown" data-bs-toggle="dropdown">
                                This Month
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">This Week</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="bookingsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-peso-sign me-2"></i> Earnings Overview</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="earningsDropdown" data-bs-toggle="dropdown">
                                This Month
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">This Week</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="earningsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Reports Card -->
        <div class="card report-filter-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-export me-2"></i> Export Reports</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.exportReports') }}" method="POST">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="type" class="form-label">Report Type</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">Select report type</option>
                                <option value="bookings">Bookings</option>
                                <option value="earnings">Earnings</option>
                                <option value="users">Users</option>
                                <option value="services">Services</option>
                                <option value="ratings">Ratings</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn export-btn w-100">
                                <i class="fas fa-download me-2"></i> Export Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i> Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                                <th>User</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $activity)
                            <tr>
                                <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->causer->name ?? 'System' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize charts with your data
        const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
        const bookingsChart = new Chart(bookingsCtx, {
            type: 'line',
            data: {
                labels: @json($bookingData['labels']),
                datasets: [{
                    label: 'Bookings',
                    data: @json($bookingData['data']),
                    backgroundColor: 'rgba(46, 125, 50, 0.1)',
                    borderColor: 'rgba(46, 125, 50, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        const earningsCtx = document.getElementById('earningsChart').getContext('2d');
        const earningsChart = new Chart(earningsCtx, {
            type: 'bar',
            data: {
                labels: @json($earningsData['labels']),
                datasets: [{
                    label: 'Earnings (₱)',
                    data: @json($earningsData['data']),
                    backgroundColor: 'rgba(139, 195, 74, 0.7)',
                    borderColor: 'rgba(139, 195, 74, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Date range validation
        document.getElementById('end_date').addEventListener('change', function() {
            const startDate = document.getElementById('start_date').value;
            if (startDate && this.value < startDate) {
                alert('End date must be after start date');
                this.value = '';
            }
        });
    </script>
</body>
</html>