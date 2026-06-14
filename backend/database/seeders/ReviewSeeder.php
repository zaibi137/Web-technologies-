<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $comments = [
            'Absolutely fantastic stay! The staff were incredibly welcoming and the rooms were immaculate.',
            'Great location, beautiful interiors, and outstanding room service. Highly recommend!',
            'One of the best hotels I have ever stayed in. The amenities were top-notch.',
            'Very comfortable and clean. The breakfast was excellent and staff very friendly.',
            'Perfect for a weekend getaway. Stunning views and a wonderfully relaxing atmosphere.',
            'Loved every moment of our stay. Will definitely be coming back again soon!',
            'The facilities were impressive and the service was prompt and professional.',
            'Good value for the price. Clean, modern rooms with all the essentials covered.',
            'A lovely boutique experience. Felt very personal and the attention to detail was superb.',
            'Excellent hospitality. Made us feel right at home throughout our entire stay.',
        ];

        $hotels  = Hotel::all();
        // Use ANY user (including admin) so seeder works even with no regular users
        $users   = User::all();

        if ($users->isEmpty() || $hotels->isEmpty()) {
            $this->command->warn('ReviewSeeder skipped: no users or hotels found.');
            return;
        }

        foreach ($hotels as $hotel) {
            $reviewCount = rand(3, 5);
            for ($i = 0; $i < $reviewCount; $i++) {
                Review::create([
                    'user_id'    => $users->random()->id,
                    'hotel_id'   => $hotel->id,
                    'booking_id' => null,
                    'rating'     => rand(3, 5),
                    'comment'    => $comments[array_rand($comments)],
                    'is_visible' => true,
                ]);
            }
        }
    }
}
