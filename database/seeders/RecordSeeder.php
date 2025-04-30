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
            'price' => 500,
            'image' => 'plant_care.jpg'
        ]);
        Service::create([
            'name' => 'Watering',
            'type' => 'Gardening',
            'description' => 'Regular watering service to keep your plants hydrated and healthy, with customized schedules.',
            'price' => 500,
            'image' => 'watering.jpg'
        ]);
        Service::create([
            'name' => 'Pest Control',
            'type' => 'Gardening',
            'description' => 'Eco-friendly pest control solutions to protect your plants from harmful insects and diseases.',
            'price' => 500,
            'image' => 'pest_control.jpg'
        ]);
        Service::create([
            'name' => 'Lawn Mowing',
            'type' => 'Gardening',
            'description' => 'Professional lawn mowing service to keep your grass at the perfect height and looking neat.',
            'price' => 500,
            'image' => 'lawn_mowing.jpg'
        ]);
        
        Service::create([
            'name' => 'Garden Design',
            'type' => 'Landscaping',
            'description' => 'Custom garden design services to create beautiful and functional outdoor spaces.',
            'price' => 500,
            'image' => 'garden-design.jpg'
        ]);

        Service::create([
            'name' => 'Pathway Construction',
            'type' => 'Landscaping',
            'description' => 'Design and construction of beautiful pathways using various materials like stone, brick, or gravel.',
            'price' => 500,
            'image' => 'pathway.jpg'
        ]);
        
        Service::create([
            'name' => 'Fencing',
            'type' => 'Landscaping',
            'description' => 'Installation of durable and attractive fences to enhance privacy and security in your garden.',
            'price' => 500,
            'image' => 'fencing.jpg'
        ]);
        
        Service::create([
            'name' => 'Outdoor Furniture',
            'type' => 'Landscaping',
            'description' => 'Selection and installation of weather-resistant outdoor furniture to complement your garden design.',
            'price' => 500,
            'image' => 'outdoor-furniture.jpg'
        ]);
    }
}