<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            [
                'name' => 'Regal Penthouse Luxury Resort',
                'slug' => 'regal-penthouse-luxury-resort',
                'description' => 'Experience ultimate luxury in our exclusive penthouse suites with breathtaking city views.',
                'address' => '123 Luxury Avenue, Downtown',
                'city' => 'New York',
                'country' => 'United States',
                'star_rating' => 5,
                'amenities' => ['WiFi', 'Spa', 'Pool', 'Gym', 'Restaurant', 'Room Service'],
                'images' => [
                    'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=500',
                    'https://images.unsplash.com/photo-1521224519399-112d4b3b8052?w=500',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Coastal Dreams Beach Resort',
                'slug' => 'coastal-dreams-beach-resort',
                'description' => 'Relax by the pristine beaches with world-class amenities and personalized service.',
                'address' => '456 Ocean Drive, Beachfront',
                'city' => 'Miami',
                'country' => 'United States',
                'star_rating' => 5,
                'amenities' => ['Private Beach', 'Water Sports', 'Seafood Restaurant', 'Sunset Bar', 'Wellness Center'],
                'images' => [
                    'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=500',
                    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Alpine Mountain Retreat',
                'slug' => 'alpine-mountain-retreat',
                'description' => 'Escape to serene mountain peaks with luxurious accommodations and adventure activities.',
                'address' => '789 Mountain Peak Road',
                'city' => 'Aspen',
                'country' => 'United States',
                'star_rating' => 4,
                'amenities' => ['Ski Rental', 'Fireplace', 'Mountain View', 'Hiking Trails', 'Lodge Restaurant'],
                'images' => [
                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500',
                    'https://images.unsplash.com/photo-1552664730-d307ca884978?w=500',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Urban Central Hotel',
                'slug' => 'urban-central-hotel',
                'description' => 'Modern elegance in the heart of the city with easy access to business and entertainment districts.',
                'address' => '321 Market Street',
                'city' => 'Los Angeles',
                'country' => 'United States',
                'star_rating' => 4,
                'amenities' => ['Business Center', 'Rooftop Bar', 'Fitness Center', 'Conference Rooms'],
                'images' => [
                    'https://images.unsplash.com/photo-1564123409343-9c93d34f7f1f?w=500',
                    'https://images.unsplash.com/photo-1512941691920-25bda36dc842?w=500',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Garden Paradise Boutique Hotel',
                'slug' => 'garden-paradise-boutique-hotel',
                'description' => 'Intimate boutique hotel surrounded by lush gardens and tropical landscapes.',
                'address' => '555 Garden Lane',
                'city' => 'Honolulu',
                'country' => 'United States',
                'star_rating' => 4,
                'amenities' => ['Garden Tours', 'Spa Services', 'Tropical Restaurant', 'Pool'],
                'images' => [
                    'https://images.unsplash.com/photo-1469022563149-aa64dbd37dae?w=500',
                    'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=500',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($hotels as $hotelData) {
            Hotel::create($hotelData);
        }
    }
}
