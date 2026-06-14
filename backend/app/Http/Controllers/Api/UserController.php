<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Using standard base controller
use Exception;

class UserController extends Controller
{
    /**
     * Get all users (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::query();

            // Handle optional search query strings safely
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Paginate results latest first
            $users = $query->withCount('bookings')->latest()->paginate(15);

            // Returns the exact structure expected by response.data in users.html
            return response()->json([
                'success' => true,
                'data' => $users->items(), // Extracts the raw array cleanly out of the paginator
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $users->total()
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load users layout index definitions.',
                'error' => $e->getMessage(),
                'data' => []
            ], 200); // 200 breaks the endless loading loop on front-end if DB crashes
        }
    }

    /**
     * Get single user (admin only)
     */
    public function show($id): JsonResponse
    {
        try {
            $user = User::with(['bookings', 'reviews'])->find($id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User instance not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user data properties.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user role (admin only)
     */
    public function updateRole(Request $request, $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            $request->validate([
                'role' => 'required|in:user,admin,manager',
            ]);

            $user->update(['role' => $request->role]);

            return response()->json([
                'success' => true,
                'message' => 'User role updated successfully.',
                'user' => $user,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role adjustment verification crash.',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Toggle user active status (admin only)
     */
    public function toggleActive($id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            // Fallback default value handling if column doesn't support clean booleans directly
            $currentStatus = $user->is_active;
            $user->update(['is_active' => !$currentStatus]);

            return response()->json([
                'success' => true,
                'message' => 'User account status toggled successfully.',
                'user' => $user,
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Status toggle manipulation failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user (admin only)
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User records row not found'], 404);
            }

            // Safe fallback check protecting current authenticated root admin profile from self-deletion
            $currentUser = $request->user();
            if ($currentUser && $currentUser->id == $user->id) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Self-account removal action denied.'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Critical drop cascade restriction failure.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}