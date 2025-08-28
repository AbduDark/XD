
@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">التقارير والإحصائيات</h1>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fas fa-chart-line text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي المبيعات (الشهر)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($monthSales, 2) }} ج.م</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <i class="fas fa-file-invoice text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">عدد الفواتير (الشهر)</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $monthInvoices }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <i class="fas fa-tools text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">أجهزة قيد الصيانة</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingRepairs }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                    <i class="fas fa-undo text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي المرتجعات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalReturns, 2) }} ج.م</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <i class="fas fa-coins text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">أرباح اليوم</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($todayProfit, 2) }} ج.م</p>
                </div>
            </div>
        </div>
    </div>

    <!-- الرسوم البيانية -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">مبيعات آخر 7 أيام</h3>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">توزيع المنتجات حسب الفئة</h3>
            <canvas id="categoryChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- قائمة التقارير -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('reports.sales') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-chart-line text-blue-600 dark:text-blue-400 text-2xl ml-4"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">تقرير المبيعات</h3>
                    <p class="text-gray-600 dark:text-gray-400">تقرير مفصل عن المبيعات والأرباح</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.daily') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-calendar-day text-green-600 dark:text-green-400 text-2xl ml-4"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">التقرير اليومي</h3>
                    <p class="text-gray-600 dark:text-gray-400">مبيعات وأرباح يوم محدد</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.inventory') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-boxes text-yellow-600 dark:text-yellow-400 text-2xl ml-4"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">تقرير المخزون</h3>
                    <p class="text-gray-600 dark:text-gray-400">حالة المخزون والمنتجات الناقصة</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.inventory-value') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-dollar-sign text-purple-600 dark:text-purple-400 text-2xl ml-4"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">قيمة المخزون</h3>
                    <p class="text-gray-600 dark:text-gray-400">قيمة المنتجات بأسعار البيع والشراء</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.daily-closing') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-lock text-indigo-600 dark:text-indigo-400 text-2xl ml-4"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">تقفيل يومي</h3>
                    <p class="text-gray-600 dark:text-gray-400">تقفيل يومي للمبيعات والحسابات</p>
                </div>
            </div>
        </a>

        <a href="{{ route('reports.repairs') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <i class="fas fa-tools text-orange-600 dark:text-orange-400 text-2xl ml-4"></i>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">تقرير الصيانة</h3>
                    <p class="text-gray-600 dark:text-gray-400">إحصائيات أعمال الصيانة</p>
                </div>
            </div>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// رسم بياني للمبيعات
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesChartLabels) !!},
        datasets: [{
            label: 'المبيعات (ج.م)',
            data: {!! json_encode($salesChartData) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                }
            },
            x: {
                ticks: {
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                }
            }
        }
    }
});

// رسم بياني للفئات
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($categoryLabels) !!},
        datasets: [{
            data: {!! json_encode($categoryData) !!},
            backgroundColor: [
                '#3B82F6',
                '#10B981',
                '#F59E0B',
                '#EF4444',
                '#8B5CF6',
                '#06B6D4'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                labels: {
                    color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                }
            }
        }
    }
});
</script>
@endsection
