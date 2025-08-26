
<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Headphones', 'name_ar' => 'سماعات أذن'],
            ['name' => 'Speakers', 'name_ar' => 'سماعات'],
            ['name' => 'Chargers', 'name_ar' => 'شاحن'],
            ['name' => 'Mouse', 'name_ar' => 'ماوس'],
            ['name' => 'Microphones', 'name_ar' => 'ميكات'],
            ['name' => 'Lights', 'name_ar' => 'ليدر'],
            ['name' => 'OTG', 'name_ar' => 'اوتو جي'],
            ['name' => 'Cases', 'name_ar' => 'جراب'],
            ['name' => 'Micro USB', 'name_ar' => 'وصلة مايكرو'],
            ['name' => 'USB-C', 'name_ar' => 'وصلة تايب'],
            ['name' => 'Accessories', 'name_ar' => 'إكسسوار'],
            ['name' => 'Stands', 'name_ar' => 'ستاند'],
            ['name' => 'Screen Protectors', 'name_ar' => 'سكرينة'],
            ['name' => 'Airpods', 'name_ar' => 'ايربودز'],
            ['name' => 'Computers', 'name_ar' => 'كمبيوتر'],
            ['name' => 'Power Banks', 'name_ar' => 'باور بنك'],
            ['name' => 'Others', 'name_ar' => 'أخرى'],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
