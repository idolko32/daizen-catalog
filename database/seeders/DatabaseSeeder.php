<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Product::upsert([
            ['name' => 'PVC Piping Cord Edging Strip (5mm and 8mm)', 'category' => 'Thread', 'sku' => 'DAI-PVC-004', 'price' => 100, 'unit' => 'metre', 'stock' => 100, 'image_path' => 'pv1.JPEG', 'is_active' => true],
            ['name' => 'Three-fold Sofa Bed Mechanism with Hydraulic Lift', 'category' => 'Tools', 'sku' => 'DAI-MEC-003', 'price' => 1000, 'unit' => 'set', 'stock' => 10, 'image_path' => 'ad10.jpeg', 'is_active' => true],
            ['name' => 'Gold Reflective Metallic PU Upholstery Leather', 'category' => 'Leather & synthetic leather', 'sku' => 'DAI-LEA-002', 'price' => 100, 'unit' => 'metre', 'stock' => 100, 'image_path' => '15O.jpeg', 'is_active' => true],
            ['name' => 'Reflective Upholstery Fabric 1', 'category' => 'Upholstery fabric', 'sku' => 'DAI-FAB-001', 'price' => 100, 'unit' => 'piece', 'stock' => 100, 'image_path' => 'pr1.jpeg', 'is_active' => true],
        ], ['sku'], ['name', 'category', 'price', 'unit', 'stock', 'image_path', 'is_active']);
    }
}
