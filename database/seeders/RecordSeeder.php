<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Service;
use App\Models\Setting;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin =  User::create([
            'name' => 'Admin User',
            'email' => 'admin@gardencare.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'admin'
        ]);
        Setting::create([
            'admin_user_wallet' => $admin->id,
            'admin_fee_percentage' => 3,
        ]);

        User::create([
            'name' => 'Homeowner user',
            'email' => 'homeowner@gardencare.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'homeowner'
        ]);
        
        User::create([
            'name' => 'Nikks',
            'email' => 'nikks@gardencare.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'homeowner'
        ]);
        
        User::create([
            'name' => 'Gardener user',
            'email' => 'gardener@gardencare.com',
            'password' => bcrypt('LLCC@2025'),
            'user_type' => 'gardener',
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
            'description' => 'Professional care for your plants including pruning, fertilizing, and overall health assessment.',
            'min_price' => 500,
            'max_price' => 5000,
            'price_description' => 'Per Sesion',
            'image' => 'plant_care.jpg'
        ]);
        Service::create([
            'name' => 'Watering',
            'type' => 'Gardening',
            'description' => 'Regular watering service to keep your plants hydrated and healthy, with customized schedules.',
            'min_price' => 500,
            'max_price' => 1000,
            'price_description' => 'Per visit',
            'image' => 'watering.jpg'
        ]);
        Service::create([
            'name' => 'Pest Control',
            'type' => 'Gardening',
            'description' => 'Eco-friendly pest control solutions to protect your plants from harmful insects and diseases.',
            'min_price' => 500,
            'max_price' => null,
            'image' => 'pest_control.jpg'
        ]);
        Service::create([
            'name' => 'Lawn Mowing',
            'type' => 'Gardening',
            'description' => 'Professional lawn mowing service to keep your grass at the perfect height and looking neat.',
            'min_price' => 500,
            'max_price' => null,
            'image' => 'lawn mowing.jpg'
        ]);
        
        Service::create([
            'name' => 'Garden Design',
            'type' => 'Landscaping',
            'description' => 'Custom garden design services to create beautiful and functional outdoor spaces.',
            'min_price' => 5000,
            'max_price' => 15000,
            'image' => 'garden_design.jpg'
        ]);

        Service::create([
            'name' => 'Pathway Construction',
            'type' => 'Landscaping',
            'description' => 'Design and construction of beautiful pathways using various materials like stone, brick, or gravel.',
            'min_price' => 10000,
            'max_price' => 25000,
            'image' => 'pathway.jpg'
        ]);
        
        Service::create([
            'name' => 'Fencing',
            'type' => 'Landscaping',
            'description' => 'Installation of durable and attractive fences to enhance privacy and security in your garden.',
            'min_price' => 80000,
            'max_price' => 25000,
            'price_description' => 'Per meter',
            'image' => 'fencing.jpg'
        ]);
        
        Service::create([
            'name' => 'Outdoor Furniture',
            'type' => 'Landscaping',
            'description' => 'Selection and installation of weather-resistant outdoor furniture to complement your garden design.',
            'min_price' => 15000,
            'max_price' => 50000,
            'image' => 'outdoor.jpg'
        ]);
    }
}