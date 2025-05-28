<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ratings</title>
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

        /* Star ratings styling */
        .star-rating {
            color: #ffc107; /* Bootstrap yellow */
            font-size: 1.2rem;
        }

        .rating-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #fff;
            transition: transform 0.2s;
        }

        .rating-card:hover {
            transform: scale(1.02);
        }

        /* Gardening-themed background */
        body {
            background-image: url('https://www.transparenttextures.com/patterns/leaves.png'); /* Subtle leaf pattern */
            background-repeat: repeat;
        }

        /* Status badges */
        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-completed {
            background-color: #28a745;
            color: #fff;
        }

        .badge-cancelled {
            background-color: #dc3545;
            color: #fff;
        }

        /* Pagination styling */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .page-item.active .page-link {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .page-link {
            color: #4CAF50;
        }

        .page-link:hover {
            color: #45a049;
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
        <a href="{{ route('admin.manageRatings') }}" class="active">
            <i class="fas fa-comments"></i> Manage Ratings
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-success">
            <i class="fas fa-chevron-left me-2"></i> Dashboard
        </a>
        <h1 class="mb-0">Manage Ratings & Feedback</h1>
    </div>
    <div class="badge bg-primary fs-5">
        Total Ratings: {{ $totalRatings }}
    </div>
</div>

            <!-- Filters Row -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.manageRatings') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="rating" class="form-label">Filter by Rating</label>
                                    <select name="rating" id="rating" class="form-select">
                                        <option value="">All Ratings</option>
                                        <option value="5" {{ request()->rating == 5 ? 'selected' : '' }}>5 Stars</option>
                                        <option value="4" {{ request()->rating == 4 ? 'selected' : '' }}>4 Stars</option>
                                        <option value="3" {{ request()->rating == 3 ? 'selected' : '' }}>3 Stars</option>
                                        <option value="2" {{ request()->rating == 2 ? 'selected' : '' }}>2 Stars</option>
                                        <option value="1" {{ request()->rating == 1 ? 'selected' : '' }}>1 Star</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="search" class="form-label">Search Comments</label>
                                    <input type="text" class="form-control" id="search" name="search" value="{{ request()->search }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="sort" class="form-label">Sort By</label>
                                    <select name="sort" id="sort" class="form-select">
                                        <option value="latest" {{ request()->sort == 'latest' ? 'selected' : '' }}>Latest First</option>
                                        <option value="oldest" {{ request()->sort == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                        <option value="highest" {{ request()->sort == 'highest' ? 'selected' : '' }}>Highest Rating</option>
                                        <option value="lowest" {{ request()->sort == 'lowest' ? 'selected' : '' }}>Lowest Rating</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100">Apply Filters</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ratings List -->
            <div class="row">
                <div class="col-md-12">
                    @if($ratings->count() > 0)
                        @foreach($ratings as $rating)
                            <div class="card rating-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="card-title">
                                                    Booking #{{ $rating->booking->id }}
                                                    <span class="badge {{ $rating->booking->status == 'pending' ? 'badge-pending' : ($rating->booking->status == 'completed' ? 'badge-completed' : 'badge-cancelled') }}">
                                                        {{ ucfirst($rating->booking->status) }}
                                                    </span>
                                                </h5>
                                                <span class="star-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $rating->rating)
                                                            <i class="fas fa-star"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </span>
                                            </div>
                                            <p class="text-muted">
                                                @if(isset($rating->booking->homeowner))
                                                    From: {{ $rating->booking->homeowner->name ?? 'Unknown Homeowner' }}
                                                @endif
                                                @if(isset($rating->booking->gardener)) 
                                                    | To: {{ $rating->booking->gardener->name ?? 'Unknown Gardener' }}
                                                @endif
                                            </p>
                                            <div class="mt-3">
                                                <strong>Comment:</strong>
                                                <p>{{ $rating->comment ?? 'No comment provided' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <p class="text-muted">{{ $rating->created_at->format('M d, Y H:i') }}</p>
                                            <div class="mt-4">
                                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteRatingModal{{ $rating->id }}">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                                <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewBookingModal{{ $rating->booking->id }}">
                                                    <i class="fas fa-eye"></i> View Booking
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Rating Modal -->
                            <div class="modal fade" id="deleteRatingModal{{ $rating->id }}" tabindex="-1" aria-labelledby="deleteRatingModalLabel{{ $rating->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteRatingModalLabel{{ $rating->id }}">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this rating? This action cannot be undone.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('admin.deleteRating', $rating->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete Rating</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- View Booking Modal -->
                            <div class="modal fade" id="viewBookingModal{{ $rating->booking->id }}" tabindex="-1" aria-labelledby="viewBookingModalLabel{{ $rating->booking->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewBookingModalLabel{{ $rating->booking->id }}">Booking Details #{{ $rating->booking->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Service:</strong> {{ $rating->booking->service->name ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Status:</strong> 
                                                    <span class="badge {{ $rating->booking->status == 'pending' ? 'badge-pending' : ($rating->booking->status == 'completed' ? 'badge-completed' : 'badge-cancelled') }}">
                                                        {{ ucfirst($rating->booking->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Homeowner:</strong> {{ $rating->booking->homeowner->name ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Gardener:</strong> {{ $rating->booking->gardener->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Scheduled Date:</strong> {{ $rating->booking->scheduled_date ?? 'N/A' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Price:</strong> ${{ number_format($rating->booking->price ?? 0, 2) }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <strong>Description:</strong>
                                                    <p>{{ $rating->booking->description ?? 'No description provided' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <a href="{{ route('admin.manageBookings') }}" class="btn btn-primary">View All Bookings</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $ratings->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> No ratings found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>