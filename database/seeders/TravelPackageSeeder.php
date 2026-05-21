<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TravelPackage;
use App\Models\Destination;
use App\Models\Employee;
use App\Models\UserAccount;

class TravelPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Get destinations
        $paris     = Destination::where('city_name', 'Paris')->first();
        $tokyo     = Destination::where('city_name', 'Tokyo')->first();
        $newYork   = Destination::where('city_name', 'New York')->first();
        $barcelona = Destination::where('city_name', 'Barcelona')->first();
        $dubai     = Destination::where('city_name', 'Dubai')->first();

        // Get admin employee (for admin-created packages)
        $adminAccount  = UserAccount::where('role', 'admin')->first();
        $adminEmployee = $adminAccount ? Employee::where('id', $adminAccount->employee_id)->first() : null;

        // Get agent employees
        $agent1Account  = UserAccount::where('username', 'agent1')->first();
        $agent1Employee = $agent1Account ? Employee::where('id', $agent1Account->employee_id)->first() : null;

        $agent2Account  = UserAccount::where('username', 'agent2')->first();
        $agent2Employee = $agent2Account ? Employee::where('id', $agent2Account->employee_id)->first() : null;

        // ── 7 ADMIN-CREATED PACKAGES ──────────────────────────────
        $adminPackages = [
            [
                'destination_id'  => $paris->id,
                'package_name'    => 'Paris Romance Getaway',
                'package_type'    => 'Romantic',
                'description'     => 'Experience the magic of Paris with a romantic 7-day escape. Visit the Eiffel Tower, stroll along the Seine, and enjoy world-class cuisine.',
                'itinerary'       => "Day 1: Arrival & Eiffel Tower\nDay 2: Louvre Museum & Tuileries Garden\nDay 3: Versailles Day Trip\nDay 4: Montmartre & Sacré-Cœur\nDay 5: Seine River Cruise & Shopping\nDay 6: Day trip to Champagne region\nDay 7: Departure",
                'inclusions'      => "Round-trip airfare\n5-star hotel accommodation (7 nights)\nDaily breakfast\nGuided city tours\nSeine River cruise\nAirport transfers",
                'exclusions'      => "Personal expenses\nLunch and dinner (except breakfast)\nOptional excursions\nTravel insurance",
                'package_cost'    => 2499.00,
                'start_date'      => '2025-07-01',
                'end_date'        => '2025-07-08',
                'slots_available' => 20,
                'is_visible'      => true,
            ],
            [
                'destination_id'  => $tokyo->id,
                'package_name'    => 'Tokyo Cultural Immersion',
                'package_type'    => 'Cultural',
                'description'     => 'Dive deep into Japanese culture with this 10-day Tokyo experience. From ancient temples to futuristic technology districts.',
                'itinerary'       => "Day 1: Arrival & Shinjuku\nDay 2: Asakusa & Senso-ji Temple\nDay 3: Akihabara & teamLab\nDay 4: Harajuku & Shibuya\nDay 5: Day trip to Nikko\nDay 6: Tsukiji Market & Odaiba\nDay 7: Mount Fuji Day Trip\nDay 8: Kyoto Day Trip\nDay 9: Ginza & Tokyo Tower\nDay 10: Departure",
                'inclusions'      => "Round-trip airfare\n4-star hotel (10 nights)\nJR Pass (7 days)\nDaily breakfast\nGuided tours\nAirport transfers",
                'exclusions'      => "Visa fees\nPersonal shopping\nLunch and dinner\nOptional activities",
                'package_cost'    => 3299.00,
                'start_date'      => '2025-08-05',
                'end_date'        => '2025-08-15',
                'slots_available' => 15,
                'is_visible'      => true,
            ],
            [
                'destination_id'  => $newYork->id,
                'package_name'    => 'New York City Explorer',
                'package_type'    => 'Adventure',
                'description'     => 'Explore the city that never sleeps! 6 days packed with iconic landmarks, Broadway shows, and the best food NYC has to offer.',
                'itinerary'       => "Day 1: Arrival & Times Square\nDay 2: Statue of Liberty & Ellis Island\nDay 3: Central Park & Metropolitan Museum\nDay 4: Brooklyn Bridge & DUMBO\nDay 5: Broadway Show & 5th Avenue\nDay 6: Departure",
                'inclusions'      => "Round-trip airfare\n4-star hotel (6 nights)\nMetro card (7 days)\nBroadway show ticket\nStatue of Liberty ferry\nAirport transfers",
                'exclusions'      => "Meals (except welcome dinner)\nPersonal expenses\nMuseum entry fees\nTips and gratuities",
                'package_cost'    => 2199.00,
                'start_date'      => '2025-09-10',
                'end_date'        => '2025-09-16',
                'slots_available' => 25,
                'is_visible'      => true,
            ],
            [
                'destination_id'  => $dubai->id,
                'package_name'    => 'Dubai Luxury Experience',
                'package_type'    => 'Luxury',
                'description'     => 'Indulge in the ultimate luxury experience in Dubai. Stay in a 5-star resort, explore the desert, and shop in the world\'s largest mall.',
                'itinerary'       => "Day 1: Arrival & Burj Khalifa\nDay 2: Desert Safari & BBQ Dinner\nDay 3: Dubai Mall & Dubai Fountain\nDay 4: Palm Jumeirah & Atlantis\nDay 5: Gold Souk & Spice Souk\nDay 6: Abu Dhabi Day Trip\nDay 7: Departure",
                'inclusions'      => "Round-trip airfare\n5-star resort (7 nights)\nDesert safari with dinner\nBurj Khalifa entry\nCity tour\nAirport transfers",
                'exclusions'      => "Visa fees\nPersonal shopping\nSpa treatments\nAlcoholic beverages",
                'package_cost'    => 3899.00,
                'start_date'      => '2025-10-01',
                'end_date'        => '2025-10-08',
                'slots_available' => 12,
                'is_visible'      => true,
            ],
            [
                'destination_id'  => $barcelona->id,
                'package_name'    => 'Barcelona Art & Beach',
                'package_type'    => 'Leisure',
                'description'     => 'Discover the best of Barcelona — Gaudí masterpieces, tapas, flamenco, and beautiful Mediterranean beaches.',
                'itinerary'       => "Day 1: Arrival & Las Ramblas\nDay 2: Sagrada Família & Park Güell\nDay 3: Gothic Quarter & Barceloneta Beach\nDay 4: Camp Nou & Montjuïc\nDay 5: Day trip to Montserrat\nDay 6: Tapas Tour & Flamenco Show\nDay 7: Departure",
                'inclusions'      => "Round-trip airfare\n4-star hotel (7 nights)\nSagrada Família entry\nFlamenco show\nMontserrat day trip\nAirport transfers",
                'exclusions'      => "Meals (except breakfast)\nPersonal expenses\nOptional activities\nTravel insurance",
                'package_cost'    => 2099.00,
                'start_date'      => '2025-06-15',
                'end_date'        => '2025-06-22',
                'slots_available' => 18,
                'is_visible'      => true,
            ],
            [
                'destination_id'  => $paris->id,
                'package_name'    => 'Paris Family Adventure',
                'package_type'    => 'Family',
                'description'     => 'A family-friendly Paris trip designed for all ages. Disneyland Paris, the Eiffel Tower, and interactive museum experiences.',
                'itinerary'       => "Day 1: Arrival & Eiffel Tower picnic\nDay 2: Disneyland Paris (full day)\nDay 3: Louvre Kids Tour & Tuileries\nDay 4: Versailles Gardens\nDay 5: Science Museum & Pompidou\nDay 6: Departure",
                'inclusions'      => "Round-trip airfare\nFamily hotel (6 nights)\nDisneyland Paris tickets\nLouvre family tour\nAirport transfers\nDaily breakfast",
                'exclusions'      => "Lunch and dinner\nSouvenirs\nExtra Disneyland rides\nPersonal expenses",
                'package_cost'    => 1899.00,
                'start_date'      => '2025-07-20',
                'end_date'        => '2025-07-26',
                'slots_available' => 30,
                'is_visible'      => true,
            ],
            [
                'destination_id'  => $tokyo->id,
                'package_name'    => 'Tokyo Anime & Pop Culture Tour',
                'package_type'    => 'Special Interest',
                'description'     => 'The ultimate tour for anime and pop culture fans. Visit Akihabara, Studio Ghibli Museum, and exclusive merchandise stores.',
                'itinerary'       => "Day 1: Arrival & Akihabara\nDay 2: Studio Ghibli Museum\nDay 3: Harajuku & Takeshita Street\nDay 4: Odaiba & teamLab Planets\nDay 5: Shibuya & Manga Cafes\nDay 6: Ikebukuro & Sunshine City\nDay 7: Departure",
                'inclusions'      => "Round-trip airfare\n3-star hotel (7 nights)\nStudio Ghibli Museum ticket\nteamLab Planets entry\nAkihabara guided tour\nAirport transfers",
                'exclusions'      => "Merchandise purchases\nMeals\nPersonal expenses\nOptional activities",
                'package_cost'    => 2799.00,
                'start_date'      => '2025-11-01',
                'end_date'        => '2025-11-08',
                'slots_available' => 20,
                'is_visible'      => true,
            ],
        ];

        foreach ($adminPackages as $pkg) {
            TravelPackage::create(array_merge($pkg, [
                'employee_id' => $adminEmployee?->id,
            ]));
        }

        // ── 6 AGENT-CREATED PACKAGES ──────────────────────────────
        $agentPackages = [
            // Agent 1 packages (3)
            [
                'employee_id'     => $agent1Employee?->id,
                'destination_id'  => $barcelona->id,
                'package_name'    => 'Barcelona Weekend Escape',
                'package_type'    => 'Short Break',
                'description'     => 'A quick 4-day Barcelona getaway perfect for a long weekend. Highlights include Sagrada Família, tapas, and the beach.',
                'itinerary'       => "Day 1: Arrival & Barceloneta Beach\nDay 2: Sagrada Família & Park Güell\nDay 3: Gothic Quarter & Tapas Tour\nDay 4: Departure",
                'inclusions'      => "Return flights\n3-star hotel (4 nights)\nSagrada Família ticket\nTapas tour\nAirport transfers",
                'exclusions'      => "Meals (except breakfast)\nPersonal expenses",
                'package_cost'    => 1299.00,
                'start_date'      => '2025-06-01',
                'end_date'        => '2025-06-05',
                'slots_available' => 15,
                'is_visible'      => true,
            ],
            [
                'employee_id'     => $agent1Employee?->id,
                'destination_id'  => $dubai->id,
                'package_name'    => 'Dubai Desert & City Tour',
                'package_type'    => 'Adventure',
                'description'     => 'Experience the contrast of Dubai — from the glittering skyline to the vast Arabian desert.',
                'itinerary'       => "Day 1: Arrival & City Tour\nDay 2: Desert Safari & Camel Ride\nDay 3: Burj Khalifa & Dubai Mall\nDay 4: Palm Jumeirah\nDay 5: Departure",
                'inclusions'      => "Return flights\n4-star hotel (5 nights)\nDesert safari\nBurj Khalifa entry\nAirport transfers",
                'exclusions'      => "Visa fees\nPersonal shopping\nMeals",
                'package_cost'    => 2599.00,
                'start_date'      => '2025-09-20',
                'end_date'        => '2025-09-25',
                'slots_available' => 10,
                'is_visible'      => true,
            ],
            [
                'employee_id'     => $agent1Employee?->id,
                'destination_id'  => $newYork->id,
                'package_name'    => 'New York Budget Explorer',
                'package_type'    => 'Budget',
                'description'     => 'See the best of New York without breaking the bank. 5 days of iconic sights at an affordable price.',
                'itinerary'       => "Day 1: Arrival & Times Square\nDay 2: Central Park & Museum Mile\nDay 3: Brooklyn & DUMBO\nDay 4: Statue of Liberty\nDay 5: Departure",
                'inclusions'      => "Return flights\n3-star hotel (5 nights)\nMetro card\nStatue of Liberty ferry\nAirport transfers",
                'exclusions'      => "All meals\nMuseum entry fees\nPersonal expenses",
                'package_cost'    => 1499.00,
                'start_date'      => '2025-10-15',
                'end_date'        => '2025-10-20',
                'slots_available' => 20,
                'is_visible'      => true,
            ],
            // Agent 2 packages (3)
            [
                'employee_id'     => $agent2Employee?->id,
                'destination_id'  => $paris->id,
                'package_name'    => 'Paris Art Lovers Tour',
                'package_type'    => 'Cultural',
                'description'     => 'A curated tour for art enthusiasts. Explore the Louvre, Musée d\'Orsay, and the vibrant Marais art district.',
                'itinerary'       => "Day 1: Arrival & Marais District\nDay 2: Louvre Museum (full day)\nDay 3: Musée d'Orsay & Rodin Museum\nDay 4: Centre Pompidou & street art\nDay 5: Versailles art collections\nDay 6: Departure",
                'inclusions'      => "Return flights\n4-star hotel (6 nights)\nMuseum pass (5 days)\nGuided art tours\nAirport transfers",
                'exclusions'      => "Meals\nPersonal art purchases\nOptional workshops",
                'package_cost'    => 2299.00,
                'start_date'      => '2025-08-20',
                'end_date'        => '2025-08-26',
                'slots_available' => 12,
                'is_visible'      => true,
            ],
            [
                'employee_id'     => $agent2Employee?->id,
                'destination_id'  => $tokyo->id,
                'package_name'    => 'Tokyo Food & Sake Tour',
                'package_type'    => 'Culinary',
                'description'     => 'A foodie\'s paradise — explore Tokyo\'s incredible culinary scene from street food to Michelin-starred restaurants.',
                'itinerary'       => "Day 1: Arrival & Tsukiji Outer Market\nDay 2: Ramen & Sushi masterclass\nDay 3: Izakaya hopping in Shinjuku\nDay 4: Sake brewery tour\nDay 5: Street food in Asakusa\nDay 6: Departure",
                'inclusions'      => "Return flights\n4-star hotel (6 nights)\nFood tour (3 days)\nSake brewery visit\nCooking class\nAirport transfers",
                'exclusions'      => "Additional meals\nAlcoholic beverages beyond tour\nPersonal expenses",
                'package_cost'    => 2899.00,
                'start_date'      => '2025-11-15',
                'end_date'        => '2025-11-21',
                'slots_available' => 8,
                'is_visible'      => true,
            ],
            [
                'employee_id'     => $agent2Employee?->id,
                'destination_id'  => $barcelona->id,
                'package_name'    => 'Barcelona Gaudi Architecture Tour',
                'package_type'    => 'Cultural',
                'description'     => 'A deep dive into the genius of Antoni Gaudí. Visit every major Gaudí masterpiece in Barcelona with expert architectural guides.',
                'itinerary'       => "Day 1: Arrival & Casa Batlló\nDay 2: Sagrada Família (full day)\nDay 3: Park Güell & Casa Milà\nDay 4: Palau Güell & Gothic Quarter\nDay 5: Day trip to Colònia Güell\nDay 6: Departure",
                'inclusions'      => "Return flights\n4-star hotel (6 nights)\nAll Gaudí site entries\nExpert architectural guide\nAirport transfers",
                'exclusions'      => "Meals\nPersonal expenses\nOptional activities",
                'package_cost'    => 2199.00,
                'start_date'      => '2025-07-10',
                'end_date'        => '2025-07-16',
                'slots_available' => 14,
                'is_visible'      => true,
            ],
        ];

        foreach ($agentPackages as $pkg) {
            TravelPackage::create($pkg);
        }
    }
}
