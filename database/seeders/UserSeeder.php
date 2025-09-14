<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Create 15 standard consumers
        User::factory( 15 )->create();

        // Create 5 retailers (who are also consumers by default)
        User::factory( 5 )->retailer()->create();
    }
}
