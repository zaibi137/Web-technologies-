<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    // GET /api/hotels
    public function index(): JsonResponse
    {
        $hotels = Hotel::withCount('rooms')->latest()->paginate(20);
        return response()->json($hotels, 200);
    }

    // POST /api/hotels  (admin only)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string',
            'country'     => 'nullable|string',
            'star_rating' => 'nullable|numeric|min:1|max:5',
            'is_active'   => 'nullable|boolean',
            'amenities'   => 'nullable|array',
            'images'      => 'nullable|array',
        ]);

        $validated['slug']      = Str::slug($validated['name']) . '-' . time();
        $validated['is_active'] = $request->input('is_active', true);
        $validated['amenities'] = $request->input('amenities', []);

        $hotel = Hotel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hotel created successfully.',
            'data'    => $hotel,
        ], 201);
    }

    // GET /api/hotels/{id}
    public function show($id): JsonResponse
    {
        $hotel = Hotel::withCount('rooms')->find($id);
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found.'], 404);
        }
        return response()->json($hotel, 200);
    }

    // PUT /api/hotels/{id}  (admin only)
    public function update(Request $request, $id): JsonResponse
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found.'], 404);
        }

        $validated = $request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string',
            'country'     => 'nullable|string',
            'star_rating' => 'nullable|numeric|min:1|max:5',
            'is_active'   => 'nullable|boolean',
            'amenities'   => 'nullable|array',
            'images'      => 'nullable|array',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . $id;
        }

        $hotel->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hotel updated successfully.',
            'data'    => $hotel,
        ], 200);
    }

    // DELETE /api/hotels/{id}  (admin only)
    public function destroy($id): JsonResponse
    {
        $hotel = Hotel::find($id);
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found.'], 404);
        }

        $hotel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hotel deleted successfully.',
        ], 200);
    }
}
