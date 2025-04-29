<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;

class PlantSeeder extends Seeder
{
    public function run()
    {
        Plant::create([
            'name' => 'Snake Plant',
            'watering_frequency' => 'Every 2-3 weeks',
            'sunlight' => 'Indirect bright light',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Feed monthly during spring and summer',
            'common_problems' => 'Root rot from overwatering',
        ]);

        Plant::create([
            'name' => 'Aloe Vera',
            'watering_frequency' => 'Every 3 weeks',
            'sunlight' => 'Full sun',
            'soil' => 'Sandy, well-draining soil',
            'fertilizer' => 'Feed with diluted fertilizer every 6 weeks',
            'common_problems' => 'Brown leaf tips from too much sun',
        ]);

        Plant::create([
            'name' => 'Peace Lily',
            'watering_frequency' => 'Once a week',
            'sunlight' => 'Low to bright indirect light',
            'soil' => 'Moist, well-draining soil',
            'fertilizer' => 'Feed every 6-8 weeks',
            'common_problems' => 'Brown tips due to dry air',
        ]);

        Plant::create([
            'name' => 'Spider Plant',
            'watering_frequency' => 'Every 1-2 weeks',
            'sunlight' => 'Bright, indirect sunlight',
            'soil' => 'Well-drained, general-purpose soil',
            'fertilizer' => 'Feed monthly in spring and summer',
            'common_problems' => 'Leaf tips browning due to fluoride in water',
        ]);

        Plant::create([
            'name' => 'Pothos',
            'watering_frequency' => 'Every 1-2 weeks',
            'sunlight' => 'Low to bright indirect light',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Fertilize every 2–3 months',
            'common_problems' => 'Yellow leaves from overwatering',
        ]);

        Plant::create([
            'name' => 'ZZ Plant',
            'watering_frequency' => 'Every 2-3 weeks',
            'sunlight' => 'Low to medium indirect light',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Fertilize 1–2 times during growing season',
            'common_problems' => 'Yellowing leaves from too much water',
        ]);

        Plant::create([
            'name' => 'Fiddle Leaf Fig',
            'watering_frequency' => 'Every 1-2 weeks',
            'sunlight' => 'Bright, indirect light',
            'soil' => 'Well-draining, rich soil',
            'fertilizer' => 'Feed monthly during growing season',
            'common_problems' => 'Brown spots from overwatering or drafts',
        ]);

        Plant::create([
            'name' => 'Rubber Plant',
            'watering_frequency' => 'Every 1-2 weeks',
            'sunlight' => 'Bright, indirect sunlight',
            'soil' => 'Well-draining potting mix',
            'fertilizer' => 'Fertilize monthly in spring/summer',
            'common_problems' => 'Drooping leaves from inconsistent watering',
        ]);

        Plant::create([
            'name' => 'Bamboo Palm',
            'watering_frequency' => 'Keep soil slightly moist',
            'sunlight' => 'Indirect sunlight or partial shade',
            'soil' => 'Well-draining, peat-based soil',
            'fertilizer' => 'Feed monthly with balanced fertilizer',
            'common_problems' => 'Leaf tips browning due to dry air',
        ]);

        Plant::create([
            'name' => 'Lavender',
            'watering_frequency' => 'Every 1-2 weeks (less in winter)',
            'sunlight' => 'Full sun',
            'soil' => 'Sandy, well-drained soil',
            'fertilizer' => 'Feed once in spring',
            'common_problems' => 'Root rot in poorly draining soil',
        ]);
    }
}
