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
    <!-- Add Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-color: #2E7D32;
            --primary-light: #4CAF50;
            --primary-dark: #1B5E20;
            --secondary-color: #8BC34A;
            --accent-color: #FFC107;
            --light-color: #F1F8E9;
            --dark-color: #263238;
            --text-color: #37474F;
            --text-light: #607D8B;
            --card-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            --card-shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease-in-out;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: var(--text-color);
            line-height: 1.6;
        }

        .container-fluid {
            padding: 0 2rem;
        }

        /* Stat Cards */
        .stat-card {
            border-radius: 12px;
            border: none;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: var(--transition);
            background: white;
            overflow: hidden;
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary-light), var(--primary-dark));
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-card .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            font-family: 'Arial Rounded MT Bold', 'Arial', sans-serif;
        }

        .stat-card .stat-label {
            color: var(--text-light);
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Chart Cards */
        .chart-card {
            border-radius: 12px;
            border: none;
            box-shadow: var(--card-shadow);
            margin-top: 1.5rem;
            transition: var(--transition);
            background: white;
        }

        .chart-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .chart-card .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            color: var(--primary-dark);
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }

        .chart-card .card-header h5 {
            font-weight: 700;
        }

        .chart-container {
            position: relative;
            height: 400px;
            padding: 1rem;
        }

        /* Report Cards */
        .report-card {
            border-radius: 12px;
            border: none;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            background: white;
        }

        .report-card:hover {
            box-shadow: var(--card-shadow-hover);
        }

        .report-card .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            color: var(--primary-dark);
            padding: 1.25rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }

        .report-card .card-header h5 {
            font-weight: 700;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border-color: var(--text-light);
            color: var(--text-light);
            transition: var(--transition);
        }

        .btn-outline-secondary:hover {
            border-color: var(--primary-dark);
            color: var(--primary-dark);
            background-color: transparent;
        }

        /* Tables */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background-color: var(--light-color);
            color: var(--primary-dark);
            font-weight: 600;
            border-top: 1px solid #dee2e6;
            border-bottom: 2px solid var(--primary-light);
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background-color: rgba(139, 195, 74, 0.05);
        }

        .badge {
            padding: 0.5em 0.75em;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 0 1rem;
            }
            
            .stat-card .stat-value {
                font-size: 1.8rem;
            }
            
            .chart-container {
                height: 300px;
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
            .stat-card::before {
                display: none;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4 animate__animated animate__fadeIn">
        <!-- Quick Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4 animate__animated animate__fadeInLeft">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="stat-value">{{ $totalBookings }}</div>
                        <div class="stat-label">Total Bookings</div>
                        <div class="mt-2">
                            <i class="fas fa-calendar-check text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 animate__animated animate__fadeInUp">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="stat-value">₱{{ number_format($totalEarnings, 2) }}</div>
                        <div class="stat-label">Total Earnings</div>
                        <div class="mt-2">
                            <i class="fas fa-chart-line text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 animate__animated animate__fadeInRight">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="stat-value">{{ number_format($averageRating, 1) }}/5</div>
                        <div class="stat-label">Average Rating</div>
                        <div class="mt-2">
                            <i class="fas fa-star text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bookings Chart -->
        <div class="chart-card animate__animated animate__fadeIn">
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
        <div class="card no-print report-card animate__animated animate__fadeIn">
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
        
        <!-- Users Report (hidden by default) -->
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
        <div class="report-card animate__animated animate__fadeIn">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="reportTitle">Bookings Report</h5>
                <button class="btn btn-sm btn-outline-secondary no-print" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button>
            </div>
            <div class="card-body">
                <!-- Bookings Report -->
                <div id="bookingsReport" class="fade-in">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Initialize and render the chart with animation
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
            
            // Create the chart with animation
            const bookingsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: 'Completed Bookings',
                            data: [{{ implode(',', $completedBookingsByMonth) }}],
                            backgroundColor: '#4CAF50', // Green
                            borderColor: '#2E7D32',
                            borderWidth: 1
                        },
                        {
                            label: 'Accepted Bookings',
                            data: [{{ implode(',', $acceptedBookingsByMonth) }}],
                            backgroundColor: '#2196F3', // Blue
                            borderColor: '#0D47A1',
                            borderWidth: 1
                        },
                        {
                            label: 'Pending Bookings',
                            data: [{{ implode(',', $pendingBookingsByMonth) }}],
                            backgroundColor: '#FFC107', // Yellow
                            borderColor: '#FFA000',
                            borderWidth: 1
                        },
                        {
                            label: 'Declined Bookings',
                            data: [{{ implode(',', $declinedBookingsByMonth) }}],
                            backgroundColor: '#F44336', // Red
                            borderColor: '#C62828',
                            borderWidth: 1
                        },
                        {
                            label: 'Total Earnings (₱)',
                            data: [{{ implode(',', $earningsByMonth) }}],
                            backgroundColor: '#9C27B0', // Purple
                            borderColor: '#6A1B9A',
                            borderWidth: 3,
                            type: 'line',
                            yAxisID: 'y1',
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
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Bookings'
                            },
                            stacked: false
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Earnings (₱)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
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
                            intersect: false,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            }
                        },
                        legend: {
                            position: 'top',
                            labels: {
                                boxWidth: 12,
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
        });

        // Toggle between report types with animation
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const reportTitle = document.getElementById('reportTitle');
            const currentReport = document.querySelector('.card-body > div[style="display: block;"]');
            const newReport = document.getElementById(`${type}Report`);
            
            // Add fade out animation to current report
            if (currentReport) {
                currentReport.style.opacity = '0';
                currentReport.style.transition = 'opacity 0.3s ease';
                
                setTimeout(() => {
                    currentReport.style.display = 'none';
                    reportTitle.textContent = `${type.charAt(0).toUpperCase() + type.slice(1)} Report`;
                    newReport.style.display = 'block';
                    newReport.style.opacity = '0';
                    
                    // Trigger reflow to enable animation
                    void newReport.offsetWidth;
                    
                    newReport.style.opacity = '1';
                    newReport.style.transition = 'opacity 0.3s ease';
                    newReport.classList.add('fade-in');
                }, 300);
            } else {
                reportTitle.textContent = `${type.charAt(0).toUpperCase() + type.slice(1)} Report`;
                newReport.style.display = 'block';
                newReport.classList.add('fade-in');
            }
        });

        // Generate PDF with enhanced options
        function generatePDF() {
            const element = document.getElementById('reportTitle').parentElement.parentElement;
            const opt = {
                margin: [10, 10, 10, 10],
                filename: `${document.getElementById('type').value}_report_${new Date().toISOString().slice(0,10)}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2,
                    logging: true,
                    useCORS: true,
                    letterRendering: true
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait',
                    hotfixes: ["px_scaling"]
                },
                pagebreak: { 
                    mode: ['avoid-all', 'css', 'legacy'] 
                }
            };

            // Show loading indicator
            const loadingIndicator = document.createElement('div');
            loadingIndicator.innerHTML = `
                <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
                    <div style="background: white; padding: 2rem; border-radius: 8px; text-align: center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-3">Generating PDF...</h5>
                    </div>
                </div>
            `;
            document.body.appendChild(loadingIndicator);

            html2pdf().from(element).set(opt).save().then(() => {
                // Remove loading indicator when done
                document.body.removeChild(loadingIndicator);
            });
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
            e.preventDefault();
        });

        // Add hover effect to table rows
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
                this.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
            });
            row.addEventListener('mouseleave', function() {
                this.style.transform = '';
                this.style.boxShadow = '';
            });
        });
    </script>
</body>
</html>