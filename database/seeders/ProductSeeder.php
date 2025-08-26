<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = ProductCategory::all();

        $products = [
            // Headphones
            ['name' => 'Sony WH-1000XM4', 'name_ar' => 'سماعة سوني WH-1000XM4', 'category_id' => 1, 'code' => '123456789001', 'purchase_price' => 800.00, 'selling_price' => 1200.00, 'quantity' => 15],
            ['name' => 'AirPods Pro', 'name_ar' => 'ايربودز برو', 'category_id' => 1, 'code' => '123456789002', 'purchase_price' => 600.00, 'selling_price' => 900.00, 'quantity' => 25],
            ['name' => 'Beats Studio3', 'name_ar' => 'بيتس ستوديو 3', 'category_id' => 1, 'code' => '123456789003', 'purchase_price' => 450.00, 'selling_price' => 700.00, 'quantity' => 8],

            // Speakers
            ['name' => 'JBL Charge 5', 'name_ar' => 'سماعة JBL تشارج 5', 'category_id' => 2, 'code' => '123456789004', 'purchase_price' => 300.00, 'selling_price' => 450.00, 'quantity' => 20],
            ['name' => 'Bose SoundLink', 'name_ar' => 'بوز ساوند لينك', 'category_id' => 2, 'code' => '123456789005', 'purchase_price' => 400.00, 'selling_price' => 600.00, 'quantity' => 12],

            // Chargers
            ['name' => 'iPhone Fast Charger', 'name_ar' => 'شاحن آيفون سريع', 'category_id' => 3, 'code' => '123456789006', 'purchase_price' => 50.00, 'selling_price' => 80.00, 'quantity' => 50],
            ['name' => 'Samsung 25W Charger', 'name_ar' => 'شاحن سامسونج 25 وات', 'category_id' => 3, 'code' => '123456789007', 'purchase_price' => 45.00, 'selling_price' => 70.00, 'quantity' => 35],
            ['name' => 'Wireless Charger Pad', 'name_ar' => 'شاحن لاسلكي', 'category_id' => 3, 'code' => '123456789008', 'purchase_price' => 80.00, 'selling_price' => 120.00, 'quantity' => 5],

            // Mouse
            ['name' => 'Logitech MX Master 3', 'name_ar' => 'لوجيتك ماستر 3', 'category_id' => 4, 'code' => '123456789009', 'purchase_price' => 200.00, 'selling_price' => 300.00, 'quantity' => 18],
            ['name' => 'Gaming Mouse RGB', 'name_ar' => 'ماوس ألعاب RGB', 'category_id' => 4, 'code' => '123456789010', 'purchase_price' => 120.00, 'selling_price' => 180.00, 'quantity' => 22],

            // Microphones
            ['name' => 'Blue Yeti', 'name_ar' => 'بلو يتي ميكروفون', 'category_id' => 5, 'code' => '123456789011', 'purchase_price' => 350.00, 'selling_price' => 500.00, 'quantity' => 10],
            ['name' => 'Audio-Technica AT2020', 'name_ar' => 'أوديو تكنيكا AT2020', 'category_id' => 5, 'code' => '123456789012', 'purchase_price' => 280.00, 'selling_price' => 400.00, 'quantity' => 8],

            // Cases
            ['name' => 'iPhone 15 Pro Case', 'name_ar' => 'جراب آيفون 15 برو', 'category_id' => 8, 'code' => '123456789013', 'purchase_price' => 25.00, 'selling_price' => 50.00, 'quantity' => 100],
            ['name' => 'Samsung S24 Ultra Case', 'name_ar' => 'جراب سامسونج S24 الترا', 'category_id' => 8, 'code' => '123456789014', 'purchase_price' => 30.00, 'selling_price' => 55.00, 'quantity' => 75],

            // Power Banks
            ['name' => 'Anker 20000mAh', 'name_ar' => 'أنكر 20000 مللي أمبير', 'category_id' => 16, 'code' => '123456789015', 'purchase_price' => 150.00, 'selling_price' => 220.00, 'quantity' => 30],
            ['name' => 'Xiaomi 10000mAh', 'name_ar' => 'شاومي 10000 مللي أمبير', 'category_id' => 16, 'code' => '123456789016', 'purchase_price' => 80.00, 'selling_price' => 120.00, 'quantity' => 40],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}