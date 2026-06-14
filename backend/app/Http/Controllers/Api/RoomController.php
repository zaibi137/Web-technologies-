<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreRoomRequest;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RoomController extends Controller
{
    /**
     * Get rooms by hotel
     */
    public function indexByHotel($hotelId, Request $request): JsonResponse
    {
        $query = Room::byHotel($hotelId);

        if (!$request->user() || !$request->user()->isAdmin()) {
            $query->available();
        }

        $rooms = $query->paginate(10);

        return response()->json($rooms, 200);
    }

    /**
     * Get single room
     */
    public function show(Room $room): JsonResponse
    {
        return response()->json($room, 200);
    }

    /**
     * Check room availability
     */
    public function checkAvailability(Room $room, Request $request): JsonResponse
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $available = $room->isAvailable($request->check_in, $request->check_out);

        return response()->json([
            'room_id' => $room->id,
            'available' => $available,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
        ], 200);
    }

    /**
     * Create room (admin only)
     */
    public function store(StoreRoomRequest $request): JsonResponse
    {
        $room = Room::create([
            'hotel_id' => $request->hotel_id,
            'room_type' => $request->room_type,
            'name' => $request->name,
            'description' => $request->description,
            'price_per_night' => $request->price_per_night,
            'max_guests' => $request->max_guests,
            'amenities' => $request->amenities ?? [],
            'images' => $request->images ?? [],
            'is_available' => $request->boolean('is_available', true),
        ]);

        return response()->json([
            'message' => 'Room created successfully',
            'room' => $room,
        ], 201);
    }

    /**
     * Update room (admin only)
     */
    public function update(Request $request, Room $room): JsonResponse
    {
        $room->update([
            'room_type' => $request->room_type ?? $room->room_type,
            'name' => $request->name ?? $room->name,
            'description' => $request->description ?? $room->description,
            'price_per_night' => $request->price_per_night ?? $room->price_per_night,
            'max_guests' => $request->max_guests ?? $room->max_guests,
            'amenities' => $request->amenities ?? $room->amenities,
            'images' => $request->images ?? $room->images,
            'is_available' => $request->has('is_available') ? $request->boolean('is_available') : $room->is_available,
        ]);

        return response()->json([
            'message' => 'Room updated successfully',
            'room' => $room,
        ], 200);
    }

    /**
     * Delete room (admin only)
     */
    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json([
            'message' => 'Room deleted successfully',
        ], 200);
    }
}