<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Get all hotels
        $hotels = Hotel::all();

        foreach ($hotels as $hotel) {
            // Create 3 rooms per hotel
            Room::create([
                'hotel_id' => $hotel->id,
                'room_type' => 'Deluxe',
                'name' => "Deluxe Suite - {$hotel->name}",
                'description' => 'Spacious deluxe suite with premium amenities and stunning views.',
                'price_per_night' => rand(150, 300),
                'max_guests' => 2,
                'amenities' => ['King Bed', 'Free WiFi', 'Marble Bathroom', 'City View'],
                'images' => [
                    'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=500',
                ],
                'is_available' => true,
            ]);

            Room::create([
                'hotel_id' => $hotel->id,
                'room_type' => 'Suite',
                'name' => "Executive Suite - {$hotel->name}",
                'description' => 'Luxurious executive suite with separate living area and premium service.',
                'price_per_night' => rand(250, 500),
                'max_guests' => 4,
                'amenities' => ['King Bed', 'Living Room', 'Soaking Tub', 'Balcony'],
                'images' => [
                    'https://images.unsplash.com/photo-1591088398332-8c255a03a800?w=500',
                ],
                'is_available' => true,
            ]);

            Room::create([
                'hotel_id' => $hotel->id,
                'room_type' => 'Standard',
                'name' => "Standard Room - {$hotel->name}",
                'description' => 'Comfortable standard room with essential amenities.',
                'price_per_night' => rand(80, 150),
                'max_guests' => 2,
                'amenities' => ['Queen Bed', 'Modern Bathroom', 'Work Desk'],
                'images' => [
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500',
                ],
                'is_available' => true,
            ]);
        }
    }
}
