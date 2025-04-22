<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Service;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the directory exists
        if (!Storage::disk('public')->exists('services')) {
            Storage::disk('public')->makeDirectory('services');
        }

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
            'user_type' => 'gardener',
            'rating' => 4.8,
            'completed_jobs' => 24,
            'years_experience' => 5,
            'bio' => 'Professional gardener with 5 years of experience in organic gardening and landscape design.',
            'highlighted_works' => json_encode([
                'gardeners/works/garden1.jpg',  // Relative to storage/app/public
                'gardeners/works/garden2.jpg',
            ]),
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

        // Gardening Services with images
        Service::create([
            'name' => 'Plant Care',
            'type' => 'Gardening',
            'price' => 500,
            'description' => 'Professional plant care to ensure healthy growth and longevity.',
            'image_url' => 'services/plant_care.jpg'
        ]);
        
        Service::create([
            'name' => 'Watering',
            'type' => 'Gardening',
            'price' => 500,
            'description' => 'Regular watering services to maintain optimal moisture levels for your plants.',
            'image_url' => 'services/watering.jpg'
        ]);
        
        Service::create([
            'name' => 'Pest Control',
            'type' => 'Gardening',
            'price' => 500,
            'description' => 'Effective pest management to protect your garden from harmful insects and diseases.',
            'image_url' => 'services/pest_control.jpg'
        ]);
        
        Service::create([
            'name' => 'Lawn Mowing',
            'type' => 'Gardening',
            'price' => 500,
            'description' => 'Professional lawn mowing service to keep your grass at the ideal height and appearance.',
            'image_url' => 'services/lawn_mowing.jpg'
        ]);
        
        // Landscaping Services with images
        Service::create([
            'name' => 'Garden Design',
            'type' => 'Landscaping',
            'price' => 500,
            'description' => 'Creative garden design services to transform your outdoor space.',
            'image_url' => 'services/garden_design.jpg'
        ]);

        Service::create([
            'name' => 'Pathway Construction',
            'type' => 'Landscaping',
            'price' => 500,
            'description' => 'Custom pathway construction using quality materials for functionality and aesthetics.',
            'image_url' => 'services/pathway_construction.jpg'
        ]);
        
        Service::create([
            'name' => 'Fencing',
            'type' => 'Landscaping',
            'price' => 500,
            'description' => 'Professional fence installation to enhance privacy and security.',
            'image_url' => 'services/fencing.jpg'
        ]);
        
        Service::create([
            'name' => 'Outdoor Furniture',
            'type' => 'Landscaping',
            'price' => 500,
            'description' => 'Selection and installation of durable and stylish outdoor furniture.',
            'image_url' => 'services/outdoor_furniture.jpg'
        ]);
    }
}