<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'telegram_id' => '6821234179',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Seed brands
        $brands = [
            ['name' => 'Nike', 'description' => 'Athletic apparel and footwear'],
            ['name' => 'Adidas', 'description' => 'Sportswear and equipment'],
            ['name' => 'Puma', 'description' => 'Sports clothing and shoes'],
            ['name' => 'Under Armour', 'description' => 'Performance sportswear'],
            ['name' => 'New Balance', 'description' => 'Athletic footwear'],
            ['name' => 'Reebok', 'description' => 'Fitness and sports'],
            ['name' => 'Asics', 'description' => 'Running shoes and sportswear'],
            ['name' => 'Converse', 'description' => 'Casual footwear'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
