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
    <!-- Datepicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        
        .filter-section {
            background-color: var(--lighter-green);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .filter-row {
            align-items: flex-end;
        }
        
        .filter-group {
            margin-bottom: 1rem;
        }
        
        .filter-label {
            font-weight: 500;
            color: var(--dark-green);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .star-filter .form-check {
            display: inline-block;
            margin-right: 0.5rem;
        }
        
        .star-filter .form-check-input {
            display: none;
        }
        
        .star-filter .form-check-label {
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .star-filter .form-check-input:checked + .form-check-label {
            background-color: var(--accent-green);
            color: white;
        }
        
        .reset-filters {
            margin-left: auto;
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
                    
                    <!-- Filter Section -->
                    <div class="card-body">
                        <form action="{{ route('admin.ratings.index') }}" method="GET" id="filter-form">
                            <div class="filter-section">
                                <div class="row filter-row">
                                    <div class="col-md-3 filter-group">
                                        <label class="filter-label">Rating Stars</label>
                                        <div class="star-filter">
                                            @for($i = 1; $i <= 5; $i++)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="stars[]" id="star-{{ $i }}" value="{{ $i }}" 
                                                        {{ in_array($i, request('stars', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="star-{{ $i }}">
                                                        @for($j = 1; $j <= $i; $j++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                    </label>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 filter-group">
                                        <label class="filter-label">User Type</label>
                                        <select class="form-select" name="user_type">
                                            <option value="">All Users</option>
                                            <option value="homeowner" {{ request('user_type') == 'homeowner' ? 'selected' : '' }}>Homeowners</option>
                                            <option value="gardener" {{ request('user_type') == 'gardener' ? 'selected' : '' }}>Gardeners</option>
                                            <option value="service_provider" {{ request('user_type') == 'service_provider' ? 'selected' : '' }}>Service Providers</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3 filter-group">
                                        <label class="filter-label">Date Range</label>
                                        <input type="text" class="form-control date-range" name="date_range" placeholder="Select date range"
                                            value="{{ request('date_range') }}" data-toggle="date-range">
                                    </div>
                                    
                                    <div class="col-md-3 filter-group">
                                        <label class="filter-label">Search Feedback</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" placeholder="Search feedback..." 
                                                value="{{ request('search') }}">
                                            <button class="btn btn-outline-secondary" type="button" id="clear-search">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 d-flex">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-filter me-1"></i> Apply Filters
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary reset-filters">
                                            <i class="fas fa-sync-alt me-1"></i> Reset Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
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
                        {{ $ratings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Flatpickr for date range -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize date range picker
            flatpickr('.date-range', {
                mode: 'range',
                dateFormat: 'Y-m-d',
                allowInput: true
            });
            
            // Clear search input
            document.getElementById('clear-search').addEventListener('click', function() {
                document.querySelector('input[name="search"]').value = '';
                document.getElementById('filter-form').submit();
            });
            
            // Reset all filters
            document.querySelector('.reset-filters').addEventListener('click', function() {
                // Clear all form inputs
                const form = document.getElementById('filter-form');
                form.reset();
                
                // Remove query parameters from URL
                const url = new URL(window.location.href);
                url.search = '';
                window.location.href = url.toString();
            });
            
            // Submit form when star rating is clicked
            document.querySelectorAll('.star-filter .form-check-input').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    document.getElementById('filter-form').submit();
                });
            });
        });
    </script>
</body>
</html>