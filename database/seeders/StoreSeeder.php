
<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run()
    {
        // إنشاء متجر افتراضي للمدير العام
        $superAdmin = User::where('role', 'super_admin')->first();
        
        if ($superAdmin) {
            $store = Store::create([
                'name' => 'Al-Hussiny Store',
                'name_ar' => 'متجر الحسيني',
                'slug' => 'al-hussiny-store',
                'description' => 'Main store for electronics and mobile accessories',
                'description_ar' => 'المتجر الرئيسي للإلكترونيات وإكسسوارات الهواتف',
                'owner_id' => $superAdmin->id,
                'phone' => '+966501234567',
                'address' => 'King Fahd Road, Riyadh',
                'address_ar' => 'طريق الملك فهد، الرياض',
                'email' => 'store@alhussiny.com',
                'is_active' => true,
                'tax_rate' => 15.00,
                'currency' => 'SAR',
            ]);

            // ربط المدير العام بالمتجر
            $store->users()->attach($superAdmin->id, [
                'role' => 'owner',
                'is_active' => true
            ]);
        }

        // إنشاء متاجر إضافية
        $owners = User::where('role', 'admin')->take(3)->get();
        
        foreach ($owners as $owner) {
            $store = Store::create([
                'name' => 'Store ' . $owner->name,
                'name_ar' => 'متجر ' . $owner->name,
                'slug' => 'store-' . strtolower(str_replace(' ', '-', $owner->name)),
                'description' => 'Electronics store managed by ' . $owner->name,
                'description_ar' => 'متجر إلكترونيات يديره ' . $owner->name,
                'owner_id' => $owner->id,
                'phone' => '+966' . rand(500000000, 599999999),
                'address' => 'Commercial Street, ' . ['Riyadh', 'Jeddah', 'Dammam'][rand(0, 2)],
                'address_ar' => 'شارع تجاري، ' . ['الرياض', 'جدة', 'الدمام'][rand(0, 2)],
                'email' => strtolower(str_replace(' ', '', $owner->name)) . '@store.com',
                'is_active' => true,
                'tax_rate' => 15.00,
                'currency' => 'SAR',
            ]);

            // ربط المالك بالمتجر
            $store->users()->attach($owner->id, [
                'role' => 'owner',
                'is_active' => true
            ]);
        }
    }
}
