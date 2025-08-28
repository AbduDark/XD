
@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">تقرير المخزون</h1>
        <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للتقارير
        </a>
    </div>

    <!-- إحصائيات عامة -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">إجمالي قيمة المخزون</h3>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalValue, 2) }} ج.م</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">إجمالي المنتجات</h3>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $products->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">منتجات قليلة المخزون</h3>
            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $lowStockProducts->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">منتجات نفدت</h3>
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $outOfStockProducts->count() }}</p>
        </div>
    </div>

    <!-- منتجات قليلة المخزون -->
    @if($lowStockProducts->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">
                <i class="fas fa-exclamation-triangle ml-2"></i>
                منتجات قليلة المخزون
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">المنتج</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الكمية المتبقية</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الحد الأدنى</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">السعر</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($lowStockProducts as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm text-red-600 dark:text-red-400 font-bold">{{ $product->quantity }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $product->min_quantity }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($product->selling_price, 2) }} ج.م</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- منتجات نفدت -->
    @if($outOfStockProducts->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">
                <i class="fas fa-times-circle ml-2"></i>
                منتجات نفدت من المخزن
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">المنتج</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">السعر</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">آخر تحديث</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($outOfStockProducts as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $product->category->name_ar ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($product->selling_price, 2) }} ج.م</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $product->updated_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- جميع المنتجات -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">جميع المنتجات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">المنتج</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الكمية</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">سعر الشراء</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">سعر البيع</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">القيمة الإجمالية</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الحالة</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $product->quantity <= $product->min_quantity ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $product->category->name_ar ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($product->quantity) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($product->purchase_price, 2) }} ج.م</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ number_format($product->selling_price, 2) }} ج.م</td>
                        <td class="px-6 py-4 text-sm font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($product->quantity * $product->purchase_price, 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($product->quantity == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">نفد</span>
                            @elseif($product->quantity <= $product->min_quantity)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">قليل</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">متوفر</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
