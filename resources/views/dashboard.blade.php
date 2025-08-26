
@extends('layouts.app')

@section('page-title', 'لوحة التحكم الرئيسية')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="card-glass p-8 text-center">
        <div class="flex items-center justify-center mb-6">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-line text-white text-3xl"></i>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">مرحباً بك، {{ Auth::user()->name }}</h1>
        <p class="text-gray-600 dark:text-gray-400">إليك نظرة عامة على أداء محلك اليوم</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sales -->
        <div class="stats-card hover:scale-105 transition-transform cursor-pointer" onclick="showSalesDetails()">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">إجمالي المبيعات</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format(15420.50, 2) }} ر.س</p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-2">
                        <i class="fas fa-arrow-up ml-1"></i>
                        زيادة 12% عن الأمس
                    </p>
                </div>
                <div class="text-green-500 text-4xl">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>

        <!-- Products Count -->
        <div class="stats-card hover:scale-105 transition-transform cursor-pointer" onclick="window.location.href='{{ route('products.index') }}'">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">عدد المنتجات</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\Product::count() }}</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400 mt-2">
                        <i class="fas fa-plus ml-1"></i>
                        {{ \App\Models\Product::whereDate('created_at', today())->count() }} منتج جديد اليوم
                    </p>
                </div>
                <div class="text-blue-500 text-4xl">
                    <i class="fas fa-mobile-alt"></i>
                </div>
            </div>
        </div>

        <!-- Invoices Count -->
        <div class="stats-card hover:scale-105 transition-transform cursor-pointer" onclick="window.location.href='{{ route('invoices.index') }}'">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">الفواتير اليوم</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ \App\Models\Invoice::whereDate('created_at', today())->count() }}</p>
                    <p class="text-sm text-purple-600 dark:text-purple-400 mt-2">
                        <i class="fas fa-file-invoice ml-1"></i>
                        إجمالي {{ \App\Models\Invoice::count() }} فاتورة
                    </p>
                </div>
                <div class="text-purple-500 text-4xl">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>

        <!-- Repairs Count -->
        <div class="stats-card hover:scale-105 transition-transform cursor-pointer" onclick="window.location.href='{{ route('repairs.index') }}'">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">طلبات الصيانة</p>
                    <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ \App\Models\Repair::where('status', 'pending')->count() }}</p>
                    <p class="text-sm text-orange-600 dark:text-orange-400 mt-2">
                        <i class="fas fa-tools ml-1"></i>
                        قيد الانتظار
                    </p>
                </div>
                <div class="text-orange-500 text-4xl">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Sales Chart -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">المبيعات الأسبوعية</h3>
            <canvas id="salesChart" height="300"></canvas>
        </div>

        <!-- Products Distribution -->
        <div class="card-glass p-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">توزيع المنتجات</h3>
            <canvas id="productsChart" height="300"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Invoices -->
        <div class="card-glass p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">آخر الفواتير</h3>
                <a href="{{ route('invoices.index') }}" class="btn-gradient px-4 py-2 text-sm">
                    عرض الكل
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse(\App\Models\Invoice::latest()->take(5)->get() as $invoice)
                <div class="flex items-center justify-between p-4 bg-white bg-opacity-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-xl border border-gray-200 dark:border-gray-600 border-opacity-30">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-invoice text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">فاتورة #{{ $invoice->id }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $invoice->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-green-600 dark:text-green-400">{{ number_format($invoice->total, 2) }} ر.س</p>
                        <span class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-200 dark:bg-green-800 dark:text-green-200 rounded-full">
                            مكتملة
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-file-invoice text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">لا توجد فواتير حتى الآن</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Repairs -->
        <div class="card-glass p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">آخر طلبات الصيانة</h3>
                <a href="{{ route('repairs.index') }}" class="btn-gradient px-4 py-2 text-sm">
                    عرض الكل
                    <i class="fas fa-arrow-left mr-2"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse(\App\Models\Repair::latest()->take(5)->get() as $repair)
                <div class="flex items-center justify-between p-4 bg-white bg-opacity-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-xl border border-gray-200 dark:border-gray-600 border-opacity-30">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-tools text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white">{{ $repair->customer_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $repair->device_type }}</p>
                        </div>
                    </div>
                    <div class="text-left">
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                            @if($repair->status === 'pending') text-yellow-800 bg-yellow-200 dark:bg-yellow-800 dark:text-yellow-200
                            @elseif($repair->status === 'in_progress') text-blue-800 bg-blue-200 dark:bg-blue-800 dark:text-blue-200
                            @elseif($repair->status === 'completed') text-green-800 bg-green-200 dark:bg-green-800 dark:text-green-200
                            @else text-red-800 bg-red-200 dark:bg-red-800 dark:text-red-200
                            @endif">
                            @if($repair->status === 'pending') قيد الانتظار
                            @elseif($repair->status === 'in_progress') قيد التنفيذ
                            @elseif($repair->status === 'completed') مكتملة
                            @else ملغاة
                            @endif
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-tools text-gray-400 text-4xl mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">لا توجد طلبات صيانة حتى الآن</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card-glass p-6">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-6">الإجراءات السريعة</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('invoices.create') }}" class="quick-action-btn group">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus text-white text-2xl"></i>
                </div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">فاتورة جديدة</p>
            </a>

            <a href="{{ route('products.create') }}" class="quick-action-btn group">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-mobile-alt text-white text-2xl"></i>
                </div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">منتج جديد</p>
            </a>

            <a href="{{ route('repairs.create') }}" class="quick-action-btn group">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-tools text-white text-2xl"></i>
                </div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">طلب صيانة</p>
            </a>

            <a href="{{ route('reports.index') }}" class="quick-action-btn group">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-bar text-white text-2xl"></i>
                </div>
                <p class="text-sm font-medium text-gray-800 dark:text-white">التقارير</p>
            </a>
        </div>
    </div>
</div>

<!-- Sales Details Modal -->
<div id="salesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="card-glass max-w-md w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">تفاصيل المبيعات</h3>
            <button onclick="closeSalesModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">مبيعات اليوم:</span>
                <span class="font-bold text-green-600 dark:text-green-400">{{ number_format(3450.00, 2) }} ر.س</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">مبيعات الأسبوع:</span>
                <span class="font-bold text-green-600 dark:text-green-400">{{ number_format(15420.50, 2) }} ر.س</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">مبيعات الشهر:</span>
                <span class="font-bold text-green-600 dark:text-green-400">{{ number_format(45890.75, 2) }} ر.س</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600 dark:text-gray-400">عدد الفواتير اليوم:</span>
                <span class="font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\Invoice::whereDate('created_at', today())->count() }}</span>
            </div>
        </div>
    </div>
</div>

<style>
.quick-action-btn {
    @apply p-6 text-center bg-white bg-opacity-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-xl border border-gray-200 dark:border-gray-600 border-opacity-30 hover:bg-opacity-70 transition-all cursor-pointer;
}
</style>

<script>
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'],
            datasets: [{
                label: 'المبيعات (ر.س)',
                data: [1200, 1900, 3000, 2500, 2200, 3000, 3450],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
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
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });

    // Products Distribution Chart
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    new Chart(productsCtx, {
        type: 'doughnut',
        data: {
            labels: ['جوالات', 'إكسسوارات', 'قطع غيار', 'أخرى'],
            datasets: [{
                data: [45, 25, 20, 10],
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(245, 158, 11)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

function showSalesDetails() {
    document.getElementById('salesModal').classList.remove('hidden');
    document.getElementById('salesModal').classList.add('flex');
}

function closeSalesModal() {
    document.getElementById('salesModal').classList.add('hidden');
    document.getElementById('salesModal').classList.remove('flex');
}
</script>
@endsection
