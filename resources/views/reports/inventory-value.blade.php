
@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-dollar-sign ml-3"></i>
            تقرير قيمة المخزون
        </h1>
        <p class="dashboard-subtitle">تحليل شامل لقيمة المنتجات المتوفرة بأسعار البيع والشراء</p>
    </div>

    <!-- إحصائيات قيمة المخزون -->
    <div class="stats-grid">
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-number">{{ number_format($totalSellingValue, 2) }}</div>
            <div class="stat-label">قيمة المخزون بسعر البيع (ج.م)</div>
        </div>

        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-number">{{ number_format($totalPurchaseValue, 2) }}</div>
            <div class="stat-label">قيمة المخزون بسعر الشراء (ج.م)</div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-number">{{ number_format($expectedProfit, 2) }}</div>
            <div class="stat-label">الربح المتوقع (ج.م)</div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-percentage"></i>
            </div>
            <div class="stat-number">{{ $totalPurchaseValue > 0 ? number_format(($expectedProfit / $totalPurchaseValue) * 100, 1) : 0 }}%</div>
            <div class="stat-label">نسبة الربح المتوقعة</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- إحصائيات الفئات -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i>
                    إحصائيات الفئات
                </h3>
            </div>
            <div class="card-content">
                @if($categoryStats->count() > 0)
                    <div class="space-y-4">
                        @foreach($categoryStats as $category => $stats)
                        <div class="p-4 bg-white bg-opacity-10 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-white font-semibold">{{ $category ?? 'بدون فئة' }}</h4>
                                <span class="text-gray-300 text-sm">{{ $stats['products_count'] }} منتج</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-300">الكمية:</span>
                                    <span class="text-white font-semibold">{{ number_format($stats['total_quantity']) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-300">قيمة البيع:</span>
                                    <span class="text-white font-semibold">{{ number_format($stats['selling_value'], 0) }} ج.م</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <p>لا توجد فئات محددة</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- تنبيهات المخزون -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    تنبيهات المخزون
                </h3>
            </div>
            <div class="card-content">
                <div class="space-y-4">
                    <div class="p-4 bg-red-500 bg-opacity-20 rounded-lg border border-red-500 border-opacity-30">
                        <div class="flex items-center justify-between">
                            <span class="text-white font-semibold">منتجات نفدت</span>
                            <span class="text-red-300 font-bold">{{ $outOfStockProducts->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-yellow-500 bg-opacity-20 rounded-lg border border-yellow-500 border-opacity-30">
                        <div class="flex items-center justify-between">
                            <span class="text-white font-semibold">منتجات قليلة المخزون</span>
                            <span class="text-yellow-300 font-bold">{{ $lowStockProducts->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-green-500 bg-opacity-20 rounded-lg border border-green-500 border-opacity-30">
                        <div class="flex items-center justify-between">
                            <span class="text-white font-semibold">إجمالي المنتجات</span>
                            <span class="text-green-300 font-bold">{{ $products->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول المنتجات التفصيلي -->
    <div class="dashboard-card full-width">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول المنتجات التفصيلي
            </h3>
        </div>
        <div class="card-content">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-white border-opacity-20">
                            <th class="text-right py-3 px-4 text-white font-semibold">المنتج</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">الفئة</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">الكمية</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">سعر الشراء</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">سعر البيع</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">قيمة الشراء</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">قيمة البيع</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">الربح المتوقع</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        @php
                            $purchaseValue = $product->quantity * $product->purchase_price;
                            $sellingValue = $product->quantity * $product->selling_price;
                            $expectedProfit = $sellingValue - $purchaseValue;
                            $isLowStock = $product->quantity <= $product->min_quantity;
                            $isOutOfStock = $product->quantity == 0;
                        @endphp
                        <tr class="border-b border-white border-opacity-10 hover:bg-white hover:bg-opacity-5 {{ $isOutOfStock ? 'bg-red-500 bg-opacity-10' : ($isLowStock ? 'bg-yellow-500 bg-opacity-10' : '') }}">
                            <td class="py-3 px-4 text-white font-semibold">{{ $product->name }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $product->category->name_ar ?? 'غير محدد' }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ number_format($product->quantity) }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ number_format($product->purchase_price, 2) }} ج.م</td>
                            <td class="py-3 px-4 text-gray-300">{{ number_format($product->selling_price, 2) }} ج.م</td>
                            <td class="py-3 px-4 text-blue-300">{{ number_format($purchaseValue, 2) }} ج.م</td>
                            <td class="py-3 px-4 text-green-300">{{ number_format($sellingValue, 2) }} ج.م</td>
                            <td class="py-3 px-4 text-purple-300">{{ number_format($expectedProfit, 2) }} ج.م</td>
                            <td class="py-3 px-4">
                                @if($isOutOfStock)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500 bg-opacity-20 text-red-300 border border-red-500 border-opacity-30">نفد</span>
                                @elseif($isLowStock)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500 bg-opacity-20 text-yellow-300 border border-yellow-500 border-opacity-30">قليل</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500 bg-opacity-20 text-green-300 border border-green-500 border-opacity-30">متوفر</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
