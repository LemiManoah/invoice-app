<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

final class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Suit (2-Piece)', 'description' => 'Two-piece suits including jacket and trousers'],
            ['name' => 'Suit (3-Piece)', 'description' => 'Three-piece suits including jacket, trousers, and waistcoat'],
            ['name' => 'Jacket/Blazer', 'description' => 'Single jackets and blazers'],
            ['name' => 'Trouser', 'description' => 'Standalone trousers/pants'],
            ['name' => 'Waistcoat', 'description' => 'Waistcoats and vests'],
            ['name' => 'Shirt', 'description' => 'Custom shirts and blouses'],
            ['name' => 'Coat', 'description' => 'Overcoats and long coats'],
            ['name' => 'Alterations', 'description' => 'Alteration services'],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
