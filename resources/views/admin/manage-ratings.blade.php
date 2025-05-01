<!-- resources/views/admin/manage-ratings.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ratings - Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #81c784;
            --lighter-green: #e8f5e9;
            --accent-green: #4caf50;
            --dark-green: #1b5e20;
        }
        
        body {
            background-color: #f5f9f5;
            padding-top: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            box-shadow: 0 0.25rem 1rem rgba(46, 125, 50, 0.15);
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .card-header {
            font-weight: 600;
            background-color: var(--primary-green);
            color: white;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }
        
        .card-title {
            margin: 0;
            font-size: 1.4rem;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
        }
        
        .rating-display i {
            font-size: 1rem;
            color: #ffc107;
        }
        
        .table-responsive {
            overflow-x: auto;
            border-radius: 8px;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead {
            background-color: var(--lighter-green);
            color: var(--dark-green);
        }
        
        .table th {
            border-bottom: 2px solid var(--light-green);
            padding: 12px 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-top: 1px solid #e0e0e0;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(129, 199, 132, 0.1);
        }
        
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        
        .page-item.active .page-link {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .page-link {
            color: var(--primary-green);
        }
        
        .card-footer {
            background-color: white;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .badge-green {
            background-color: var(--light-green);
            color: var(--dark-green);
        }
        
        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--light-green);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Manage Ratings <span class="badge badge-green bg-light text-dark ms-2">{{ $totalRatings }} total</span></h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Booking ID</th>
                                        <th>Rating</th>
                                        <th>Feedback</th>
                                        <th>Homeowner</th>
                                        <th>Gardener</th>
                                        <th>Service Provider</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ratings as $rating)
                                    <tr>
                                        <td>{{ $rating->id }}</td>
                                        <td>{{ $rating->booking_id }}</td>
                                        <td>
                                            <div class="rating-display">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $rating->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2">{{ number_format($rating->rating, 1) }}</span>
                                            </div>
                                        </td>
                                        <td>{{ Str::limit($rating->feedback, 50) }}</td>
                                        <td>
                                            @if ($rating->booking->homeowner)
                                                {{ $rating->booking->homeowner->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rating->booking->gardener)
                                                {{ $rating->booking->gardener->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rating->booking->serviceProvider)
                                                {{ $rating->booking->serviceProvider->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $rating->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="far fa-star"></i>
                                                <h4>No ratings found</h4>
                                                <p class="text-muted">When ratings are submitted, they will appear here.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $ratings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>