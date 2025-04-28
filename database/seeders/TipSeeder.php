<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipSeeder extends Seeder
{
    public function run()
    {
        DB::table('tips')->insert([
            [
                'season' => 'dry',
                'tip' => 'Water plants early morning to avoid evaporation.',
            ],
            [
                'season' => 'dry',
                'tip' => 'Plant drought-resistant veggies: okra, eggplant.',
            ],
            [
                'season' => 'dry',
                'tip' => 'Apply mulch to retain soil moisture.',
            ],
            [
                'season' => 'wet',
                'tip' => 'Improve drainage to prevent root rot.',
            ],
            [
                'season' => 'wet',
                'tip' => 'Trim overgrown branches before typhoons.',
            ],
            [
                'season' => 'wet',
                'tip' => 'Apply fungicide to avoid mold on leaves.',
            ],
        ]);
    }
}