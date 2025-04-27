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
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
        }
        .card-header {
            font-weight: 600;
            background-color: rgba(0, 0, 0, 0.03);
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
        }
        .pagination {
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Manage Ratings (Total: {{ $totalRatings }})</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
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
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rating->booking->gardener)
                                                {{ $rating->booking->gardener->name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rating->booking->serviceProvider)
                                                {{ $rating->booking->serviceProvider->name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $rating->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No ratings found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-center">
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