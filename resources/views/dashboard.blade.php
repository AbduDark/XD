@extends('layouts.app')

@section('page-title', 'لوحة التحكم الرئيسية')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">مرحباً، {{ Auth::user()->name }}</h1>
                <p class="text-blue-100">نظرة شاملة على أداء محل الموبايلات</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl text-blue-200"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sales -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">إجمالي المبيعات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalSales ?? 150) }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 flex items-center mt-1">
                        <i class="fas fa-arrow-up ml-1"></i>
                        +12% من الشهر الماضي
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">إجمالي المنتجات</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalProducts) }}</p>
                    <p class="text-sm text-blue-600 dark:text-blue-400 flex items-center mt-1">
                        <i class="fas fa-box ml-1"></i>
                        {{ number_format($lowStockProducts ?? 5) }} منتج منخفض المخزون
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-mobile-alt text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Repairs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">عمليات الصيانة النشطة</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($activeRepairs ?? 23) }}</p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-400 flex items-center mt-1">
                        <i class="fas fa-clock ml-1"></i>
                        {{ number_format($pendingRepairs ?? 8) }} في الانتظار
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tools text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">الإيرادات الشهرية</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($monthlyRevenue ?? 45230) }} ج.م</p>
                    <p class="text-sm text-purple-600 dark:text-purple-400 flex items-center mt-1">
                        <i class="fas fa-chart-line ml-1"></i>
                        +8% من الشهر الماضي
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <i class="fas fa-clock ml-2 text-blue-600"></i>
                    الأنشطة الأخيرة
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">تم إضافة منتج جديد</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">iPhone 15 Pro Max - منذ 5 دقائق</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-file-invoice text-blue-600 dark:text-blue-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">فاتورة جديدة</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">فاتورة #2025-001 - منذ 15 دقيقة</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-tools text-yellow-600 dark:text-yellow-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">اكتملت عملية صيانة</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">صيانة #REP-001 - منذ 30 دقيقة</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">تحذير مخزون منخفض</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Samsung Galaxy S24 - منذ ساعة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <i class="fas fa-bolt ml-2 text-yellow-600"></i>
                    إجراءات سريعة
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('products.create') }}" class="bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 p-4 rounded-lg text-center transition-colors group">
                        <i class="fas fa-plus text-blue-600 dark:text-blue-400 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <p class="text-sm font-medium text-blue-600 dark:text-blue-400">إضافة منتج</p>
                    </a>

                    <a href="{{ route('invoices.create') }}" class="bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 p-4 rounded-lg text-center transition-colors group">
                        <i class="fas fa-file-invoice text-green-600 dark:text-green-400 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <p class="text-sm font-medium text-green-600 dark:text-green-400">فاتورة جديدة</p>
                    </a>

                    <a href="{{ route('repairs.index') }}" class="bg-yellow-50 dark:bg-yellow-900 hover:bg-yellow-100 dark:hover:bg-yellow-800 p-4 rounded-lg text-center transition-colors group">
                        <i class="fas fa-tools text-yellow-600 dark:text-yellow-400 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">إدارة الصيانة</p>
                    </a>

                    <a href="{{ route('reports.index') }}" class="bg-purple-50 dark:bg-purple-900 hover:bg-purple-100 dark:hover:bg-purple-800 p-4 rounded-lg text-center transition-colors group">
                        <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400 text-2xl mb-2 group-hover:scale-110 transition-transform"></i>
                        <p class="text-sm font-medium text-purple-600 dark:text-purple-400">التقارير</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                <i class="fas fa-chart-line ml-2 text-green-600"></i>
                نظرة عامة على المبيعات
            </h3>
        </div>
        <div class="p-6">
            <div class="h-64 flex items-center justify-center bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-chart-area text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">سيتم إضافة الرسوم البيانية قريباً</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection