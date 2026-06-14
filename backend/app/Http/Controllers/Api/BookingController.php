<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BookingController extends Controller
{
    /**
     * Get user's bookings (or all bookings for admin)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Booking::query()->orderByDesc('created_at');

        // Non-admin users can only see their own bookings
        if (!$request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->with('user', 'room', 'hotel')->paginate(10);

        return response()->json($bookings, 200);
    }

    /**
     * Get single booking
     */
    public function show(Request $request, Booking $booking): JsonResponse
    {
        // Users can only view their own bookings
        if (!$request->user()->isAdmin() && $request->user()->id !== $booking->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->load('user', 'room', 'hotel', 'payment');

        return response()->json($booking, 200);
    }

    /**
     * Create new booking
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        // Calculate total price — fetch room directly by room_id
        $room = \App\Models\Room::find($request->room_id);
        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }
        
        $nights = \Carbon\Carbon::parse($request->check_out_date)
            ->diffInDays(\Carbon\Carbon::parse($request->check_in_date));
        
        $totalPrice = $room->price_per_night * $nights;

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'room_id' => $request->room_id,
            'hotel_id' => $request->hotel_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'guests' => $request->guests,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        $booking->load('user', 'room', 'hotel');

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking,
        ], 201);
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        // Users can only cancel their own bookings
        if (!$request->user()->isAdmin() && $request->user()->id !== $booking->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$booking->canCancel()) {
            return response()->json([
                'message' => 'Cannot cancel booking in ' . $booking->status . ' status',
            ], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Booking cancelled successfully',
            'booking' => $booking,
        ], 200);
    }

    /**
     * Update booking status (admin only)
     */
    public function updateStatus(Request $request, Booking $booking): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Booking status updated',
            'booking' => $booking,
        ], 200);
    }
}