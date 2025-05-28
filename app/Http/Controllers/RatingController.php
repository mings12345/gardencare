<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitRatingRequest;
use App\Models\Rating;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class RatingController extends Controller
{
    public function submitRating(SubmitRatingRequest $request, $bookingId): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
            if ($booking->rating()->exists()) {
                return response()->json([
                    'message' => 'This booking already has a rating',
                ], 422);
            }

            $rating = $booking->rating()->create([
                'rating' => $request->rating,
                'feedback' => $request->feedback,
            ]);

            return response()->json([
                'message' => 'Rating submitted successfully',
                'data' => $rating,
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('Rating submission error: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while submitting the rating',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            $ratings = Rating::with([
                    'booking.gardener',
                    'booking.homeowner',
                    'booking.serviceProvider'
                ])
                ->when($user->user_type === 'service_provider', function($q) use ($user) {
                    $q->whereHas('booking', function($q) use ($user) {
                        $q->where('serviceprovider_id', $user->id);
                    });
                })
                ->when($user->user_type === 'gardener', function($q) use ($user) {
                    $q->whereHas('booking', function($q) use ($user) {
                        $q->where('gardener_id', $user->id);
                    });
                })
                ->when($user->user_type === 'homeowner', function($q) use ($user) {
                    $q->whereHas('booking', function($q) use ($user) {
                        $q->where('homeowner_id', $user->id);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($rating) {
                    return [
                        'id' => $rating->id,
                        'booking_id' => $rating->booking_id,
                        'rating' => $rating->rating,
                        'feedback' => $rating->feedback,
                        'created_at' => $rating->created_at,
                        'gardener' => $rating->booking->gardener ? [
                            'id' => $rating->booking->gardener->id,
                            'name' => $rating->booking->gardener->name,
                        ] : null,
                        'homeowner' => $rating->booking->homeowner ? [
                            'id' => $rating->booking->homeowner->id,
                            'name' => $rating->booking->homeowner->name,
                        ] : null,
                        'service_provider' => $rating->booking->serviceProvider ? [
                            'id' => $rating->booking->serviceProvider->id,
                            'name' => $rating->booking->serviceProvider->name,
                        ] : null,
                    ];
                });

            return response()->json([
                'ratings' => $ratings,
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to fetch ratings: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Admin Methods
    public function adminIndex(Request $request)
    {
        $query = Rating::with(['booking.homeowner', 'booking.gardener', 'booking.serviceProvider']);

        // Apply filters
        $this->applyFilters($query, $request);

        // Get filtered ratings with pagination
        $ratings = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get total ratings count (unfiltered)
        $totalRatings = Rating::count();

        // Get service providers for filter dropdown
        $serviceProviders = User::where('user_type', 'service_provider')
            ->orWhere('user_type', 'gardener')
            ->orderBy('name')
            ->get();

        // Handle export request
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportToCsv($query);
        }

        return view('admin.manage-ratings', compact('ratings', 'totalRatings', 'serviceProviders'));
    }

    private function applyFilters($query, Request $request)
    {
        // Filter by rating
        if ($request->filled('rating')) {
            $ratingFilter = $request->rating;
            
            switch ($ratingFilter) {
                case '5':
                    $query->where('rating', 5);
                    break;
                case '4':
                    $query->where('rating', '>=', 4);
                    break;
                case '3':
                    $query->where('rating', '>=', 3);
                    break;
                case '2':
                    $query->where('rating', 2);
                    break;
                case '1':
                    $query->where('rating', 1);
                    break;
                case 'low':
                    $query->where('rating', '<', 3);
                    break;
                default:
                    if (is_numeric($ratingFilter)) {
                        $query->where('rating', $ratingFilter);
                    }
                    break;
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by service provider
        if ($request->filled('provider_id')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('gardener_id', $request->provider_id)
                  ->orWhere('serviceprovider_id', $request->provider_id);
            });
        }

        // Search in feedback
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('feedback', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('booking.homeowner', function ($subQ) use ($searchTerm) {
                      $subQ->where('name', 'LIKE', "%{$searchTerm}%");
                  })
                  ->orWhereHas('booking.gardener', function ($subQ) use ($searchTerm) {
                      $subQ->where('name', 'LIKE', "%{$searchTerm}%");
                  })
                  ->orWhereHas('booking.serviceProvider', function ($subQ) use ($searchTerm) {
                      $subQ->where('name', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $rating = Rating::with(['booking.homeowner', 'booking.gardener', 'booking.serviceProvider'])
                ->findOrFail($id);

            return response()->json([
                'id' => $rating->id,
                'booking_id' => $rating->booking_id,
                'rating' => $rating->rating,
                'feedback' => $rating->feedback,
                'service_date' => $rating->booking->service_date ?? 'N/A',
                'created_at' => $rating->created_at->format('M d, Y H:i'),
                'homeowner' => $rating->booking->homeowner ? [
                    'id' => $rating->booking->homeowner->id,
                    'name' => $rating->booking->homeowner->name,
                ] : null,
                'gardener' => $rating->booking->gardener ? [
                    'id' => $rating->booking->gardener->id,
                    'name' => $rating->booking->gardener->name,
                ] : null,
                'service_provider' => $rating->booking->serviceProvider ? [
                    'id' => $rating->booking->serviceProvider->id,
                    'name' => $rating->booking->serviceProvider->name,
                ] : null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch rating details: ' . $e->getMessage());
            return response()->json([
                'message' => 'Rating not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $rating = Rating::findOrFail($id);
            $rating->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rating deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to delete rating: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete rating',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function exportToCsv($query)
    {
        $ratings = $query->get();
        
        $filename = 'ratings_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($ratings) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Booking ID',
                'Rating',
                'Feedback',
                'Homeowner',
                'Gardener',
                'Service Provider',
                'Date Created',
                'Service Date'
            ]);

            // CSV Data
            foreach ($ratings as $rating) {
                fputcsv($file, [
                    $rating->id,
                    $rating->booking_id,
                    $rating->rating,
                    $rating->feedback,
                    $rating->booking->homeowner->name ?? 'N/A',
                    $rating->booking->gardener->name ?? 'N/A',
                    $rating->booking->serviceProvider->name ?? 'N/A',
                    $rating->created_at->format('Y-m-d H:i:s'),
                    $rating->booking->service_date ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    // Statistics methods for admin dashboard
    public function getStatistics(): JsonResponse
    {
        try {
            $totalRatings = Rating::count();
            $averageRating = Rating::avg('rating');
            
            $ratingDistribution = Rating::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating')
                ->get()
                ->pluck('count', 'rating');

            $recentRatings = Rating::with(['booking.homeowner', 'booking.gardener', 'booking.serviceProvider'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $lowRatings = Rating::where('rating', '<', 3)->count();
            $highRatings = Rating::where('rating', '>=', 4)->count();

            return response()->json([
                'total_ratings' => $totalRatings,
                'average_rating' => round($averageRating, 2),
                'rating_distribution' => $ratingDistribution,
                'recent_ratings' => $recentRatings,
                'low_ratings_count' => $lowRatings,
                'high_ratings_count' => $highRatings,
                'satisfaction_rate' => $totalRatings > 0 ? round(($highRatings / $totalRatings) * 100, 2) : 0
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch rating statistics: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'rating_ids' => 'required|array',
                'rating_ids.*' => 'exists:ratings,id'
            ]);

            $deletedCount = Rating::whereIn('id', $request->rating_ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} ratings"
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to bulk delete ratings: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete ratings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}