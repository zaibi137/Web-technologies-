<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $hotels = Hotel::all();

        foreach ($users as $user) {
            // Create 2 bookings per user
            for ($i = 0; $i < 2; $i++) {
                $hotel = $hotels->random();
                $room = $hotel->rooms()->inRandomOrder()->first();
                
                $checkIn = Carbon::now()->addDays(rand(1, 30));
                $checkOut = $checkIn->copy()->addDays(rand(1, 7));
                $nights = $checkIn->diffInDays($checkOut);
                $totalPrice = $room->price_per_night * $nights;

                Booking::create([
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                    'hotel_id' => $hotel->id,
                    'check_in_date' => $checkIn,
                    'check_out_date' => $checkOut,
                    'guests' => rand(1, $room->max_guests),
                    'total_price' => $totalPrice,
                    'status' => ['pending', 'confirmed', 'completed'][rand(0, 2)],
                    'special_requests' => rand(0, 1) ? 'Early check-in requested' : null,
                ]);
            }
        }
    }
}
