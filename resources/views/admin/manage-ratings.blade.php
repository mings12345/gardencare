<!-- resources/views/admin/manage-ratings.blade.php -->
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Ratings (Total: {{ $totalRatings }})</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
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
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-1">{{ number_format($rating->rating, 1) }}</span>
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
                                    <td colspan="8" class="text-center">No ratings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $ratings->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .rating-display {
        display: flex;
        align-items: center;
    }
    .rating-display i {
        font-size: 1rem;
    }
</style>
@endsection