
@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-chart-line ml-3"></i>
            التقارير والإحصائيات
        </h1>
        <p class="dashboard-subtitle">تحليل شامل لأداء المتجر والمبيعات</p>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-number">{{ number_format($monthSales, 2) }}</div>
            <div class="stat-label">مبيعات الشهر (ج.م)</div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-number">{{ $monthInvoices }}</div>
            <div class="stat-label">عدد الفواتير (الشهر)</div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="stat-number">{{ $pendingRepairs }}</div>
            <div class="stat-label">أجهزة قيد الصيانة</div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-undo"></i>
            </div>
            <div class="stat-number">{{ number_format($totalReturns, 2) }}</div>
            <div class="stat-label">إجمالي المرتجعات (ج.م)</div>
        </div>

        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-number">{{ number_format($todayProfit, 2) }}</div>
            <div class="stat-label">أرباح اليوم (ج.م)</div>
        </div>
    </div>

    <!-- الرسوم البيانية -->
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-area"></i>
                    مبيعات آخر 7 أيام
                </h3>
            </div>
            <div class="card-content">
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i>
                    توزيع المنتجات حسب الفئة
                </h3>
            </div>
            <div class="card-content">
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة التقارير -->
    <div class="dashboard-card full-width">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i>
                التقارير المتاحة
            </h3>
        </div>
        <div class="card-content">
            <div class="quick-actions">
                <a href="{{ route('reports.sales') }}" class="action-btn action-primary">
                    <i class="fas fa-chart-line"></i>
                    <span>تقرير المبيعات</span>
                    <small>تحليل مفصل للمبيعات والأرباح</small>
                </a>

                <a href="{{ route('reports.daily') }}" class="action-btn action-success">
                    <i class="fas fa-calendar-day"></i>
                    <span>التقرير اليومي</span>
                    <small>مبيعات وأرباح يوم محدد</small>
                </a>

                <a href="{{ route('reports.inventory') }}" class="action-btn action-warning">
                    <i class="fas fa-boxes"></i>
                    <span>تقرير المخزون</span>
                    <small>حالة المخزون والمنتجات الناقصة</small>
                </a>

                <a href="{{ route('reports.inventory-value') }}" class="action-btn action-info">
                    <i class="fas fa-dollar-sign"></i>
                    <span>قيمة المخزون</span>
                    <small>قيمة المنتجات بأسعار البيع والشراء</small>
                </a>

                <a href="{{ route('reports.daily-closing') }}" class="action-btn action-primary">
                    <i class="fas fa-lock"></i>
                    <span>تقفيل يومي</span>
                    <small>تقفيل يومي للمبيعات والحسابات</small>
                </a>

                <a href="{{ route('reports.repairs') }}" class="action-btn action-success">
                    <i class="fas fa-wrench"></i>
                    <span>تقرير الإصلاحات</span>
                    <small>حالة الإصلاحات والأرباح</small>
                </a>
            </div>
        </div>
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
            borderColor: 'rgba(102, 126, 234, 1)',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgba(102, 126, 234, 1)',
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
                    color: '#fff',
                    font: {
                        family: 'Arial',
                        size: 14
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#fff',
                    callback: function(value) {
                        return value.toLocaleString() + ' ج.م';
                    }
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                }
            },
            x: {
                ticks: {
                    color: '#fff'
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
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
                'rgba(102, 126, 234, 0.8)',
                'rgba(79, 172, 254, 0.8)',
                'rgba(240, 147, 251, 0.8)',
                'rgba(255, 205, 210, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(6, 182, 212, 0.8)'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#fff',
                    font: {
                        family: 'Arial',
                        size: 12
                    },
                    padding: 20
                }
            }
        }
    }
});
</script>
@endsection
