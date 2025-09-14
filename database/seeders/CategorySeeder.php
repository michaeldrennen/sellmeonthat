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
        $electronics = Category::firstOrCreate(['slug' => 'electronics'], ['name' => 'Electronics']);
        $home = Category::firstOrCreate(['slug' => 'home-goods'], ['name' => 'Home Goods']);

        Category::firstOrCreate(['slug' => 'smartphones', 'parent_id' => $electronics->id], ['name' => 'Smartphones']);
        Category::firstOrCreate(['slug' => 'laptops', 'parent_id' => $electronics->id], ['name' => 'Laptops']);

        Category::firstOrCreate(['slug' => 'kitchenware', 'parent_id' => $home->id], ['name' => 'Kitchenware']);
        Category::firstOrCreate(['slug' => 'furniture', 'parent_id' => $home->id], ['name' => 'Furniture']);
    }
}