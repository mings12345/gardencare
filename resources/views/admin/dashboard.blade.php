<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Previous styles remain the same */
        
        /* New styles for feedback management */
        .feedback-table {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        
        .feedback-table th {
            background-color: #4CAF50;
            color: white;
        }
        
        .feedback-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .rating-stars {
            color: #FFD700; /* Gold color for stars */
        }
        
        .feedback-actions a {
            margin-right: 10px;
            color: #4CAF50;
        }
        
        .badge {
            font-size: 0.8em;
            padding: 5px 8px;
        }
        
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar remains the same -->
    <div class="sidebar">
        <!-- ... existing sidebar content ... -->
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Admin Dashboard</h1>

        <!-- Card Grid -->
        <div class="card-grid">
            <!-- ... existing cards ... -->
        </div>

        <!-- Feedback Management Section -->
        <div class="feedback-section mt-5">
            <h2 class="mb-4">Feedback Management</h2>
            
            <div class="feedback-table">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Booking ID</th>
                            <th>Rating</th>
                            <th>Feedback</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feedbacks as $feedback)
                        <tr>
                            <td>{{ $feedback->id }}</td>
                            <td>{{ $feedback->booking_id }}</td>
                            <td>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $feedback->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span class="badge bg-primary">{{ number_format($feedback->rating, 1) }}</span>
                                </div>
                            </td>
                            <td>{{ Str::limit($feedback->feedback, 50) }}</td>
                            <td>
                                @if($feedback->booking->homeowner)
                                    <span class="badge bg-info">Homeowner: {{ $feedback->booking->homeowner->name }}</span>
                                @endif
                            </td>
                            <td>
                                @if($feedback->booking->gardener)
                                    <span class="badge bg-success">Gardener: {{ $feedback->booking->gardener->name }}</span>
                                @endif
                                @if($feedback->booking->serviceProvider)
                                    <span class="badge bg-warning text-dark">Service Provider: {{ $feedback->booking->serviceProvider->name }}</span>
                                @endif
                            </td>
                            <td>{{ $feedback->created_at->format('M d, Y') }}</td>
                            <td class="feedback-actions">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $feedback->id }}">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="#" onclick="confirmDelete({{ $feedback->id }})">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>

                        <!-- Feedback Modal -->
                        <div class="modal fade" id="feedbackModal{{ $feedback->id }}" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="feedbackModalLabel">Feedback Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <strong>Rating:</strong>
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $feedback->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <span class="badge bg-primary">{{ number_format($feedback->rating, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <strong>Booking ID:</strong> {{ $feedback->booking_id }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>From:</strong> 
                                            @if($feedback->booking->homeowner)
                                                {{ $feedback->booking->homeowner->name }} (Homeowner)
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <strong>To:</strong> 
                                            @if($feedback->booking->gardener)
                                                {{ $feedback->booking->gardener->name }} (Gardener)<br>
                                            @endif
                                            @if($feedback->booking->serviceProvider)
                                                {{ $feedback->booking->serviceProvider->name }} (Service Provider)
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <strong>Date:</strong> {{ $feedback->created_at->format('M d, Y H:i') }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Feedback:</strong>
                                            <div class="p-3 bg-light rounded">
                                                {{ $feedback->feedback }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination">
                    {{ $feedbacks->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function confirmDelete(feedbackId) {
            if (confirm('Are you sure you want to delete this feedback?')) {
                // You'll need to implement this endpoint in your controller
                fetch(`/admin/feedback/${feedbackId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to delete feedback');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting feedback');
                });
            }
        }
    </script>
</body>
</html>