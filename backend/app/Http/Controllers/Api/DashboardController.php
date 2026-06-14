<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Get admin dashboard stats
     */
    public function stats(): JsonResponse
    {
        $totalHotels = Hotel::count();
        $totalRooms = Room::count();
        $totalBookings = Booking::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRevenue = Booking::where('status', 'completed')
            ->sum('total_price');

        $bookingsByStatus = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
        ];

        $recentBookings = Booking::with('user', 'room', 'hotel')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'total_hotels' => $totalHotels,
            'total_rooms' => $totalRooms,
            'total_bookings' => $totalBookings,
            'total_users' => $totalUsers,
            'total_revenue' => $totalRevenue,
            'bookings_by_status' => $bookingsByStatus,
            'recent_bookings' => $recentBookings,
            'total_admins' => $totalAdmins,
        ], 200);
    }
}