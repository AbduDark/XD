
<x-app-layout>
    <div class="space-y-8">
        <!-- Header -->
        <div class="glass-effect shadow-elegant rounded-3xl overflow-hidden">
            <div class="p-8 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-20">
                    <div class="absolute top-4 left-4 w-32 h-32 bg-white rounded-full opacity-10 animate-pulse"></div>
                    <div class="absolute bottom-4 right-4 w-24 h-24 bg-white rounded-full opacity-10 animate-pulse" style="animation-delay: 1s;"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-white rounded-full opacity-5 animate-pulse" style="animation-delay: 2s;"></div>
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <div class="fade-in-up">
                        <h1 class="text-4xl font-bold mb-2">مرحباً {{ Auth::user()->name }}</h1>
                        <p class="text-blue-100 text-lg">لوحة التحكم - متجر الحسيني الاحترافي</p>
                        <div class="flex items-center mt-4 space-x-4 space-x-reverse">
                            <div class="flex items-center bg-white/20 rounded-full px-4 py-2">
                                <i class="fas fa-calendar-day ml-2"></i>
                                <span>{{ now()->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center bg-white/20 rounded-full px-4 py-2">
                                <i class="fas fa-clock ml-2"></i>
                                <span id="currentTime">{{ now()->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right slide-in-left">
                        <div class="bg-white/20 rounded-2xl p-6 backdrop-filter backdrop-blur-lg">
                            <div class="flex items-center justify-center w-16 h-16 bg-white/30 rounded-full mb-3">
                                <i class="fas fa-chart-line text-2xl text-white"></i>
                            </div>
                            <p class="text-sm text-blue-100">الإحصائيات محدثة</p>
                            <p class="text-xs text-blue-200">آخر تحديث: {{ now()->format('H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 dashboard-stats">
            <!-- Total Products -->
            <div class="stats-card hover-lift fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="stats-icon bg-gradient-to-r from-blue-400 to-blue-600">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600 mb-2">إجمالي المنتجات</p>
                        <p class="stats-value" id="totalProducts">{{ $totalProducts ?? 0 }}</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-arrow-up text-green-500 text-sm ml-1"></i>
                            <span class="text-sm text-green-600 font-semibold">+12%</span>
                            <span class="text-xs text-gray-500 mr-2">من الشهر الماضي</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition-colors duration-200">
                        عرض جميع المنتجات
                        <i class="fas fa-arrow-left mr-2 transform transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Today's Sales -->
            <div class="stats-card hover-lift fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="stats-icon bg-gradient-to-r from-green-400 to-green-600">
                            <i class="fas fa-dollar-sign text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600 mb-2">مبيعات اليوم</p>
                        <p class="stats-value text-green-600" id="todaySales">{{ number_format($todaySales ?? 0, 2) }} جنيه</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-arrow-up text-green-500 text-sm ml-1"></i>
                            <span class="text-sm text-green-600 font-semibold">+8%</span>
                            <span class="text-xs text-gray-500 mr-2">من الأمس</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('invoices.index') }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold transition-colors duration-200">
                        عرض الفواتير
                        <i class="fas fa-arrow-left mr-2 transform transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Pending Repairs -->
            <div class="stats-card hover-lift fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="stats-icon bg-gradient-to-r from-yellow-400 to-yellow-600">
                            <i class="fas fa-tools text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600 mb-2">صيانات معلقة</p>
                        <p class="stats-value text-yellow-600" id="pendingRepairs">{{ $pendingRepairs ?? 0 }}</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-minus text-yellow-500 text-sm ml-1"></i>
                            <span class="text-sm text-yellow-600 font-semibold">-3%</span>
                            <span class="text-xs text-gray-500 mr-2">من الأسبوع الماضي</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('repairs.index') }}" class="inline-flex items-center text-yellow-600 hover:text-yellow-800 font-semibold transition-colors duration-200">
                        عرض الصيانات
                        <i class="fas fa-arrow-left mr-2 transform transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="stats-card hover-lift fade-in-up" style="animation-delay: 0.4s;">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="stats-icon bg-gradient-to-r from-red-400 to-red-600">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-600 mb-2">منتجات ناقصة</p>
                        <p class="stats-value text-red-600" id="lowStock">{{ $lowStock ?? 0 }}</p>
                        <div class="flex items-center mt-3">
                            <i class="fas fa-arrow-up text-red-500 text-sm ml-1"></i>
                            <span class="text-sm text-red-600 font-semibold">+2</span>
                            <span class="text-xs text-gray-500 mr-2">منتجات جديدة</span>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}?filter=low_stock" class="inline-flex items-center text-red-600 hover:text-red-800 font-semibold transition-colors duration-200">
                        عرض المنتجات
                        <i class="fas fa-arrow-left mr-2 transform transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card hover-lift fade-in-up" style="animation-delay: 0.5s;">
            <div class="card-header flex items-center justify-between">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-bolt ml-3"></i>
                    إجراءات سريعة
                </h3>
                <div class="text-sm opacity-75">اختصارات العمليات الأساسية</div>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                    <a href="{{ route('invoices.create') }}" class="quick-action-btn bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700">
                        <i class="fas fa-plus"></i>
                        <span>فاتورة جديدة</span>
                    </a>
                    <a href="{{ route('products.create') }}" class="quick-action-btn bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700">
                        <i class="fas fa-box"></i>
                        <span>منتج جديد</span>
                    </a>
                    <a href="{{ route('repairs.create') }}" class="quick-action-btn bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700">
                        <i class="fas fa-tools"></i>
                        <span>صيانة جديدة</span>
                    </a>
                    <a href="{{ route('returns.create') }}" class="quick-action-btn bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700">
                        <i class="fas fa-undo"></i>
                        <span>مرتجع جديد</span>
                    </a>
                    <a href="{{ route('reports.sales') }}" class="quick-action-btn bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700">
                        <i class="fas fa-chart-line"></i>
                        <span>تقرير المبيعات</span>
                    </a>
                    <a href="{{ route('cash-transfers.index') }}" class="quick-action-btn bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700">
                        <i class="fas fa-exchange-alt"></i>
                        <span>تحويلات نقدية</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
            <!-- Recent Invoices -->
            <div class="card hover-lift fade-in-up" style="animation-delay: 0.6s;">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-lg font-bold flex items-center">
                        <i class="fas fa-receipt ml-3"></i>
                        آخر الفواتير
                    </h3>
                    <a href="{{ route('invoices.index') }}" class="text-sm text-white/80 hover:text-white transition-colors">عرض الكل</a>
                </div>
                <div class="card-body">
                    <div class="space-y-4" id="recentInvoices">
                        <div class="flex items-center justify-center h-32">
                            <div class="spinner"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Repairs -->
            <div class="card hover-lift fade-in-up" style="animation-delay: 0.7s;">
                <div class="card-header flex items-center justify-between">
                    <h3 class="text-lg font-bold flex items-center">
                        <i class="fas fa-tools ml-3"></i>
                        آخر الصيانات
                    </h3>
                    <a href="{{ route('repairs.index') }}" class="text-sm text-white/80 hover:text-white transition-colors">عرض الكل</a>
                </div>
                <div class="card-body">
                    <div class="space-y-4" id="recentRepairs">
                        <div class="flex items-center justify-center h-32">
                            <div class="spinner"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="card hover-lift fade-in-up" style="animation-delay: 0.8s;">
            <div class="card-header flex items-center justify-between">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-chart-area ml-3"></i>
                    مخطط المبيعات الأسبوعي
                </h3>
                <div class="flex items-center space-x-3 space-x-reverse">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-400 rounded-full ml-2"></div>
                        <span class="text-sm text-white/80">المبيعات</span>
                    </div>
                    <button class="text-white/60 hover:text-white transition-colors" id="refreshChart">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="h-96 relative">
                    <canvas id="salesChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('ar-EG', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }
        
        setInterval(updateClock, 1000);

        // Refresh chart button
        document.getElementById('refreshChart')?.addEventListener('click', function() {
            this.classList.add('animate-spin');
            setTimeout(() => {
                this.classList.remove('animate-spin');
                window.location.reload();
            }, 1000);
        });

        // Add stagger animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.fade-in-up');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</x-app-layout>
