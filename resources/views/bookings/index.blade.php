<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings Management | GreenThumb</title>
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
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .page-header h1 {
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
            font-size: 28px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #FFF3CD;
            color: #856404;
        }

        .status-confirmed {
            background-color: #D4EDDA;
            color: #155724;
        }

        .status-completed {
            background-color: #D1ECF1;
            color: #0C5460;
        }

        .status-cancelled {
            background-color: #F8D7DA;
            color: #721C24;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            vertical-align: middle;
            padding: 15px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 12px 15px;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(139, 195, 74, 0.1);
        }

        .badge-service {
            background-color: var(--light-color);
            color: var(--dark-color);
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }

        .badge-gardening {
            background-color: #4CAF50;
            color: white;
        }

        .badge-landscaping {
            background-color: #2196F3;
            color: white;
        }

        .payment-details {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
        }

        .payment-details strong {
            display: inline-block;
            width: 80px;
        }

        .payment-status-paid {
            color: #28a745;
            font-weight: bold;
        }

        .payment-status-pending {
            color: #ffc107;
            font-weight: bold;
        }

        .payment-status-failed {
            color: #dc3545;
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .empty-state i {
            font-size: 50px;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .empty-state p {
            color: var(--text-light);
            max-width: 500px;
            margin: 0 auto 25px;
        }

        .filter-section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        .filter-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .form-select, .form-control {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }

        .form-select:focus, .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(139, 195, 74, 0.25);
        }

        @media (max-width: 992px) {
            .table-responsive {
                overflow-x: auto;
            }
            
            .table thead {
                display: none;
            }
            
            .table tbody tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid #eee;
                border-radius: 8px;
                box-shadow: var(--shadow);
            }
            
            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--primary-color);
                margin-right: 15px;
                flex: 0 0 120px;
            }
            
            .table tbody td:last-child {
                border-bottom: none;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1><i class="fas fa-calendar-alt me-2"></i> Bookings Management</h1>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h5 class="filter-title"><i class="fas fa-filter me-2"></i>Filter Bookings</h5>
            <div class="row">
                <div class="col-md-6">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="accepted">Accepted</option>
                        <option value="completed">Completed</option>
                        <option value="declined">Declined</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="typeFilter" class="form-label">Service Type</label>
                    <select class="form-select" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="gardening">Gardening</option>
                        <option value="landscaping">Landscaping</option>
                    </select>
                </div>
            </div>
        </div>

        @if($bookings->isEmpty())
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>No Bookings Found</h3>
                <p>There are currently no bookings in the system.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Type</th>
                            <th>Client</th>
                            <th>Professional</th>
                            <th>Date & Time</th>
                            <th>Services</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead> 
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td data-label="ID">#{{ $booking->id }}</td>
                                <td data-label="Type">
                                @if(strtolower($booking->type) == 'gardening')
                                        <span class="badge badge-gardening">Gardening</span>
                                    @else
                                        <span class="badge badge-landscaping">Landscaping</span>
                                    @endif
                                </td>
                                <td data-label="Homeowner">
                                    <strong>{{ optional($booking->homeowner)->name ?? 'N/A' }}</strong>
                                    <div class="text-muted small">{{ $booking->address }}</div>
                                </td>
                                <td data-label="Professional">
                                    @if($booking->gardener)
                                        <span class="badge bg-success">Gardener</span>
                                        {{ $booking->gardener->name }}
                                    @elseif($booking->serviceProvider)
                                        <span class="badge bg-info">Service Provider</span>
                                        {{ $booking->serviceProvider->name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td data-label="Date & Time">
                                    <strong>{{ \Carbon\Carbon::parse($booking->date)->format('M d, Y') }}</strong>
                                    <div class="text-muted small">{{ $booking->time }}</div>
                                </td>
                                <td data-label="Services">
                                    @foreach($booking->services as $service)
                                        <span class="badge badge-service">{{ $service->name }}</span>
                                    @endforeach
                                </td>
                                <td data-label="Payment">
                                    @if($booking->payments->isNotEmpty())
                                        @foreach($booking->payments as $payment)
                                            <div class="payment-details mb-2">
                                                <div>
                                                    <strong>Amount:</strong> 
                                                    ₱{{ number_format($payment->amount_paid, 2) }}
                                                </div>
                                                <div>
                                                    <strong>Status:</strong> 
                                                    <span class="payment-status-{{ $payment->payment_status }}">
                                                        {{ ucfirst($payment->payment_status) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>Date:</strong> 
                                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}
                                                </div>
                                                @if($payment->admin_fee)
                                                <div>
                                                    <strong>Admin Fee:</strong> 
                                                    ₱{{ number_format($payment->admin_fee, 2) }}
                                                </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No payments</span>
                                    @endif
                                </td>
                                <td data-label="Total">
                                ₱{{ number_format($booking->total_price, 2) }}
                                </td>
                                <td data-label="Status">
                                    <span class="status-badge status-{{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Status filter
            document.getElementById('statusFilter').addEventListener('change', function() {
                const status = this.value;
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    if (!status || row.querySelector('.status-badge').classList.contains(`status-${status}`)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // Type filter
            document.getElementById('typeFilter').addEventListener('change', function() {
                const type = this.value;
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    if (!type || row.querySelector('td:nth-child(2)').textContent.toLowerCase().includes(type)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>