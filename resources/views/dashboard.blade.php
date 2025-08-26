@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-tachometer-alt"></i>
            لوحة التحكم
        </h1>
        <p class="dashboard-subtitle">مرحباً بك في متجر الحسيني</p>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $totalProducts ?? 0 }}</h3>
                <p class="stat-label">إجمالي المنتجات</p>
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ number_format($totalSales ?? 0) }}</h3>
                <p class="stat-label">إجمالي المبيعات</p>
            </div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $lowStockProducts ?? 0 }}</h3>
                <p class="stat-label">منتجات تحتاج تجديد</p>
            </div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number">{{ $pendingRepairs ?? 0 }}</h3>
                <p class="stat-label">صيانات معلقة</p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="dashboard-grid">
        <!-- Recent Activities -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-clock"></i>
                    النشاطات الأخيرة
                </h2>
            </div>
            <div class="card-content">
                <div class="activity-list">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="activity-item">
                        <div class="activity-icon activity-{{ $activity['type'] ?? 'default' }}">
                            <i class="fas fa-{{ $activity['icon'] ?? 'circle' }}"></i>
                        </div>
                        <div class="activity-content">
                            <p class="activity-text">{{ $activity['message'] ?? 'نشاط جديد' }}</p>
                            <span class="activity-time">{{ $activity['time'] ?? 'الآن' }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>لا توجد أنشطة حديثة</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-bolt"></i>
                    إجراءات سريعة
                </h2>
            </div>
            <div class="card-content">
                <div class="quick-actions">
                    <a href="{{ route('products.create') }}" class="action-btn action-primary">
                        <i class="fas fa-plus"></i>
                        <span>إضافة منتج</span>
                    </a>
                    <a href="{{ route('invoices.create') }}" class="action-btn action-success">
                        <i class="fas fa-file-invoice"></i>
                        <span>فاتورة جديدة</span>
                    </a>
                    <a href="{{ route('returns.create') }}" class="action-btn action-warning">
                        <i class="fas fa-undo"></i>
                        <span>مرتجع جديد</span>
                    </a>
                    <a href="{{ route('repairs.create') }}" class="action-btn action-info">
                        <i class="fas fa-tools"></i>
                        <span>أمر صيانة</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="dashboard-card full-width">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-chart-line"></i>
                    رسم بياني للمبيعات
                </h2>
            </div>
            <div class="card-content">
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fas fa-exclamation-triangle text-orange-500"></i>
                    تنبيهات المخزون
                </h2>
            </div>
            <div class="card-content">
                <div class="stock-alerts">
                    @forelse($lowStockItems ?? [] as $item)
                    <div class="stock-item">
                        <div class="stock-info">
                            <h4 class="stock-name">{{ $item['name'] ?? 'منتج' }}</h4>
                            <p class="stock-quantity">الكمية المتبقية: {{ $item['quantity'] ?? 0 }}</p>
                        </div>
                        <div class="stock-status">
                            <span class="badge badge-warning">منخفض</span>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <p>جميع المنتجات متوفرة</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sales chart
    const ctx = document.getElementById('salesChart');
    if (ctx) {
        // You can add Chart.js here if needed
        ctx.innerHTML = '<div class="chart-placeholder"><i class="fas fa-chart-line"></i><p>الرسم البياني سيتم تفعيله قريباً</p></div>';
    }

    // Auto-refresh dashboard data every 30 seconds
    setInterval(function() {
        fetch('{{ route("dashboard") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            if (response.ok) {
                console.log('تم تحديث البيانات');
            }
        }).catch(error => {
            console.error('خطأ في تحديث البيانات:', error);
        });
    }, 30000);
});
</script>
@endsection