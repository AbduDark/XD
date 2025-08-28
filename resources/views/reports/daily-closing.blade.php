
@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">التقفيل اليومي</h1>
        <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة للتقارير
        </a>
    </div>

    <!-- فلتر التاريخ -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">التاريخ</label>
                <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" 
                       class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white text-gray-900">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search ml-2"></i>
                عرض التقفيل
            </button>
            <button type="button" onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-print ml-2"></i>
                طباعة
            </button>
        </form>
    </div>

    <!-- ملخص التقفيل -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">إجمالي المبيعات</h3>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($sales, 2) }} ج.م</p>
            <p class="text-sm text-gray-500">عدد الفواتير: {{ $invoicesCount }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">إجمالي الأرباح</h3>
            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($profit, 2) }} ج.م</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">المرتجعات</h3>
            <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ number_format($returns, 2) }} ج.م</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">صافي النقد</h3>
            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($netCash, 2) }} ج.م</p>
        </div>
    </div>

    <!-- تفاصيل التحويلات النقدية -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">التحويلات النقدية</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-300">نقد داخل</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">{{ number_format($cashIn, 2) }} ج.م</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-300">نقد خارج</span>
                    <span class="font-semibold text-red-600 dark:text-red-400">{{ number_format($cashOut, 2) }} ج.م</span>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-900 dark:text-white">الرصيد</span>
                        <span class="font-bold text-lg {{ $cashIn - $cashOut >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ number_format($cashIn - $cashOut, 2) }} ج.م
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">ملخص الحسابات</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-300">المبيعات</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">+{{ number_format($sales, 2) }} ج.م</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-300">المرتجعات</span>
                    <span class="font-semibold text-red-600 dark:text-red-400">-{{ number_format($returns, 2) }} ج.م</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-300">نقد إضافي</span>
                    <span class="font-semibold text-blue-600 dark:text-blue-400">+{{ number_format($cashIn, 2) }} ج.م</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 dark:text-gray-300">نقد مسحوب</span>
                    <span class="font-semibold text-orange-600 dark:text-orange-400">-{{ number_format($cashOut, 2) }} ج.م</span>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-600 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-900 dark:text-white">الإجمالي النهائي</span>
                        <span class="font-bold text-xl text-indigo-600 dark:text-indigo-400">
                            {{ number_format($netCash, 2) }} ج.م
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- المنتجات المباعة -->
    @if($soldProducts->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">المنتجات المباعة</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">المنتج</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الكمية المباعة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">سعر الوحدة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-900 dark:text-white uppercase tracking-wider">الإجمالي</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($soldProducts as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            {{ $item->product->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ $item->total_sold }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            {{ number_format($item->product->selling_price, 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($item->total_sold * $item->product->selling_price, 2) }} ج.م
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow text-center">
        <i class="fas fa-box-open text-gray-400 text-5xl mb-4"></i>
        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400 mb-2">لا توجد مبيعات</h3>
        <p class="text-gray-500 dark:text-gray-500">لم يتم بيع أي منتج في هذا التاريخ</p>
    </div>
    @endif
</div>

<style>
@media print {
    .no-print { display: none !important; }
    .print-break { page-break-after: always; }
    body { background: white !important; }
    .dark\:bg-gray-800 { background: white !important; }
    .dark\:text-white { color: black !important; }
    .text-gray-900 { color: black !important; }
}
</style>
@endsection
