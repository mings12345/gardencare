<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | GardenCare Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Add Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-card {
            margin-top: 1.5rem;
        }
        .chart-container {
            position: relative;
            height: 400px;
            padding: 1rem;
        }
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
            height: 300px;
            padding: 1rem;
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

        /* Date range filter styles */
        .date-range-container {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .date-range-item {
            flex: 1;
            min-width: 120px;
        }

        .date-range-status {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .date-range-container {
                margin-top: 10px;
                width: 100%;
            }
            
            .date-range-item {
                flex: 1;
            }
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
            .date-range-container {
                display: none !important;
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
        
        <!-- Bookings Overview Chart -->
        <div class="card chart-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Bookings Overview</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="bookingsChart"></canvas>
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
                                <option value="services">Services Report</option>
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
        
        <!-- Users Report (hidden) -->
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

        <!-- Services Report (hidden) -->
        <div id="servicesReport" style="display:none;">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Service ID</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->type }}</td>
                            <td>{{ $service->name }}</td>
                            <td>₱{{ number_format($service->price, 2) }}</td>
                            <td>{{ $service->description ?? 'No description' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Report Content -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0" id="reportTitle">Bookings Report</h5>
                    <small class="date-range-status" id="dateRangeStatus"></small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="date-range-container me-3">
                        <div class="date-range-item">
                            <input type="date" class="form-control form-control-sm" id="reportStartDate" 
                                value="{{ date('Y-m-01') }}">
                        </div>
                        <div class="date-range-item">
                            <input type="date" class="form-control form-control-sm" id="reportEndDate" 
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="me-3">
                        <select class="form-select form-select-sm" id="userFilter">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                @if($user->user_type !== 'admin')
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst(str_replace('_', ' ', $user->user_type)) }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary no-print" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
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
                            <tbody id="bookingsReportBody">
                                @foreach($bookings as $booking)
                                <tr data-booking-date="{{ $booking->date ? date('Y-m-d', strtotime($booking->date)) : '' }}"
                                    data-homeowner-id="{{ $booking->homeowner->id }}"
                                    data-provider-id="{{ $booking->gardener_id ?? $booking->serviceprovider_id ?? '' }}">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Initialize and render the chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('bookingsChart').getContext('2d');
            
            // Get the last 6 months for labels
            const months = [];
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            const currentDate = new Date();
            
            for (let i = 5; i >= 0; i--) {
                const date = new Date();
                date.setMonth(currentDate.getMonth() - i);
                months.push(monthNames[date.getMonth()] + ' ' + date.getFullYear());
            }
            
            // Create the chart
            const bookingsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Completed Bookings',
                            data: [{{ implode(',', $completedBookingsByMonth) }}],
                            backgroundColor: '#4CAF50',
                            borderColor: '#2E7D32',
                            borderWidth: 1
                        },
                        {
                            label: 'Accepted Bookings',
                            data: [{{ implode(',', $acceptedBookingsByMonth) }}],
                            backgroundColor: '#2196F3',
                            borderColor: '#0D47A1',
                            borderWidth: 1
                        },
                        {
                            label: 'Pending Bookings',
                            data: [{{ implode(',', $pendingBookingsByMonth) }}],
                            backgroundColor: '#FFC107',
                            borderColor: '#FFA000',
                            borderWidth: 1
                        },
                        {
                            label: 'Declined Bookings',
                            data: [{{ implode(',', $declinedBookingsByMonth) }}],
                            backgroundColor: '#F44336',
                            borderColor: '#C62828',
                            borderWidth: 1
                        },
                        {
                            label: 'Total Earnings (₱)',
                            data: [{{ implode(',', $earningsByMonth) }}],
                            backgroundColor: '#9C27B0',
                            borderColor: '#6A1B9A',
                            borderWidth: 3,
                            type: 'line',
                            yAxisID: 'y',
                            tension: 0.3,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Bookings / Earnings (₱)'
                            },
                            stacked: false
                        },
                        x: {
                            stacked: false,
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label.includes('Earnings')) {
                                        return label + ': ₱' + context.raw.toLocaleString();
                                    }
                                    return label + ': ' + context.raw;
                                },
                                footer: function(tooltipItems) {
                                    let sum = 0;
                                    tooltipItems.forEach(function(tooltipItem) {
                                        if (!tooltipItem.dataset.label.includes('Earnings')) {
                                            sum += tooltipItem.parsed.y;
                                        }
                                    });
                                    return 'Total Bookings: ' + sum;
                                }
                            },
                            mode: 'index',
                            intersect: false
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });

            // Date range filtering for bookings report
            const reportStartDate = document.getElementById('reportStartDate');
            const reportEndDate = document.getElementById('reportEndDate');
            const userFilter = document.getElementById('userFilter');
            const bookingsReportBody = document.getElementById('bookingsReportBody');
            const dateRangeStatus = document.getElementById('dateRangeStatus');
            const bookingRows = bookingsReportBody.querySelectorAll('tr');

            function filterBookings() {
                const startDate = reportStartDate.value;
                const endDate = reportEndDate.value;
                const userId = userFilter.value;
                
                // Validate date range
                if (startDate && endDate && startDate > endDate) {
                    alert('End date cannot be before start date');
                    reportEndDate.value = startDate;
                    return;
                }

                let visibleCount = 0;
                
                bookingRows.forEach(row => {
                    const bookingDate = row.getAttribute('data-booking-date');
                    const homeownerId = row.getAttribute('data-homeowner-id');
                    const providerId = row.getAttribute('data-provider-id');
                    let showRow = true;
                    
                    // Date filtering
                    if (startDate && bookingDate < startDate) {
                        showRow = false;
                    }
                    if (endDate && bookingDate > endDate) {
                        showRow = false;
                    }
                    
                    // User filtering
                    if (userId && homeownerId !== userId && providerId !== userId) {
                        showRow = false;
                    }
                    
                    row.style.display = showRow ? '' : 'none';
                    if (showRow) visibleCount++;
                });
                
                // Update date range status
                const start = startDate ? new Date(startDate).toLocaleDateString() : 'Start';
                const end = endDate ? new Date(endDate).toLocaleDateString() : 'End';
                const userText = userId ? ` for selected user` : '';
                dateRangeStatus.textContent = `Showing ${visibleCount} bookings from ${start} to ${end}${userText}`;
            }

            // Add event listeners
            reportStartDate.addEventListener('change', filterBookings);
            reportEndDate.addEventListener('change', filterBookings);
            userFilter.addEventListener('change', filterBookings);

            // Initialize filtering
            filterBookings();

            // Toggle between report types
            document.getElementById('type').addEventListener('change', function() {
                const type = this.value;
                document.getElementById('reportTitle').textContent = `${type.charAt(0).toUpperCase() + type.slice(1)} Report`;
                
                // Hide all reports
                document.getElementById('bookingsReport').style.display = 'none';
                document.getElementById('earningsReport').style.display = 'none';
                document.getElementById('ratingsReport').style.display = 'none';
                document.getElementById('usersReport').style.display = 'none';
                document.getElementById('servicesReport').style.display = 'none';
                
                // Show selected report
                document.getElementById(`${type}Report`).style.display = 'block';
            });
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

        // Form submission handling
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
        });
    </script>
</body>
</html>