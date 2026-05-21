<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Destination::create([
            'city_name' => 'Paris',
            'country' => 'France',
            'description' => 'The City of Light, known for the Eiffel Tower, art museums, and romantic atmosphere.',
            'image' => null,
        ]);

        Destination::create([
            'city_name' => 'Tokyo',
            'country' => 'Japan',
            'description' => 'A vibrant metropolis blending traditional culture with cutting-edge technology.',
            'image' => null,
        ]);

        Destination::create([
            'city_name' => 'New York',
            'country' => 'USA',
            'description' => 'The Big Apple, famous for Times Square, Central Park, and the Statue of Liberty.',
            'image' => null,
        ]);

        Destination::create([
            'city_name' => 'Barcelona',
            'country' => 'Spain',
            'description' => 'A coastal city known for Gaudí architecture, beaches, and vibrant nightlife.',
            'image' => null,
        ]);

        Destination::create([
            'city_name' => 'Dubai',
            'country' => 'UAE',
            'description' => 'A modern city with luxury shopping, ultramodern architecture, and desert adventures.',
            'image' => null,
        ]);
    }
}
