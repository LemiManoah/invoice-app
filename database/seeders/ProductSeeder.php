<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $suit2Piece = ProductCategory::where('name', 'Suit (2-Piece)')->first();
        $suit3Piece = ProductCategory::where('name', 'Suit (3-Piece)')->first();
        $jacket = ProductCategory::where('name', 'Jacket/Blazer')->first();
        $trouser = ProductCategory::where('name', 'Trouser')->first();
        $waistcoat = ProductCategory::where('name', 'Waistcoat')->first();
        $shirt = ProductCategory::where('name', 'Shirt')->first();
        $coat = ProductCategory::where('name', 'Coat')->first();

        $products = [
            ['name' => 'Standard Suit (2-Piece)', 'product_category_id' => $suit2Piece?->id, 'base_price' => 450.00, 'description' => 'Classic two-piece suit with jacket and trousers'],
            ['name' => 'Premium Suit (2-Piece)', 'product_category_id' => $suit2Piece?->id, 'base_price' => 650.00, 'description' => 'Premium fabric two-piece suit'],
            ['name' => 'Luxury Suit (2-Piece)', 'product_category_id' => $suit2Piece?->id, 'base_price' => 850.00, 'description' => 'Luxury Italian fabric two-piece suit'],
            ['name' => 'Standard Suit (3-Piece)', 'product_category_id' => $suit3Piece?->id, 'base_price' => 550.00, 'description' => 'Classic three-piece suit'],
            ['name' => 'Premium Suit (3-Piece)', 'product_category_id' => $suit3Piece?->id, 'base_price' => 750.00, 'description' => 'Premium fabric three-piece suit'],
            ['name' => 'Luxury Suit (3-Piece)', 'product_category_id' => $suit3Piece?->id, 'base_price' => 950.00, 'description' => 'Luxury Italian fabric three-piece suit'],
            ['name' => 'Casual Blazer', 'product_category_id' => $jacket?->id, 'base_price' => 250.00, 'description' => 'Smart casual blazer'],
            ['name' => 'Formal Jacket', 'product_category_id' => $jacket?->id, 'base_price' => 300.00, 'description' => 'Formal event jacket'],
            ['name' => 'Dress Trouser', 'product_category_id' => $trouser?->id, 'base_price' => 120.00, 'description' => 'Standalone dress trousers'],
            ['name' => 'Waistcoat Standard', 'product_category_id' => $waistcoat?->id, 'base_price' => 100.00, 'description' => 'Standard waistcoat'],
            ['name' => 'Waistcoat Premium', 'product_category_id' => $waistcoat?->id, 'base_price' => 150.00, 'description' => 'Premium fabric waistcoat'],
            ['name' => 'Custom Shirt', 'product_category_id' => $shirt?->id, 'base_price' => 85.00, 'description' => 'Bespoke custom shirt'],
            ['name' => 'Premium Shirt', 'product_category_id' => $shirt?->id, 'base_price' => 120.00, 'description' => 'Premium fabric custom shirt'],
            ['name' => 'Overcoat', 'product_category_id' => $coat?->id, 'base_price' => 400.00, 'description' => 'Classic overcoat'],
            ['name' => 'Trench Coat', 'product_category_id' => $coat?->id, 'base_price' => 350.00, 'description' => 'Classic trench coat'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
