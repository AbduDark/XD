
@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">تقرير قيمة المخزون</h1>
        <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للتقارير
        </a>
    </div>

    <!-- إحصائيات القيمة -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">القيمة بسعر البيع</h3>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalSellingValue, 2) }} ج.م</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">القيمة بسعر الشراء</h3>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($totalPurchaseValue, 2) }} ج.م</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">الربح المتوقع</h3>
            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($expectedProfit, 2) }} ج.م</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">إجمالي المنتجات</h3>
            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $products->count() }}</p>
        </div>
    </div>

    <!-- إحصائيات حسب الفئات -->
    @if($categoryStats->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">إحصائيات حسب الفئات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الفئة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">عدد المنتجات</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">إجمالي الكمية</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">قيمة البيع</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">قيمة الشراء</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الربح المتوقع</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($categoryStats as $categoryName => $stats)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $categoryName ?? 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $stats['products_count'] }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ number_format($stats['total_quantity']) }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($stats['selling_value'], 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($stats['purchase_value'], 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-purple-600 dark:text-purple-400">
                            {{ number_format($stats['selling_value'] - $stats['purchase_value'], 2) }} ج.م
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- تفاصيل جميع المنتجات -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">تفاصيل جميع المنتجات</h3>
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
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">قيمة الشراء</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">قيمة البيع</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الربح المتوقع</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $product->quantity <= $product->min_quantity ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $product->name }}
                            @if($product->quantity == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 mr-2">نفد</span>
                            @elseif($product->quantity <= $product->min_quantity)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 mr-2">قليل</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $product->category->name_ar ?? 'غير محدد' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ number_format($product->quantity) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ number_format($product->purchase_price, 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ number_format($product->selling_price, 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($product->quantity * $product->purchase_price, 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($product->quantity * $product->selling_price, 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-purple-600 dark:text-purple-400">
                            {{ number_format($product->quantity * ($product->selling_price - $product->purchase_price), 2) }} ج.م
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
