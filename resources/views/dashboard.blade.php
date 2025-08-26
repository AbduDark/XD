<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">مرحباً {{ Auth::user()->name }}</h1>
                        <p class="text-blue-100 mt-1">لوحة التحكم - متجر الحسيني</p>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100">{{ now()->format('d/m/Y') }}</p>
                        <p class="text-sm text-blue-200">{{ now()->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Products -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">إجمالي المنتجات</p>
                            <p class="text-3xl font-bold text-gray-900" id="totalProducts">{{ $totalProducts ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-box text-2xl text-blue-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            عرض جميع المنتجات
                            <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Today's Sales -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">مبيعات اليوم</p>
                            <p class="text-3xl font-bold text-gray-900" id="todaySales">{{ number_format($todaySales ?? 0, 2) }} جنيه</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-dollar-sign text-2xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('invoices.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                            عرض الفواتير
                            <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pending Repairs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">صيانات معلقة</p>
                            <p class="text-3xl font-bold text-gray-900" id="pendingRepairs">{{ $pendingRepairs ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-tools text-2xl text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('repairs.index') }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                            عرض الصيانات
                            <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">منتجات ناقصة</p>
                            <p class="text-3xl font-bold text-gray-900" id="lowStock">{{ $lowStock ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-full">
                            <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('products.index') }}?filter=low_stock" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            عرض المنتجات
                            <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <a href="{{ route('invoices.create') }}" class="quick-action-btn bg-blue-500 hover:bg-blue-600">
                        <i class="fas fa-plus text-2xl mb-2"></i>
                        <span>فاتورة جديدة</span>
                    </a>
                    <a href="{{ route('products.create') }}" class="quick-action-btn bg-green-500 hover:bg-green-600">
                        <i class="fas fa-box text-2xl mb-2"></i>
                        <span>منتج جديد</span>
                    </a>
                    <a href="{{ route('repairs.create') }}" class="quick-action-btn bg-yellow-500 hover:bg-yellow-600">
                        <i class="fas fa-tools text-2xl mb-2"></i>
                        <span>صيانة جديدة</span>
                    </a>
                    <a href="{{ route('returns.create') }}" class="quick-action-btn bg-red-500 hover:bg-red-600">
                        <i class="fas fa-undo text-2xl mb-2"></i>
                        <span>مرتجع جديد</span>
                    </a>
                    <a href="{{ route('reports.sales') }}" class="quick-action-btn bg-purple-500 hover:bg-purple-600">
                        <i class="fas fa-chart-line text-2xl mb-2"></i>
                        <span>تقرير المبيعات</span>
                    </a>
                    <a href="{{ route('cash-transfers.index') }}" class="quick-action-btn bg-indigo-500 hover:bg-indigo-600">
                        <i class="fas fa-exchange-alt text-2xl mb-2"></i>
                        <span>تحويلات نقدية</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Invoices -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">آخر الفواتير</h3>
                        <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">عرض الكل</a>
                    </div>
                    <div class="space-y-3" id="recentInvoices">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Recent Repairs -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">آخر الصيانات</h3>
                        <a href="{{ route('repairs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">عرض الكل</a>
                    </div>
                    <div class="space-y-3" id="recentRepairs">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">مخطط المبيعات الأسبوعي</h3>
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</x-app-layout>