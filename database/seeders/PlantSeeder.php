<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;

class PlantSeeder extends Seeder
{
    public function run()
    {
        // Indoor Plants
        Plant::create([
            'name' => 'Snake Plant (Sansevieria)',
            'watering_frequency' => 'Every 2-3 weeks',
            'sunlight' => 'Low to bright indirect light',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Monthly during growing season',
            'common_problems' => 'Root rot from overwatering',
        ]);

        Plant::create([
            'name' => 'ZZ Plant (Zamioculcas)',
            'watering_frequency' => 'Every 3-4 weeks',
            'sunlight' => 'Low to bright indirect light',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Every 2-3 months',
            'common_problems' => 'Yellowing leaves from overwatering',
        ]);

        Plant::create([
            'name' => 'Peace Lily',
            'watering_frequency' => 'When soil is dry to touch',
            'sunlight' => 'Medium to low indirect light',
            'soil' => 'Peat-based potting mix',
            'fertilizer' => 'Every 6-8 weeks',
            'common_problems' => 'Brown leaf tips from low humidity',
        ]);

        // Outdoor Plants
        Plant::create([
            'name' => 'Bougainvillea',
            'watering_frequency' => 'When soil is dry',
            'sunlight' => 'Full sun (6+ hours daily)',
            'soil' => 'Well-draining, slightly acidic',
            'fertilizer' => 'High-phosphorus monthly during bloom',
            'common_problems' => 'Leaf spot, root rot',
        ]);

        Plant::create([
            'name' => 'Gumamela (Hibiscus)',
            'watering_frequency' => 'When top soil is dry',
            'sunlight' => 'Full sun',
            'soil' => 'Well-draining, fertile',
            'fertilizer' => 'High-potassium monthly during bloom',
            'common_problems' => 'Aphids, Japanese beetles',
        ]);

        Plant::create([
            'name' => 'Sampaguita (Jasminum sambac)',
            'watering_frequency' => 'When top soil feels dry',
            'sunlight' => 'Full sun to partial shade',
            'soil' => 'Well-draining, slightly acidic',
            'fertilizer' => 'Balanced monthly during growing season',
            'common_problems' => 'Aphids and whiteflies',
        ]);

        Plant::create([
            'name' => 'Orchids (Dendrobium)',
            'watering_frequency' => 'Every 5-7 days',
            'sunlight' => 'Bright indirect light',
            'soil' => 'Orchid bark mix',
            'fertilizer' => 'Weak solution weekly',
            'common_problems' => 'Root rot, scale insects',
        ]);

        Plant::create([
            'name' => 'Pothos (Devil\'s Ivy)',
            'watering_frequency' => 'When soil is half dry',
            'sunlight' => 'Low to bright indirect light',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Monthly in growing season',
            'common_problems' => 'Yellow leaves from overwatering',
        ]);

        Plant::create([
            'name' => 'Dracaena',
            'watering_frequency' => 'When top soil is dry',
            'sunlight' => 'Medium indirect light',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Every 2 months',
            'common_problems' => 'Brown tips from fluoride in water',
        ]);

        Plant::create([
            'name' => 'Croton',
            'watering_frequency' => 'When top inch is dry',
            'sunlight' => 'Bright indirect light',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Monthly in spring/summer',
            'common_problems' => 'Spider mites, leaf drop',
        ]);
    }
}