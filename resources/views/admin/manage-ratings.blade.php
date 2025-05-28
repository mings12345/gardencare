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

        .filter-card {
            background: linear-gradient(135deg, var(--lighter-green) 0%, #f8f9fa 100%);
            border-left: 4px solid var(--primary-green);
        }

        .quick-filter-badge {
            transition: all 0.2s ease;
        }

        .quick-filter-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-green);
            font-size: 0.9rem;
        }

        .btn-success {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .btn-success:hover {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
        }

        .rating-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
        }

        .rating-high {
            background-color: #d4edda;
            color: #155724;
        }

        .rating-medium {
            background-color: #fff3cd;
            color: #856404;
        }

        .rating-low {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Filters Card -->
                <div class="card filter-card mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-filter me-2"></i>Filter Ratings
                        </h5>
                        
                        <form method="GET" action="{{ route('admin.ratings') }}" class="row g-3" id="filterForm">
                            <!-- Rating Filter -->
                            <div class="col-md-2">
                                <label for="rating_filter" class="form-label">Rating</label>
                                <select class="form-select" id="rating_filter" name="rating">
                                    <option value="">All Ratings</option>
                                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Stars)</option>
                                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4+ Stars)</option>
                                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3+ Stars)</option>
                                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 Stars)</option>
                                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ (1 Star)</option>
                                    <option value="low" {{ request('rating') == 'low' ? 'selected' : '' }}>⚠️ Below 3 Stars</option>
                                </select>
                            </div>

                            <!-- Date Range Filter -->
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>

                            <!-- Service Provider Filter -->
                            <div class="col-md-2">
                                <label for="provider_filter" class="form-label">Service Provider</label>
                                <select class="form-select" id="provider_filter" name="provider_id">
                                    <option value="">All Providers</option>
                                    @if(isset($serviceProviders))
                                        @foreach($serviceProviders as $provider)
                                            <option value="{{ $provider->id }}" {{ request('provider_id') == $provider->id ? 'selected' : '' }}>
                                                {{ $provider->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Search Feedback -->
                            <div class="col-md-2">
                                <label for="search_feedback" class="form-label">Search Feedback</label>
                                <input type="text" class="form-control" id="search_feedback" name="search" 
                                       placeholder="Search feedback..." value="{{ request('search') }}">
                            </div>

                            <!-- Filter Actions -->
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('admin.ratings') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </form>

                        <!-- Quick Filter Badges -->
                        <div class="mt-3 pt-3 border-top">
                            <span class="text-muted me-2 fw-bold">Quick filters:</span>
                            <a href="?rating=low" class="badge bg-danger text-decoration-none me-2 quick-filter-badge">
                                <i class="fas fa-exclamation-triangle me-1"></i>Low Ratings
                            </a>
                            <a href="?rating=5" class="badge bg-success text-decoration-none me-2 quick-filter-badge">
                                <i class="fas fa-star me-1"></i>5-Star Reviews
                            </a>
                            <a href="?date_from={{ now()->subDays(7)->format('Y-m-d') }}" class="badge bg-info text-decoration-none me-2 quick-filter-badge">
                                <i class="fas fa-calendar-week me-1"></i>Last 7 Days
                            </a>
                            <a href="?date_from={{ now()->subDays(30)->format('Y-m-d') }}" class="badge bg-warning text-decoration-none me-2 quick-filter-badge">
                                <i class="fas fa-calendar-alt me-1"></i>Last Month
                            </a>
                            @if(request()->anyFilled(['rating', 'date_from', 'date_to', 'provider_id', 'search']))
                                <a href="{{ route('admin.ratings') }}" class="badge bg-secondary text-decoration-none quick-filter-badge">
                                    <i class="fas fa-times me-1"></i>Clear All Filters
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Ratings Table Card -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            Manage Ratings 
                            <span class="badge badge-green bg-light text-dark ms-2">{{ $totalRatings }} total</span>
                            @if(request()->anyFilled(['rating', 'date_from', 'date_to', 'provider_id', 'search']))
                                <span class="badge bg-primary ms-1">{{ $ratings->total() }} filtered</span>
                            @endif
                        </h3>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-success btn-sm" onclick="exportRatings()">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshData()">
                                <i class="fas fa-sync-alt me-1"></i>Refresh
                            </button>
                        </div>
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
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ratings as $rating)
                                    <tr>
                                        <td class="fw-bold">#{{ $rating->id }}</td>
                                        <td>
                                            <a href="#" class="text-decoration-none">
                                                <span class="badge bg-light text-dark">{{ $rating->booking_id }}</span>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="rating-display">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $rating->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 fw-bold">{{ number_format($rating->rating, 1) }}</span>
                                                @if($rating->rating >= 4)
                                                    <span class="badge rating-high rating-badge ms-2">High</span>
                                                @elseif($rating->rating >= 3)
                                                    <span class="badge rating-medium rating-badge ms-2">Average</span>
                                                @else
                                                    <span class="badge rating-low rating-badge ms-2">Low</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($rating->feedback)
                                                <span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $rating->feedback }}">
                                                    {{ Str::limit($rating->feedback, 50) }}
                                                </span>
                                                @if(strlen($rating->feedback) > 50)
                                                    <button class="btn btn-sm btn-outline-secondary ms-1" onclick="showFullFeedback('{{ addslashes($rating->feedback) }}')">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-muted fst-italic">No feedback provided</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rating->booking->homeowner)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                        {{ substr($rating->booking->homeowner->name, 0, 1) }}
                                                    </div>
                                                    {{ $rating->booking->homeowner->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rating->booking->gardener)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                        {{ substr($rating->booking->gardener->name, 0, 1) }}
                                                    </div>
                                                    {{ $rating->booking->gardener->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rating->booking->serviceProvider)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-info text-white me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                        {{ substr($rating->booking->serviceProvider->name, 0, 1) }}
                                                    </div>
                                                    {{ $rating->booking->serviceProvider->name }}
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted">{{ $rating->created_at->format('M d, Y') }}</small><br>
                                                <small class="text-muted">{{ $rating->created_at->format('H:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewRatingDetails({{ $rating->id }})" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRating({{ $rating->id }})" title="Delete Rating">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="far fa-star"></i>
                                                <h4>No ratings found</h4>
                                                <p class="text-muted">
                                                    @if(request()->anyFilled(['rating', 'date_from', 'date_to', 'provider_id', 'search']))
                                                        No ratings match your current filters. Try adjusting your search criteria.
                                                    @else
                                                        When ratings are submitted, they will appear here.
                                                    @endif
                                                </p>
                                                @if(request()->anyFilled(['rating', 'date_from', 'date_to', 'provider_id', 'search']))
                                                    <a href="{{ route('admin.ratings') }}" class="btn btn-outline-secondary">
                                                        <i class="fas fa-times me-1"></i>Clear Filters
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($ratings->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $ratings->firstItem() }} to {{ $ratings->lastItem() }} of {{ $ratings->total() }} results
                            </div>
                            <div>
                                {{ $ratings->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div class="modal fade" id="feedbackModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Full Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="fullFeedbackText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Details Modal -->
    <div class="modal fade" id="ratingDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rating Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="ratingDetailsContent">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Show full feedback in modal
        function showFullFeedback(feedback) {
            document.getElementById('fullFeedbackText').textContent = feedback;
            new bootstrap.Modal(document.getElementById('feedbackModal')).show();
        }

        // View rating details
        function viewRatingDetails(ratingId) {
            // You can implement AJAX call to fetch rating details
            fetch(`/admin/ratings/${ratingId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('ratingDetailsContent').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Booking Information</h6>
                                <p><strong>Booking ID:</strong> ${data.booking_id}</p>
                                <p><strong>Service Date:</strong> ${data.service_date}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Rating Information</h6>
                                <p><strong>Rating:</strong> ${data.rating}/5</p>
                                <p><strong>Submitted:</strong> ${data.created_at}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <h6>Feedback</h6>
                            <p class="border p-3 rounded bg-light">${data.feedback || 'No feedback provided'}</p>
                        </div>
                    `;
                    new bootstrap.Modal(document.getElementById('ratingDetailsModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading rating details');
                });
        }

        // Delete rating
        function deleteRating(ratingId) {
            if (confirm('Are you sure you want to delete this rating? This action cannot be undone.')) {
                fetch(`/admin/ratings/${ratingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error deleting rating');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting rating');
                });
            }
        }

        // Export ratings
        function exportRatings() {
            const params = new URLSearchParams(window.location.search);
            params.append('export', 'csv');
            window.location.href = `{{ route('admin.ratings') }}?${params.toString()}`;
        }

        // Refresh data
        function refreshData() {
            location.reload();
        }

        // Auto-submit form when date inputs change
        document.getElementById('date_from').addEventListener('change', function() {
            if (this.value && document.getElementById('date_to').value) {
                document.getElementById('filterForm').submit();
            }
        });

        document.getElementById('date_to').addEventListener('change', function() {
            if (this.value && document.getElementById('date_from').value) {
                document.getElementById('filterForm').submit();
            }
        });

        // Auto-submit form when select inputs change
        document.getElementById('rating_filter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('provider_filter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
</body>
</html>