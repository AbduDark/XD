
@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6 text-white">
                <h1 class="text-4xl font-bold mb-2">مرحباً بك في الحسيني ستور</h1>
                <p class="text-xl opacity-90">نظام إدارة متكامل للمبيعات والمخزون</p>
                <div class="mt-4 text-sm opacity-75">
                    <span>تاريخ اليوم: {{ now()->format('Y-m-d') }}</span>
                    <span class="mx-2">|</span>
                    <span>المستخدم: {{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">مبيعات اليوم</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['today_sales']) }}</p>
                            <p class="text-sm text-blue-600 dark:text-blue-400">{{ $stats['today_sales_count'] }} فاتورة</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">مبيعات الشهر</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['month_sales']) }}</p>
                            <p class="text-sm text-green-600 dark:text-green-400">{{ $stats['month_sales_count'] }} فاتورة</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">إجمالي المنتجات</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_products'] }}</p>
                            <p class="text-sm text-yellow-600 dark:text-yellow-400">منتج</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg border-l-4 border-red-500">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.232 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mr-4 flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">منتجات قاربت النفاد</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['low_stock_products'] }}</p>
                            <p class="text-sm text-red-600 dark:text-red-400">منتج</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Sales Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">مبيعات آخر 7 أيام</h3>
                </div>
                <div class="p-6">
                    <canvas id="salesChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">المنتجات قاربت النفاد</h3>
                </div>
                <div class="p-6 max-h-80 overflow-y-auto">
                    @if($lowStockProducts->count() > 0)
                        <div class="space-y-3">
                            @foreach($lowStockProducts as $product)
                            <div class="flex justify-between items-center p-4 bg-red-50 dark:bg-red-900 rounded-lg border border-red-200 dark:border-red-700">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $product->name_ar }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $product->category->name_ar }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $product->barcode }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $product->quantity }}</p>
                                    <p class="text-xs text-gray-500">متبقي</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500 dark:text-gray-400">جميع المنتجات متوفرة بكميات كافية</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Invoices -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">آخر الفواتير</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach(\App\Models\Invoice::latest()->take(5)->get() as $invoice)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->customer_name }}</p>
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-green-600 dark:text-green-400">{{ number_format($invoice->final_amount) }} جنيه</p>
                                <p class="text-xs text-gray-500">{{ $invoice->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Repairs -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">آخر عمليات الصيانة</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach(\App\Models\Repair::latest()->take(5)->get() as $repair)
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $repair->repair_number }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $repair->device_type }}</p>
                            </div>
                            <div class="text-center">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($repair->status == 'completed') bg-green-100 text-green-800
                                    @elseif($repair->status == 'in_progress') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($repair->status == 'completed') مكتملة
                                    @elseif($repair->status == 'in_progress') قيد العمل
                                    @elseif($repair->status == 'pending') في الانتظار
                                    @else مسلمة @endif
                                </span>
                                <p class="text-xs text-gray-500 mt-1">{{ $repair->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">الإجراءات السريعة</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('invoices.create') }}" class="group relative bg-blue-500 hover:bg-blue-600 text-white font-bold py-6 px-6 rounded-lg text-center transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        إنشاء فاتورة جديدة
                    </a>
                    <a href="{{ route('products.create') }}" class="group relative bg-green-500 hover:bg-green-600 text-white font-bold py-6 px-6 rounded-lg text-center transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        إضافة منتج جديد
                    </a>
                    <a href="{{ route('repairs.create') }}" class="group relative bg-purple-500 hover:bg-purple-600 text-white font-bold py-6 px-6 rounded-lg text-center transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        تسجيل عملية صيانة
                    </a>
                    <a href="{{ route('reports.index') }}" class="group relative bg-orange-500 hover:bg-orange-600 text-white font-bold py-6 px-6 rounded-lg text-center transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        عرض التقارير
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
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    ticks: {
                        font: {
                            family: 'Tajawal'
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)',
                    },
                    ticks: {
                        font: {
                            family: 'Tajawal'
                        }
                    }
                }
            },
            elements: {
                point: {
                    hoverBackgroundColor: 'rgb(59, 130, 246)',
                }
            }
        }
    });
</script>
@endsection
