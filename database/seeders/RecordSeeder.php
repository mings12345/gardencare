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
            'name' => 'Pruning & Trimming	',
            'type' => 'Gardening',
            'description' => 'Requires skill to avoid damaging the plant. Takes around 1 hour depending on size/quantity. Includes tool sanitization and cleanup.',
            'price' => 300,
            'price_description' => 'Per Plant',
            'image' => 'trimming.jpg'
        ]);
        Service::create([
            'name' => 'Watering',
            'type' => 'Gardening',
            'description' => 'Regular watering service to keep your plants hydrated and healthy, with customized schedules.',
            'price' => 500,
            'price_description' => 'Per visit',
            'image' => 'water.jpg'
        ]);
        Service::create([
            'name' => 'Pest Control',
            'type' => 'Gardening',
            'description' => 'For treating up to 10 plants or a small garden. Includes site inspection and full application.',
            'price' => 1000,
            'price_description' => 'Per Session',
            'image' => 'pest_control.jpg'
        ]);
        Service::create([
            'name' => 'Fertilizing',
            'type' => 'Gardening',
            'description' => 'Covers general fertilizer and 1-hour work.',
            'price' => 1000,
            'price_description' => 'Per Session',
            'image' => 'feltirizing.jpg'
        ]);

        Service::create([
            'name' => 'Soil Replacement',
            'type' => 'Gardening',
            'description' => 'Covers new soil and labor per plant.',
            'price' => 100,
            'price_description' => 'Per Pot (depending on size)',
            'image' => 'soil.jpg'
        ]);
        
        Service::create([
            'name' => 'Garden Bed Construction',
            'type' => 'Landscaping',
            'description' => 'Custom garden design services to create beautiful and functional outdoor spaces.',
            'price' => 1000,
            'price_description' => 'Small Raised Bed',
            'image' => 'garden bed.jpg'
        ]);

        Service::create([
            'name' => 'Landscape Design Consultation',
            'type' => 'Landscaping',
            'description' => 'One-time, fixed service for site visit and planning.',
            'price' => 1500,
            'image' => 'planning.jpg'
        ]);
        
        Service::create([
            'name' => 'Tree Trimming',
            'type' => 'Landscaping',
            'description' => 'Fixed for small trees, variable for height and hazard level. ₱1,000 - ₱2,000 (for large trees)',
            'price' => 800,
            'price_description' => 'Per Tree',
            'image' => 'tree.jpg'
        ]);
        
        Service::create([
            'name' => 'Lawn Installation',
            'type' => 'Landscaping',
            'description' => 'Heavily depends on grass type, land condition, and irrigation.
            Includes: Delivery and laying of sod, Basic site cleaning and debris removal, Light leveling of soil, Initial watering.',
            'price' => 150,
            'price_description' => 'Per Square Meter',
            'image' => 'lawn installation.jpg'
        ]);
    }
}