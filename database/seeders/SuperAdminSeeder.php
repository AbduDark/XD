<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User
        $superAdmin = User::firstOrCreate([
            'email' => 'admin@admin.com'
        ], [
            'name' => 'مدير النظام',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create sample users
        $user1 = User::firstOrCreate([
            'email' => 'user1@example.com'
        ], [
            'name' => 'أحمد محمد',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $user2 = User::firstOrCreate([
            'email' => 'user2@example.com'
        ], [
            'name' => 'فاطمة علي',
            'password' => Hash::make('password'),
            'role' => 'employee',
            'is_active' => true,
        ]);

        // Create sample stores
        $store1 = Store::firstOrCreate([
            'slug' => 'electronics-store'
        ], [
            'name' => 'متجر الإلكترونيات',
            'name_ar' => 'متجر الإلكترونيات',
            'description' => 'متجر متخصص في بيع الأجهزة الإلكترونية',
            'description_ar' => 'متجر متخصص في بيع الأجهزة الإلكترونية',
            'owner_id' => $user1->id,
            'phone' => '+966501234567',
            'address' => 'الرياض، السعودية',
            'address_ar' => 'الرياض، السعودية',
            'email' => 'electronics@example.com',
            'tax_rate' => 15.00,
            'currency' => 'SAR',
            'is_active' => true,
        ]);

        $store2 = Store::firstOrCreate([
            'slug' => 'clothing-store'
        ], [
            'name' => 'متجر الملابس',
            'name_ar' => 'متجر الملابس',
            'description' => 'متجر متخصص في بيع الملابس العصرية',
            'description_ar' => 'متجر متخصص في بيع الملابس العصرية',
            'owner_id' => $user2->id,
            'phone' => '+966507654321',
            'address' => 'جدة، السعودية',
            'address_ar' => 'جدة، السعودية',
            'email' => 'clothing@example.com',
            'tax_rate' => 15.00,
            'currency' => 'SAR',
            'is_active' => true,
        ]);

        // Create product categories
        $category1 = ProductCategory::firstOrCreate([
            'name' => 'الإلكترونيات'
        ], [
            'name_ar' => 'الإلكترونيات',
        ]);

        $category2 = ProductCategory::firstOrCreate([
            'name' => 'الملابس'
        ], [
            'name_ar' => 'الملابس',
        ]);

        // Create sample products for store 1
        Product::firstOrCreate([
            'code' => 'ELC001',
            'store_id' => $store1->id
        ], [
            'name' => 'جهاز كمبيوتر محمول',
            'name_ar' => 'جهاز كمبيوتر محمول',
            'category_id' => $category1->id,
            'purchase_price' => 2000.00,
            'selling_price' => 2500.00,
            'quantity' => 10,
            'min_quantity' => 2,
            'description' => 'جهاز كمبيوتر محمول عالي الأداء',
        ]);

        Product::firstOrCreate([
            'code' => 'ELC002',
            'store_id' => $store1->id
        ], [
            'name' => 'هاتف ذكي',
            'name_ar' => 'هاتف ذكي',
            'category_id' => $category1->id,
            'purchase_price' => 1200.00,
            'selling_price' => 1500.00,
            'quantity' => 25,
            'min_quantity' => 5,
            'description' => 'هاتف ذكي بمواصفات عالية',
        ]);

        // Create sample products for store 2
        Product::firstOrCreate([
            'code' => 'CLT001',
            'store_id' => $store2->id
        ], [
            'name' => 'قميص رجالي',
            'name_ar' => 'قميص رجالي',
            'category_id' => $category2->id,
            'purchase_price' => 80.00,
            'selling_price' => 120.00,
            'quantity' => 50,
            'min_quantity' => 10,
            'description' => 'قميص رجالي أنيق',
        ]);

        // Create sample invoices
        $invoice1 = Invoice::firstOrCreate([
            'invoice_number' => 'INV-2025-S1-000001'
        ], [
            'user_id' => $user1->id,
            'store_id' => $store1->id,
            'customer_name' => 'عبدالله محمد',
            'customer_phone' => '+966501111111',
            'subtotal' => 2500.00,
            'discount' => 0.00,
            'tax' => 375.00,
            'total' => 2875.00,
            'status' => 'paid',
        ]);

        echo "تم إنشاء البيانات التجريبية بنجاح:\n";
        echo "- مدير النظام: admin@admin.com (كلمة المرور: password)\n";
        echo "- المستخدمون: user1@example.com, user2@example.com (كلمة المرور: password)\n";
        echo "- المتاجر: متجر الإلكترونيات، متجر الملابس\n";
        echo "- المنتجات والفواتير التجريبية\n";
    }
}
