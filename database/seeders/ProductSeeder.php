<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database with realistic products.
     * Uses updateOrCreate to avoid duplicating data on repeated runs.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'category' => 'Smartphone',
                'price' => 17999000,
                'stock' => 25,
            ],
            [
                'name' => 'MacBook Air M3 15"',
                'category' => 'Laptop',
                'price' => 21499000,
                'stock' => 12,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'category' => 'Headphone',
                'price' => 4999000,
                'stock' => 40,
            ],
            [
                'name' => 'iPad Pro M4 11"',
                'category' => 'Tablet',
                'price' => 16999000,
                'stock' => 18,
            ],
            [
                'name' => 'Logitech MX Master 3S',
                'category' => 'Accessories',
                'price' => 1499000,
                'stock' => 60,
            ],
            [
                'name' => 'ASUS ROG Strix G16',
                'category' => 'Laptop',
                'price' => 24999000,
                'stock' => 8,
            ],
            [
                'name' => 'Canon EOS R6 Mark II',
                'category' => 'Camera',
                'price' => 38999000,
                'stock' => 5,
            ],
            [
                'name' => 'Nintendo Switch OLED',
                'category' => 'Gaming Console',
                'price' => 5499000,
                'stock' => 30,
            ],
            [
                'name' => 'Samsung 34" Odyssey G5',
                'category' => 'Monitor',
                'price' => 6499000,
                'stock' => 15,
            ],
            [
                'name' => 'Mechanical Keyboard Keychron K8 Pro',
                'category' => 'Accessories',
                'price' => 1899000,
                'stock' => 45,
            ],
            [
                'name' => 'Google Pixel 9 Pro',
                'category' => 'Smartphone',
                'price' => 15999000,
                'stock' => 20,
            ],
            [
                'name' => 'Dell UltraSharp U2723QE 27"',
                'category' => 'Monitor',
                'price' => 8999000,
                'stock' => 10,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
