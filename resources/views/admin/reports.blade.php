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