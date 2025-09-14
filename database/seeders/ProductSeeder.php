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
        
        // Get available stores or create default store if none exist
        $stores = \App\Models\Store::all();
        if ($stores->isEmpty()) {
            $defaultStore = \App\Models\Store::create([
                'name' => 'المتجر الافتراضي',
                'name_ar' => 'المتجر الافتراضي',
                'slug' => 'default-store',
                'description' => 'المتجر الافتراضي للنظام',
                'description_ar' => 'المتجر الافتراضي للنظام',
                'owner_id' => 1, // Assuming super admin has id 1
                'phone' => '+966500000000',
                'address' => 'الرياض، السعودية',
                'address_ar' => 'الرياض، السعودية',
                'email' => 'default@store.com',
                'tax_rate' => 15.00,
                'currency' => 'SAR',
                'is_active' => true,
            ]);
            $stores = collect([$defaultStore]);
        }

        $products = [
            ['name' => 'iPhone 15 Pro', 'name_ar' => 'آيفون 15 برو', 'code' => 'IP15P', 'purchase_price' => 45000, 'selling_price' => 50000],
            ['name' => 'Samsung Galaxy S24', 'name_ar' => 'سامسونج جالاكسي S24', 'code' => 'SGS24', 'purchase_price' => 35000, 'selling_price' => 40000],
            ['name' => 'iPhone Case', 'name_ar' => 'جراب آيفون', 'code' => 'IPC001', 'purchase_price' => 150, 'selling_price' => 250],
            ['name' => 'Phone Charger', 'name_ar' => 'شاحن هاتف', 'code' => 'CHG001', 'purchase_price' => 50, 'selling_price' => 100],
            ['name' => 'Screen Protector', 'name_ar' => 'واقي شاشة', 'code' => 'SP001', 'purchase_price' => 25, 'selling_price' => 50],
            ['name' => 'Bluetooth Headphones', 'name_ar' => 'سماعات بلوتوث', 'code' => 'BH001', 'purchase_price' => 200, 'selling_price' => 350],
            ['name' => 'Power Bank', 'name_ar' => 'باور بنك', 'code' => 'PB001', 'purchase_price' => 150, 'selling_price' => 250],
            ['name' => 'USB Cable', 'name_ar' => 'كابل USB', 'code' => 'USB001', 'purchase_price' => 30, 'selling_price' => 60],
        ];

        foreach ($products as $product) {
            Product::create([
                'name' => $product['name'],
                'name_ar' => $product['name_ar'],
                'code' => $product['code'],
                'category_id' => $categories->random()->id,
                'store_id' => $stores->random()->id,
                'purchase_price' => $product['purchase_price'],
                'selling_price' => $product['selling_price'],
                'quantity' => rand(10, 100),
                'min_quantity' => rand(5, 15),
                'description' => 'وصف المنتج: ' . $product['name_ar'],
            ]);
        }
    }
}
