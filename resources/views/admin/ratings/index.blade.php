@extends('dashboard.app') <!-- Or your admin layout -->

@section('content')

<div class="main-content">
    
    <div class="row">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Ratings & Feedback Management</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge bg-primary">Total Ratings: {{ $totalRatings }}</span>
        </div>
    </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ratings & Feedback Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="badge bg-primary">Total Ratings: {{ $totalRatings }}</span>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Booking</th>
                                    <th>Rating</th>
                                    <th>Feedback</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ratings as $rating)
                                <tr>
                                    <td>{{ $rating->id }}</td>
                                    <td>#{{ $rating->booking_id }}</td>
                                    <td>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="badge bg-primary ms-2">{{ $rating->rating }}/5</span>
                                        </div>
                                    </td>
                                    <td>{{ Str::limit($rating->feedback, 50) }}</td>
                                    <td>
                                        @if($rating->booking->homeowner)
                                            <span class="badge bg-info">
                                                {{ $rating->booking->homeowner->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rating->booking->gardener)
                                            <span class="badge bg-success">
                                                {{ $rating->booking->gardener->name }} (Gardener)
                                            </span>
                                        @endif
                                        @if($rating->booking->serviceProvider)
                                            <span class="badge bg-warning text-dark">
                                                {{ $rating->booking->serviceProvider->name }} (Service Provider)
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $rating->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary view-feedback" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#feedbackModal"
                                                data-rating="{{ $rating->rating }}"
                                                data-feedback="{{ $rating->feedback }}"
                                                data-from="{{ $rating->booking->homeowner->name ?? 'N/A' }}"
                                                data-to="@if($rating->booking->gardener){{ $rating->booking->gardener->name }} (Gardener)@endif @if($rating->booking->serviceProvider){{ $rating->booking->serviceProvider->name }} (Service Provider)@endif"
                                                data-date="{{ $rating->created_at->format('M d, Y H:i') }}">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No ratings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $ratings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Rating Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>From:</strong> <span id="modalFrom"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>To:</strong> <span id="modalTo"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date:</strong> <span id="modalDate"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Rating:</strong> 
                        <span id="modalRatingStars"></span>
                        <span id="modalRatingValue" class="badge bg-primary"></span>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Feedback:</strong>
                    <div class="p-3 bg-light rounded mt-2" id="modalFeedback"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-feedback');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            const feedback = this.getAttribute('data-feedback');
            const from = this.getAttribute('data-from');
            const to = this.getAttribute('data-to');
            const date = this.getAttribute('data-date');
            
            document.getElementById('modalFrom').textContent = from;
            document.getElementById('modalTo').textContent = to;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalFeedback').textContent = feedback;
            document.getElementById('modalRatingValue').textContent = rating + '/5';
            
            // Generate stars
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    starsHtml += '<i class="fas fa-star text-warning"></i>';
                } else {
                    starsHtml += '<i class="far fa-star text-warning"></i>';
                }
            }
            document.getElementById('modalRatingStars').innerHTML = starsHtml;
        });
    });
});
</script>
@endsection