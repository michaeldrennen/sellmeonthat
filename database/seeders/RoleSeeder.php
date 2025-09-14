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
        Role::firstOrCreate(['slug' => 'consumer'], ['name' => 'Consumer']);
        Role::firstOrCreate(['slug' => 'retailer'], ['name' => 'Retailer']);
    }
}
