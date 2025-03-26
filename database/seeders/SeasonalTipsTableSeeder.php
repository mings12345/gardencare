<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plant;
use App\Models\SeasonalTip;

class SeasonalTipsTableSeeder extends Seeder
{
    public function run()
    {
        // Create sample plants
        $rose = Plant::create(['name' => 'Rose']);
        $mango = Plant::create(['name' => 'Mango']);
        $sampaguita = Plant::create(['name' => 'Sampaguita']);

        // Create seasonal tips for plants in the Philippines
        SeasonalTip::create([
            'season' => 'Dry Season',
            'region' => 'Philippines',
            'tip' => 'Water roses deeply twice a week to prevent drought stress.',
            'plant_id' => $rose->id,
        ]);

        SeasonalTip::create([
            'season' => 'Wet Season',
            'region' => 'Philippines',
            'tip' => 'Ensure proper drainage for roses to avoid root rot during heavy rains.',
            'plant_id' => $rose->id,
        ]);

        SeasonalTip::create([
            'season' => 'Dry Season',
            'region' => 'Philippines',
            'tip' => 'Prune mango trees to encourage new growth and better fruit production.',
            'plant_id' => $mango->id,
        ]);

        SeasonalTip::create([
            'season' => 'Wet Season',
            'region' => 'Philippines',
            'tip' => 'Apply organic mulch around mango trees to retain soil moisture and prevent weeds.',
            'plant_id' => $mango->id,
        ]);

        SeasonalTip::create([
            'season' => 'Dry Season',
            'region' => 'Philippines',
            'tip' => 'Water sampaguita plants regularly to keep the soil moist during hot weather.',
            'plant_id' => $sampaguita->id,
        ]);

        SeasonalTip::create([
            'season' => 'Wet Season',
            'region' => 'Philippines',
            'tip' => 'Protect sampaguita plants from heavy rains by providing partial shade or cover.',
            'plant_id' => $sampaguita->id,
        ]);
    }
}
