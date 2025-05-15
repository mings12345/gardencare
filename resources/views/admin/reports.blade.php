<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | GardenCare Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --accent-color: #8BC34A;
            --light-color: #F1F8E9;
            --dark-color: #1B5E20;
            --text-color: #333;
            --text-light: #666;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: var(--text-color);
        }

        .card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            color: var(--primary-color);
        }

        .chart-container {
            position: relative;
            height: 400px;
            padding: 1rem;
        }

        .btn-group .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .btn-group .btn.active {
            background-color: var(--primary-color);
            color: white;
        }

        .export-btn {
            background-color: var(--primary-color);
            border: none;
            padding: 0.5rem 1.5rem;
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

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white;
                color: black;
            }
            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Quick Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-value">{{ $totalBookings }}</div>
                        <div class="stat-label">Total Bookings</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-value">₱{{ number_format($totalEarnings, 2) }}</div>
                        <div class="stat-label">Total Earnings</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="stat-value">{{ number_format($averageRating, 1) }}/5</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Visualization Row -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Reports Overview</h5>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary active" data-chart-type="bar">Bar</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-chart-type="line">Line</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-chart-type="pie">Pie</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="reportsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Export Reports Card -->
        <div class="card no-print">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-export me-2"></i> Export Reports</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.exportReports') }}" method="POST" id="reportForm">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="type" class="form-label">Report Type</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="bookings">Bookings Report</option>
                                <option value="earnings">Earnings Report</option>
                                <option value="ratings">Ratings Report</option>
                                <option value="users">Users Report</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" onclick="generatePDF()" class="btn btn-primary w-100">
                                <i class="fas fa-file-pdf me-2"></i> PDF
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Add the Users Report section -->
<div id="usersReport" style="display:none;">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? 'N/A' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $user->user_type)) }}</td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
        <!-- Report Content -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="reportTitle">Bookings Report</h5>
                <button class="btn btn-sm btn-outline-secondary no-print" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
            <div class="card-body">
                <!-- Bookings Report -->
                <div id="bookingsReport">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Date</th>
                                    <th>Homeowner</th>
                                    <th>Service Provider</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->date ? date('M d, Y', strtotime($booking->date)) : 'N/A' }}</td>
                                    <td>{{ $booking->homeowner->name }}</td>
                                    <td>
                                        @if($booking->gardener_id)
                                            {{ $booking->gardener->name }} (Gardener)
                                        @elseif($booking->serviceprovider_id)
                                            {{ $booking->serviceProvider->name }} (Service Provider)
                                        @endif
                                    </td>
                                    <td>₱{{ number_format($booking->total_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Earnings Report (hidden by default) -->
                <div id="earningsReport" style="display:none;">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Admin Fee</th>
                                    <th>Provider Earnings</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    @foreach($booking->payments as $payment)
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ date('M d, Y', strtotime($payment->payment_date)) }}</td>
                                        <td>₱{{ number_format($payment->amount_paid, 2) }}</td>
                                        <td>₱{{ number_format($payment->admin_fee, 2) }}</td>
                                        <td>₱{{ number_format($payment->amount_paid - $payment->admin_fee, 2) }}</td>
                                        <td>{{ ucfirst($payment->payment_status) }}</td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Ratings Report (hidden by default) -->
                <div id="ratingsReport" style="display:none;">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Date</th>
                                    <th>Rating</th>
                                    <th>Feedback</th>
                                    <th>Rated By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ratings as $rating)
                                <tr>
                                    <td>{{ $rating->booking_id }}</td>
                                    <td>{{ $rating->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $rating->rating ? '' : '-empty' }} text-warning"></i>
                                        @endfor
                                        ({{ $rating->rating }})
                                    </td>
                                    <td>{{ $rating->feedback ?? 'No feedback' }}</td>
                                    <td>{{ $rating->booking->homeowner->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Toggle between report types
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            document.getElementById('reportTitle').textContent = `${type.charAt(0).toUpperCase() + type.slice(1)} Report`;
            
            // Hide all reports
            document.getElementById('bookingsReport').style.display = 'none';
            document.getElementById('earningsReport').style.display = 'none';
            document.getElementById('ratingsReport').style.display = 'none';
            document.getElementById('usersReport').style.display = 'none';
            // Show selected report
            document.getElementById(`${type}Report`).style.display = 'block';
        });

        // Generate PDF
        function generatePDF() {
            const element = document.getElementById('reportTitle').parentElement.parentElement;
            const opt = {
                margin: 10,
                filename: `${document.getElementById('type').value}_report.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            html2pdf().from(element).set(opt).save();
        }

        // Date range validation
        document.getElementById('end_date').addEventListener('change', function() {
            const startDate = document.getElementById('start_date').value;
            if (startDate && this.value < startDate) {
                alert('End date must be after start date');
                this.value = '';
            }
        });

        document.getElementById('reportForm').addEventListener('submit', function(e) {
    // For PDF generation, we prevent the default form submission
    e.preventDefault();
});
    // Chart Visualization
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data from PHP
    const bookingsData = {
        total: {{ $totalBookings }},
        pending: {{ $bookings->where('status', 'pending')->count() }},
        completed: {{ $bookings->where('status', 'completed')->count() }}
    };

    const earningsData = {
        total: {{ $totalEarnings }},
        adminFees: {{ $bookings->sum(function($booking) { return $booking->payments->sum('admin_fee'); }) }},
        providerEarnings: {{ $bookings->sum(function($booking) { 
            return $booking->payments->sum('amount_paid') - $booking->payments->sum('admin_fee'); 
        }) }}
    };

    const usersData = {
        homeowners: {{ $users->where('user_type', 'homeowner')->count() }},
        gardeners: {{ $users->where('user_type', 'gardener')->count() }},
        serviceProviders: {{ $users->where('user_type', 'service_provider')->count() }}
    };

    const ratingsData = {
        average: {{ $averageRating }},
        counts: [
            {{ $ratings->where('rating', 1)->count() }},
            {{ $ratings->where('rating', 2)->count() }},
            {{ $ratings->where('rating', 3)->count() }},
            {{ $ratings->where('rating', 4)->count() }},
            {{ $ratings->where('rating', 5)->count() }}
        ]
    };

    // Chart configuration
    const ctx = document.getElementById('reportsChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Bookings', 'Earnings', 'Users', 'Ratings'],
            datasets: [
                {
                    label: 'Total Bookings',
                    data: [bookingsData.total, 0, 0, 0],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pending Bookings',
                    data: [bookingsData.pending, 0, 0, 0],
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Completed Bookings',
                    data: [bookingsData.completed, 0, 0, 0],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Earnings (₱)',
                    data: [0, earningsData.total, 0, 0],
                    backgroundColor: 'rgba(153, 102, 255, 0.7)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Admin Fees (₱)',
                    data: [0, earningsData.adminFees, 0, 0],
                    backgroundColor: 'rgba(255, 99, 132, 0.7)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Homeowners',
                    data: [0, 0, usersData.homeowners, 0],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Gardeners',
                    data: [0, 0, usersData.gardeners, 0],
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Service Providers',
                    data: [0, 0, usersData.serviceProviders, 0],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Average Rating',
                    data: [0, 0, 0, ratingsData.average],
                    backgroundColor: 'rgba(255, 206, 86, 0.7)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.dataset.label.includes('Earnings') || 
                                context.dataset.label.includes('Fees')) {
                                label += '₱' + context.raw.toLocaleString();
                            } else {
                                label += context.raw;
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Chart type toggle buttons
    document.querySelectorAll('[data-chart-type]').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active state
            document.querySelectorAll('[data-chart-type]').forEach(b => {
                b.classList.remove('active');
            });
            this.classList.add('active');
            
            // Change chart type
            chart.destroy();
            chart = new Chart(ctx, {
                type: this.dataset.chartType,
                data: chart.data,
                options: chart.options
            });
        });
    });

    // Update chart when report type changes
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        
        // Hide all datasets initially
        chart.data.datasets.forEach(dataset => {
            dataset.hidden = true;
        });
        
        // Show relevant datasets based on report type
        if (type === 'bookings') {
            chart.data.datasets.filter(d => d.label.includes('Booking')).forEach(d => {
                d.hidden = false;
            });
        } else if (type === 'earnings') {
            chart.data.datasets.filter(d => d.label.includes('Earnings') || d.label.includes('Fees')).forEach(d => {
                d.hidden = false;
            });
        } else if (type === 'users') {
            chart.data.datasets.filter(d => d.label.includes('Homeowner') || 
                                          d.label.includes('Gardener') || 
                                          d.label.includes('Service Provider')).forEach(d => {
                d.hidden = false;
            });
        } else if (type === 'ratings') {
            chart.data.datasets.filter(d => d.label.includes('Rating')).forEach(d => {
                d.hidden = false;
            });
        }
        
        chart.update();
    });
});

function exportReport(format) {
    if (format === 'pdf') {
        const element = document.getElementById('reportTitle').parentElement.parentElement;
        const opt = {
            margin: 10,
            filename: `${document.getElementById('type').value}_report.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().from(element).set(opt).save();
    } else if (format === 'csv') {
        // Submit the form for CSV export
        document.getElementById('reportForm').submit();
    }
        }
    </script>
</body>
</html>