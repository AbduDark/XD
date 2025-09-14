
@extends('layouts.app')

@section('title', 'لوحة تحكم الأدمن')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with Actions -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    لوحة تحكم النظام
                </h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">إدارة شاملة للنظام والمتاجر والمستخدمين</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <button onclick="refreshDashboard()" class="btn-primary-modern flex items-center space-x-2 space-x-reverse">
                    <i class="fas fa-sync-alt"></i>
                    <span>تحديث البيانات</span>
                </button>
                <a href="{{ route('admin.stores.create') }}" class="btn-success-modern flex items-center space-x-2 space-x-reverse">
                    <i class="fas fa-plus"></i>
                    <span>متجر جديد</span>
                </a>
            </div>
        </div>

        <!-- Modern Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Stores -->
            <div class="card-modern p-6 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">إجمالي المتاجر</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_stores'] }}</div>
                        <div class="text-sm text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ $stats['active_stores'] }} نشط
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-store text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">نشط</span>
                        <span class="font-medium">{{ number_format(($stats['active_stores'] / max($stats['total_stores'], 1)) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-2">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: {{ ($stats['active_stores'] / max($stats['total_stores'], 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="card-modern p-6 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">إجمالي المستخدمين</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_users'] }}</div>
                        <div class="text-sm text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ $stats['active_users'] }} نشط
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">نشط</span>
                        <span class="font-medium">{{ number_format(($stats['active_users'] / max($stats['total_users'], 1)) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 mt-2">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full" style="width: {{ ($stats['active_users'] / max($stats['total_users'], 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="card-modern p-6 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">إيرادات الشهر</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['monthly_revenue'], 0) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $stats['total_invoices'] }} فاتورة
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center space-x-2 space-x-reverse text-xs">
                    <div class="flex items-center text-emerald-600 dark:text-emerald-400">
                        <i class="fas fa-arrow-up text-xs mr-1"></i>
                        <span>12.5%</span>
                    </div>
                    <span class="text-gray-500 dark:text-gray-400">من الشهر الماضي</span>
                </div>
            </div>

            <!-- System Health -->
            <div class="card-modern p-6 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">حالة النظام</div>
                        <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">ممتاز</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            جميع الخدمات تعمل
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-heart text-white text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2 space-x-reverse">
                    <div class="flex-1 bg-emerald-100 dark:bg-emerald-900 rounded-lg p-2 text-center">
                        <div class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">قاعدة البيانات</div>
                        <div class="text-emerald-700 dark:text-emerald-300 text-xs">متصلة</div>
                    </div>
                    <div class="flex-1 bg-emerald-100 dark:bg-emerald-900 rounded-lg p-2 text-center">
                        <div class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">الخادم</div>
                        <div class="text-emerald-700 dark:text-emerald-300 text-xs">يعمل</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Management Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Quick Actions -->
            <div class="card-modern overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-purple-50 dark:from-gray-800 dark:to-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-bolt text-blue-600 mr-3"></i>
                        إجراءات سريعة
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.stores.create') }}" class="flex items-center p-4 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors group">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-store text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="mr-4">
                            <div class="font-medium text-gray-900 dark:text-white">إضافة متجر</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">إنشاء متجر جديد</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors group">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-user-plus text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                        <div class="mr-4">
                            <div class="font-medium text-gray-900 dark:text-white">إضافة مستخدم</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">إنشاء حساب جديد</div>
                        </div>
                    </a>
                    
                    <a href="{{ route('admin.dashboard.system-health') }}" class="flex items-center p-4 rounded-lg bg-amber-50 hover:bg-amber-100 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors group">
                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-chart-pie text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div class="mr-4">
                            <div class="font-medium text-gray-900 dark:text-white">تقارير متقدمة</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">إحصائيات مفصلة</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="card-modern overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-gray-800 dark:to-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-clock text-emerald-600 mr-3"></i>
                        الأنشطة الأخيرة
                    </h3>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div class="space-y-4">
                        @forelse($recentActivities as $activity)
                        <div class="flex items-start space-x-4 space-x-reverse p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex-shrink-0">
                                @if($activity['type'] === 'store_created')
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-store text-blue-600 dark:text-blue-400 text-sm"></i>
                                </div>
                                @else
                                <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-emerald-600 dark:text-emerald-400 text-sm"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity['message'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    بواسطة {{ $activity['user'] }} • {{ $activity['time'] }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i class="fas fa-clock text-gray-300 dark:text-gray-600 text-3xl mb-3"></i>
                            <p class="text-gray-500 dark:text-gray-400">لا توجد أنشطة حديثة</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- System Performance -->
            <div class="card-modern overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-gray-800 dark:to-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-tachometer-alt text-purple-600 mr-3"></i>
                        أداء النظام
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">استخدام الذاكرة</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">68%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full" style="width: 68%"></div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">استخدام وحدة المعالجة</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">45%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">مساحة التخزين</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">32%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full" style="width: 32%"></div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 mr-2"></i>
                            <span class="text-sm font-medium text-emerald-700 dark:text-emerald-300">الأداء ممتاز</span>
                        </div>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">جميع الخدمات تعمل بكفاءة عالية</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Monthly Chart -->
            <div class="card-modern overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-chart-area text-blue-600 mr-3"></i>
                        الإحصائيات الشهرية
                    </h3>
                </div>
                <div class="p-6">
                    <canvas id="monthlyChart" class="max-h-80"></canvas>
                </div>
            </div>

            <!-- Store Performance -->
            <div class="card-modern overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-store text-emerald-600 mr-3"></i>
                        أداء المتاجر
                    </h3>
                </div>
                <div class="p-6">
                    <canvas id="storePerformanceChart" class="max-h-80"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'المتاجر الجديدة',
                data: monthlyData.map(item => item.stores),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }, {
                label: 'المستخدمون الجدد',
                data: monthlyData.map(item => item.users),
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(16, 185, 129)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
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

    // Store Performance Chart
    const storeCtx = document.getElementById('storePerformanceChart').getContext('2d');
    
    new Chart(storeCtx, {
        type: 'doughnut',
        data: {
            labels: ['متاجر نشطة', 'متاجر غير نشطة', 'متاجر جديدة'],
            datasets: [{
                data: [{{ $stats['active_stores'] }}, {{ $stats['total_stores'] - $stats['active_stores'] }}, 5],
                backgroundColor: [
                    'rgb(16, 185, 129)',
                    'rgb(239, 68, 68)',
                    'rgb(59, 130, 246)'
                ],
                borderWidth: 0,
                cutout: '60%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});

function refreshDashboard() {
    location.reload();
}
</script>
@endpush
@endsection
