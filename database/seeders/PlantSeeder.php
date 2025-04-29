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
    }
}
