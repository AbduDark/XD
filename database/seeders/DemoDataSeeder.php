<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo users with their stores
        $users = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@store.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'store_name' => 'متجر أحمد للإلكترونيات',
                'store_name_ar' => 'متجر أحمد للإلكترونيات'
            ],
            [
                'name' => 'فاطمة علي',
                'email' => 'fatima@store.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'store_name' => 'متجر فاطمة للأزياء',
                'store_name_ar' => 'متجر فاطمة للأزياء'
            ],
            [
                'name' => 'محمد السعيد',
                'email' => 'mohammed@store.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'store_name' => 'متجر محمد للهواتف',
                'store_name_ar' => 'متجر محمد للهواتف'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
                'role' => $userData['role'],
                'is_active' => true
            ]);

            $store = Store::create([
                'name' => $userData['store_name'],
                'name_ar' => $userData['store_name_ar'],
                'slug' => Str::slug($userData['store_name'] . '-' . $user->id),
                'owner_id' => $user->id,
                'is_active' => true,
                'tax_rate' => 15.0,
                'currency' => 'SAR',
                'phone' => '+966501234567',
                'address' => 'الرياض، المملكة العربية السعودية',
                'address_ar' => 'الرياض، المملكة العربية السعودية'
            ]);

            // Create categories for each store
            $categories = [
                ['name' => 'إلكترونيات', 'name_ar' => 'إلكترونيات'],
                ['name' => 'ملابس', 'name_ar' => 'ملابس'],
                ['name' => 'أحذية', 'name_ar' => 'أحذية'],
                ['name' => 'إكسسوارات', 'name_ar' => 'إكسسوارات']
            ];

            foreach ($categories as $categoryData) {
                $category = ProductCategory::create([
                    'name' => $categoryData['name'],
                    'name_ar' => $categoryData['name_ar'],
                    'store_id' => $store->id
                ]);

                // Create products for each category
                for ($i = 1; $i <= 5; $i++) {
                    Product::create([
                        'name' => $categoryData['name'] . ' رقم ' . $i,
                        'name_ar' => $categoryData['name_ar'] . ' رقم ' . $i,
                        'barcode' => 'DEMO' . $store->id . $category->id . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'category_id' => $category->id,
                        'store_id' => $store->id,
                        'cost_price' => rand(50, 500),
                        'selling_price' => rand(100, 800),
                        'quantity' => rand(10, 100),
                        'min_quantity' => rand(5, 10),
                        'description' => 'وصف المنتج ' . $categoryData['name'] . ' رقم ' . $i,
                        'description_ar' => 'وصف المنتج ' . $categoryData['name_ar'] . ' رقم ' . $i
                    ]);
                }
            }
        }
    }
}
