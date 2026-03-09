<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(
            ['slug' => 'consumer'],
            [
                'name' => 'Consumer',
                'description' => 'Regular user who posts wants'
            ]
        );

        Role::firstOrCreate(
            ['slug' => 'business'],
            [
                'name' => 'Business',
                'description' => 'Business user who can respond to wants with offers'
            ]
        );

        Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'Administrator with full access'
            ]
        );

        Role::firstOrCreate(
            ['slug' => 'moderator'],
            [
                'name' => 'Moderator',
                'description' => 'Moderator who can review content'
            ]
        );
    }
}
