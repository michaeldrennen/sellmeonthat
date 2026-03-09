<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Home Services
        $homeServices = Category::firstOrCreate(
            ['slug' => 'home-services'],
            ['name' => 'Home Services', 'description' => 'Professional services for your home']
        );
        Category::firstOrCreate(['slug' => 'plumbing', 'parent_id' => $homeServices->id], ['name' => 'Plumbing']);
        Category::firstOrCreate(['slug' => 'electrical', 'parent_id' => $homeServices->id], ['name' => 'Electrical']);
        Category::firstOrCreate(['slug' => 'hvac', 'parent_id' => $homeServices->id], ['name' => 'HVAC']);
        Category::firstOrCreate(['slug' => 'cleaning', 'parent_id' => $homeServices->id], ['name' => 'Cleaning']);
        Category::firstOrCreate(['slug' => 'landscaping', 'parent_id' => $homeServices->id], ['name' => 'Landscaping']);
        Category::firstOrCreate(['slug' => 'painting', 'parent_id' => $homeServices->id], ['name' => 'Painting']);
        Category::firstOrCreate(['slug' => 'roofing', 'parent_id' => $homeServices->id], ['name' => 'Roofing']);
        Category::firstOrCreate(['slug' => 'carpentry', 'parent_id' => $homeServices->id], ['name' => 'Carpentry']);

        // Professional Services
        $professionalServices = Category::firstOrCreate(
            ['slug' => 'professional-services'],
            ['name' => 'Professional Services', 'description' => 'Business and professional services']
        );
        Category::firstOrCreate(['slug' => 'legal', 'parent_id' => $professionalServices->id], ['name' => 'Legal']);
        Category::firstOrCreate(['slug' => 'accounting', 'parent_id' => $professionalServices->id], ['name' => 'Accounting']);
        Category::firstOrCreate(['slug' => 'consulting', 'parent_id' => $professionalServices->id], ['name' => 'Consulting']);
        Category::firstOrCreate(['slug' => 'marketing', 'parent_id' => $professionalServices->id], ['name' => 'Marketing']);
        Category::firstOrCreate(['slug' => 'hr', 'parent_id' => $professionalServices->id], ['name' => 'Human Resources']);

        // Automotive
        $automotive = Category::firstOrCreate(
            ['slug' => 'automotive'],
            ['name' => 'Automotive', 'description' => 'Vehicle services and repairs']
        );
        Category::firstOrCreate(['slug' => 'auto-repair', 'parent_id' => $automotive->id], ['name' => 'Auto Repair']);
        Category::firstOrCreate(['slug' => 'auto-detailing', 'parent_id' => $automotive->id], ['name' => 'Auto Detailing']);
        Category::firstOrCreate(['slug' => 'towing', 'parent_id' => $automotive->id], ['name' => 'Towing']);
        Category::firstOrCreate(['slug' => 'oil-change', 'parent_id' => $automotive->id], ['name' => 'Oil Change']);

        // Health & Wellness
        $healthWellness = Category::firstOrCreate(
            ['slug' => 'health-wellness'],
            ['name' => 'Health & Wellness', 'description' => 'Health, fitness, and wellness services']
        );
        Category::firstOrCreate(['slug' => 'personal-training', 'parent_id' => $healthWellness->id], ['name' => 'Personal Training']);
        Category::firstOrCreate(['slug' => 'massage', 'parent_id' => $healthWellness->id], ['name' => 'Massage Therapy']);
        Category::firstOrCreate(['slug' => 'nutrition', 'parent_id' => $healthWellness->id], ['name' => 'Nutrition Counseling']);
        Category::firstOrCreate(['slug' => 'dental', 'parent_id' => $healthWellness->id], ['name' => 'Dental Services']);
        Category::firstOrCreate(['slug' => 'mental-health', 'parent_id' => $healthWellness->id], ['name' => 'Mental Health']);

        // Technology
        $technology = Category::firstOrCreate(
            ['slug' => 'technology'],
            ['name' => 'Technology', 'description' => 'IT and technology services']
        );
        Category::firstOrCreate(['slug' => 'it-support', 'parent_id' => $technology->id], ['name' => 'IT Support']);
        Category::firstOrCreate(['slug' => 'web-design', 'parent_id' => $technology->id], ['name' => 'Web Design']);
        Category::firstOrCreate(['slug' => 'app-development', 'parent_id' => $technology->id], ['name' => 'App Development']);
        Category::firstOrCreate(['slug' => 'computer-repair', 'parent_id' => $technology->id], ['name' => 'Computer Repair']);
        Category::firstOrCreate(['slug' => 'cybersecurity', 'parent_id' => $technology->id], ['name' => 'Cybersecurity']);

        // Events
        $events = Category::firstOrCreate(
            ['slug' => 'events'],
            ['name' => 'Events', 'description' => 'Event planning and services']
        );
        Category::firstOrCreate(['slug' => 'catering', 'parent_id' => $events->id], ['name' => 'Catering']);
        Category::firstOrCreate(['slug' => 'photography', 'parent_id' => $events->id], ['name' => 'Photography']);
        Category::firstOrCreate(['slug' => 'videography', 'parent_id' => $events->id], ['name' => 'Videography']);
        Category::firstOrCreate(['slug' => 'dj', 'parent_id' => $events->id], ['name' => 'DJ Services']);
        Category::firstOrCreate(['slug' => 'event-planning', 'parent_id' => $events->id], ['name' => 'Event Planning']);
        Category::firstOrCreate(['slug' => 'florist', 'parent_id' => $events->id], ['name' => 'Florist']);

        // Real Estate
        $realEstate = Category::firstOrCreate(
            ['slug' => 'real-estate'],
            ['name' => 'Real Estate', 'description' => 'Real estate and property services']
        );
        Category::firstOrCreate(['slug' => 'real-estate-agent', 'parent_id' => $realEstate->id], ['name' => 'Real Estate Agent']);
        Category::firstOrCreate(['slug' => 'property-management', 'parent_id' => $realEstate->id], ['name' => 'Property Management']);
        Category::firstOrCreate(['slug' => 'home-inspection', 'parent_id' => $realEstate->id], ['name' => 'Home Inspection']);
        Category::firstOrCreate(['slug' => 'moving', 'parent_id' => $realEstate->id], ['name' => 'Moving Services']);

        // Education
        $education = Category::firstOrCreate(
            ['slug' => 'education'],
            ['name' => 'Education', 'description' => 'Teaching and training services']
        );
        Category::firstOrCreate(['slug' => 'tutoring', 'parent_id' => $education->id], ['name' => 'Tutoring']);
        Category::firstOrCreate(['slug' => 'music-lessons', 'parent_id' => $education->id], ['name' => 'Music Lessons']);
        Category::firstOrCreate(['slug' => 'language-lessons', 'parent_id' => $education->id], ['name' => 'Language Lessons']);
        Category::firstOrCreate(['slug' => 'test-prep', 'parent_id' => $education->id], ['name' => 'Test Preparation']);

        // Pet Services
        $petServices = Category::firstOrCreate(
            ['slug' => 'pet-services'],
            ['name' => 'Pet Services', 'description' => 'Pet care and services']
        );
        Category::firstOrCreate(['slug' => 'pet-grooming', 'parent_id' => $petServices->id], ['name' => 'Pet Grooming']);
        Category::firstOrCreate(['slug' => 'pet-sitting', 'parent_id' => $petServices->id], ['name' => 'Pet Sitting']);
        Category::firstOrCreate(['slug' => 'dog-walking', 'parent_id' => $petServices->id], ['name' => 'Dog Walking']);
        Category::firstOrCreate(['slug' => 'veterinary', 'parent_id' => $petServices->id], ['name' => 'Veterinary Services']);

        // Creative Services
        $creativeServices = Category::firstOrCreate(
            ['slug' => 'creative-services'],
            ['name' => 'Creative Services', 'description' => 'Design and creative services']
        );
        Category::firstOrCreate(['slug' => 'graphic-design', 'parent_id' => $creativeServices->id], ['name' => 'Graphic Design']);
        Category::firstOrCreate(['slug' => 'copywriting', 'parent_id' => $creativeServices->id], ['name' => 'Copywriting']);
        Category::firstOrCreate(['slug' => 'video-editing', 'parent_id' => $creativeServices->id], ['name' => 'Video Editing']);
        Category::firstOrCreate(['slug' => 'animation', 'parent_id' => $creativeServices->id], ['name' => 'Animation']);
    }
}