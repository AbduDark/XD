
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\ReturnItem;
use App\Models\CashTransfer;
use Carbon\Carbon;

class ReportsSeeder extends Seeder
{
    public function run()
    {
        // إضافة بيانات مبيعات لآخر 30 يوم
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // عدد عشوائي من الفواتير لكل يوم
            $invoicesCount = rand(3, 15);
            
            for ($j = 0; $j < $invoicesCount; $j++) {
                $invoice = Invoice::create([
                    'user_id' => 1,
                    'customer_name' => 'عميل تجريبي ' . rand(1, 100),
                    'customer_phone' => '0100' . rand(1000000, 9999999),
                    'discount' => rand(0, 50),
                    'total' => 0,
                    'notes' => 'فاتورة تجريبية',
                    'created_at' => $date->copy()->addMinutes(rand(0, 1439)),
                ]);

                // إضافة عناصر للفاتورة
                $products = Product::inRandomOrder()->take(rand(1, 5))->get();
                $invoiceTotal = 0;

                foreach ($products as $product) {
                    $quantity = rand(1, 5);
                    $price = $product->selling_price;
                    $totalPrice = $quantity * $price;
                    
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total_price' => $totalPrice,
                    ]);

                    $invoiceTotal += $totalPrice;
                    
                    // تقليل كمية المنتج
                    $product->decrement('quantity', $quantity);
                }

                // تحديث إجمالي الفاتورة
                $invoice->update(['total' => $invoiceTotal - $invoice->discount]);
            }

            // إضافة مرتجعات عشوائية
            if (rand(1, 10) > 7) {
                ReturnItem::create([
                    'product_name' => 'منتج مرتجع تجريبي',
                    'quantity' => rand(1, 3),
                    'amount' => rand(50, 500),
                    'reason' => 'عيب في المنتج',
                    'created_at' => $date->copy()->addMinutes(rand(0, 1439)),
                ]);
            }

            // إضافة تحويلات نقدية عشوائية
            if (rand(1, 10) > 8) {
                CashTransfer::create([
                    'type' => rand(0, 1) ? 'in' : 'out',
                    'amount' => rand(100, 1000),
                    'description' => 'تحويل نقدي تجريبي',
                    'created_at' => $date->copy()->addMinutes(rand(0, 1439)),
                ]);
            }
        }
    }
}
