
<x-app-layout>
    <div class="main-container rtl">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-tachometer-alt"></i>
                لوحة التحكم الرئيسية
            </h1>
            <p class="page-subtitle">نظرة شاملة على أداء محل الموبايلات</p>
        </div>

        <!-- Statistics Cards -->
        <div class="dashboard-grid">
            <!-- Sales Card -->
            <div class="stat-card sales">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">{{ number_format($totalSales ?? 150) }}</div>
                <div class="stat-label">إجمالي المبيعات</div>
                <small style="color: #28a745; font-weight: bold;">
                    <i class="fas fa-arrow-up"></i> +12% من الشهر الماضي
                </small>
            </div>

            <!-- Products Card -->
            <div class="stat-card products">
                <div class="stat-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="stat-value">{{ number_format($totalProducts ?? 95) }}</div>
                <div class="stat-label">إجمالي المنتجات</div>
                <small style="color: #007bff; font-weight: bold;">
                    <i class="fas fa-box"></i> {{ $lowStockCount ?? 5 }} منتج ينقص من المخزون
                </small>
            </div>

            <!-- Repairs Card -->
            <div class="stat-card repairs">
                <div class="stat-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="stat-value">{{ number_format($totalRepairs ?? 28) }}</div>
                <div class="stat-label">طلبات الصيانة</div>
                <small style="color: #ffc107; font-weight: bold;">
                    <i class="fas fa-clock"></i> {{ $pendingRepairs ?? 8 }} قيد الانتظار
                </small>
            </div>

            <!-- Revenue Card -->
            <div class="stat-card revenue">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">{{ number_format($totalRevenue ?? 45750) }} ر.س</div>
                <div class="stat-label">إجمالي الإيرادات</div>
                <small style="color: #dc3545; font-weight: bold;">
                    <i class="fas fa-chart-line"></i> هذا الشهر
                </small>
            </div>
        </div>

        <!-- Charts Row -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <!-- Sales Chart -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-area"></i>
                    تطور المبيعات الشهرية
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="height: 300px;"></canvas>
                </div>
            </div>

            <!-- Category Distribution -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chart-pie"></i>
                    توزيع المبيعات حسب الفئة
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <!-- Recent Sales -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-receipt"></i>
                    المبيعات الأخيرة
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>العميل</th>
                                    <th>المبلغ</th>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>INV-2025-000001</td>
                                    <td>أحمد محمد علي</td>
                                    <td>1,250 ر.س</td>
                                    <td>اليوم، 02:30 م</td>
                                    <td><span class="status-badge status-completed">مكتملة</span></td>
                                </tr>
                                <tr>
                                    <td>INV-2025-000002</td>
                                    <td>فاطمة أحمد</td>
                                    <td>800 ر.س</td>
                                    <td>اليوم، 01:15 م</td>
                                    <td><span class="status-badge status-completed">مكتملة</span></td>
                                </tr>
                                <tr>
                                    <td>INV-2025-000003</td>
                                    <td>محمد عبدالله</td>
                                    <td>2,100 ر.س</td>
                                    <td>أمس، 04:45 م</td>
                                    <td><span class="status-badge status-pending">قيد المراجعة</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding: 15px; text-align: center;">
                        <a href="{{ route('invoices.index') }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            عرض جميع الفواتير
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Alerts -->
            <div>
                <!-- Quick Actions -->
                <div class="card" style="margin-bottom: 20px;">
                    <div class="card-header">
                        <i class="fas fa-bolt"></i>
                        إجراءات سريعة
                    </div>
                    <div class="card-body">
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <a href="{{ route('invoices.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i>
                                فاتورة جديدة
                            </a>
                            <a href="{{ route('products.create') }}" class="btn btn-primary">
                                <i class="fas fa-mobile-alt"></i>
                                إضافة منتج
                            </a>
                            <a href="{{ route('repairs.index') }}" class="btn btn-warning">
                                <i class="fas fa-tools"></i>
                                طلبات الصيانة
                            </a>
                            <a href="{{ route('reports.index') }}" class="btn btn-info">
                                <i class="fas fa-chart-bar"></i>
                                التقارير
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alert -->
                <div class="card">
                    <div class="card-header" style="background: #dc3545;">
                        <i class="fas fa-exclamation-triangle"></i>
                        تنبيهات المخزون
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <strong>تحذير!</strong> {{ $lowStockCount ?? 5 }} منتجات تحتاج إعادة تعبئة
                        </div>
                        <div style="font-size: 0.9rem;">
                            <div style="margin-bottom: 8px;">
                                <i class="fas fa-mobile-alt text-warning"></i>
                                iPhone 13 Pro - متبقي: 2
                            </div>
                            <div style="margin-bottom: 8px;">
                                <i class="fas fa-headphones text-warning"></i>
                                سماعات AirPods - متبقي: 3
                            </div>
                            <div style="margin-bottom: 8px;">
                                <i class="fas fa-tablet-alt text-warning"></i>
                                Samsung Galaxy Tab - متبقي: 1
                            </div>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-warning btn-small">
                            <i class="fas fa-eye"></i>
                            عرض المخزون
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'المبيعات (ر.س)',
                    data: [12000, 19000, 15000, 25000, 22000, 30000],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
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
                        ticks: {
                            callback: function(value) {
                                return value + ' ر.س';
                            }
                        }
                    }
                }
            }
        });

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['هواتف ذكية', 'اكسسوارات', 'أجهزة لوحية', 'سماعات'],
                datasets: [{
                    data: [45, 25, 20, 10],
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f093fb',
                        '#f5576c'
                    ]
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
    </script>
</x-app-layout>
