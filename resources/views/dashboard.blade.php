
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-3xl font-bold mb-8">لوحة التحكم - الحسيني ستور</h1>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">مبيعات اليوم</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($stats['today_sales']) }} جنيه</p>
                                <p class="text-sm text-blue-600 dark:text-blue-400">{{ $stats['today_sales_count'] }} فاتورة</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-100 dark:bg-green-900 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">مبيعات الشهر</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ number_format($stats['month_sales']) }} جنيه</p>
                                <p class="text-sm text-green-600 dark:text-green-400">{{ $stats['month_sales_count'] }} فاتورة</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">إجمالي المنتجات</p>
                                <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $stats['total_products'] }}</p>
                                <p class="text-sm text-yellow-600 dark:text-yellow-400">منتج</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-100 dark:bg-red-900 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.232 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="mr-4">
                                <p class="text-sm font-medium text-red-600 dark:text-red-400">منتجات قاربت النفاد</p>
                                <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ $stats['low_stock_products'] }}</p>
                                <p class="text-sm text-red-600 dark:text-red-400">منتج</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">مبيعات آخر 7 أيام</h3>
                        <canvas id="salesChart" width="400" height="200"></canvas>
                    </div>

                    <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">المنتجات قاربت النفاد</h3>
                        <div class="space-y-4">
                            @foreach($lowStockProducts as $product)
                            <div class="flex justify-between items-center p-3 bg-red-50 dark:bg-red-900 rounded">
                                <div>
                                    <p class="font-medium">{{ $product->name_ar }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->category->name_ar }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="font-bold text-red-600 dark:text-red-400">{{ $product->quantity }}</p>
                                    <p class="text-xs text-gray-500">متبقي</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('invoices.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg text-center">
                        إنشاء فاتورة جديدة
                    </a>
                    <a href="{{ route('products.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-center">
                        إضافة منتج جديد
                    </a>
                    <a href="{{ route('repairs.create') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-lg text-center">
                        تسجيل عملية صيانة
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesChart->pluck('date_ar')),
            datasets: [{
                label: 'المبيعات (جنيه)',
                data: @json($salesChart->pluck('sales')),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
