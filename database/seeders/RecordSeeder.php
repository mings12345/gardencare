<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gardencare.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'admin'
        ]);

        User::create([
            'name' => 'Homeowner user',
            'email' => 'homeowner@gardencare.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'homeowner'
        ]);
        
        User::create([
            'name' => 'Gardener user',
            'email' => 'gardener@gardencare.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'gardener'
        ]);
        User::create([
            'name' => 'nans',
            'email' => 'nans@gmail.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'gardener'
        ]);
        User::create([
            'name' => 'shin',
            'email' => 'shin@gmail.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'gardener'
        ]);
        User::create([
            'name' => 'Nina',
            'email' => 'nina@gmail.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'gardener'
        ]);
        User::create([
            'name' => 'Dwight',
            'email' => 'dwight@gmail.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'gardener'
        ]);
        User::create([
            'name' => 'Service Provider user',
            'email' => 'service_provider@gardencare.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'service_provider'
        ]);
        User::create([
            'name' => 'Nikki',
            'email' => 'nikki@gmail.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'service_provider'
        ]);
        User::create([
            'name' => 'JL',
            'email' => 'jl@gmail.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'service_provider'
        ]);
        User::create([
            'name' => 'Nica',
            'email' => 'nica@gmail.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'service_provider'
        ]);

        Service::create([
            'name' => 'Plant Care',
            'type' => 'Gardening',
            'price' => 500
        ]);
        Service::create([
            'name' => 'Watering',
            'type' => 'Gardening',
            'price' => 500
        ]);
        Service::create([
            'name' => 'Pest Control',
            'type' => 'Gardening',
            'price' => 500
        ]);
        Service::create([
            'name' => 'Lawn Mowing',
            'type' => 'Gardening',
            'price' => 500
        ]);
        
        Service::create([
            'name' => 'Garden Design',
            'type' => 'Landscaping',
            'price' => 500
        ]);

        Service::create([
            'name' => 'Pathway Construction',
            'type' => 'Landscaping',
            'price' => 500
        ]);
        
        Service::create([
            'name' => 'Fencing',
            'type' => 'Landscaping',
            'price' => 500
        ]);
        
        Service::create([
            'name' => 'Outdoor Furniture',
            'type' => 'Landscaping',
            'price' => 500
        ]);

    }
}
