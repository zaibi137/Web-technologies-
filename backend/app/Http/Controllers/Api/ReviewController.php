<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReviewController extends Controller
{
    /**
     * Get reviews by hotel
     */
    public function indexByHotel($hotelId, Request $request): JsonResponse
    {
        $query = Review::where('hotel_id', $hotelId);

        // Non-admin users only see visible reviews
        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->where('is_visible', true);
        }

        // Always eager-load user AND hotel so the frontend can display both
        $reviews = $query->with(['user', 'hotel'])->latest()->paginate(20);

        return response()->json($reviews, 200);
    }

    /**
     * Get ALL reviews across all hotels (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with(['user', 'hotel'])->latest();

        if ($request->has('hotel_id') && $request->hotel_id) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate(20);

        return response()->json($reviews, 200);
    }

    /**
     * Create review
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $review = Review::create([
            'user_id' => $request->user()->id,
            'hotel_id' => $request->hotel_id,
            'booking_id' => $request->booking_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_visible' => true,
        ]);

        $review->load('user');

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review,
        ], 201);
    }

    /**
     * Delete review (admin only)
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ], 200);
    }

    /**
     * Toggle review visibility (admin only)
     */
    public function toggleVisibility(Review $review): JsonResponse
    {
        $review->update(['is_visible' => !$review->is_visible]);

        return response()->json([
            'message' => 'Review visibility toggled',
            'review' => $review,
        ], 200);
    }
}
