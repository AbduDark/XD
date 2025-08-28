
@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-calendar-day ml-3"></i>
            التقرير اليومي
        </h1>
        <p class="dashboard-subtitle">تقرير مفصل لمبيعات وأرباح {{ $date->format('d/m/Y') }}</p>
        
        <div class="mt-4">
            <form method="GET" action="{{ route('reports.daily') }}" class="flex justify-center">
                <div class="flex items-center bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-2">
                    <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" 
                           class="bg-transparent text-white placeholder-gray-300 border-0 focus:ring-0 px-3 py-2">
                    <button type="submit" class="bg-white bg-opacity-30 hover:bg-opacity-40 text-white px-4 py-2 rounded-lg transition-all duration-200">
                        <i class="fas fa-search ml-2"></i>
                        عرض
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- إحصائيات اليوم -->
    <div class="stats-grid">
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-number">{{ number_format($dailySales, 2) }}</div>
            <div class="stat-label">إجمالي المبيعات (ج.م)</div>
        </div>

        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-number">{{ $dailyInvoices }}</div>
            <div class="stat-label">عدد الفواتير</div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-number">{{ number_format($dailyProfit, 2) }}</div>
            <div class="stat-label">إجمالي الأرباح (ج.م)</div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="stat-number">{{ $dailyInvoices > 0 ? number_format($dailySales / $dailyInvoices, 2) : 0 }}</div>
            <div class="stat-label">متوسط الفاتورة (ج.م)</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- أفضل المنتجات مبيعاً -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-star"></i>
                    أفضل المنتجات مبيعاً
                </h3>
            </div>
            <div class="card-content">
                @if($topProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($topProducts->take(5) as $item)
                        <div class="flex items-center justify-between p-3 bg-white bg-opacity-10 rounded-lg">
                            <div>
                                <div class="text-white font-semibold">{{ $item->product->name ?? 'منتج محذوف' }}</div>
                                <div class="text-gray-300 text-sm">{{ $item->total_sold }} قطعة</div>
                            </div>
                            <div class="text-white font-bold">{{ number_format($item->total_revenue, 2) }} ج.م</div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>لا توجد مبيعات لهذا اليوم</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- فواتير اليوم -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-file-invoice-dollar"></i>
                    فواتير اليوم
                </h3>
            </div>
            <div class="card-content">
                @if($invoices->count() > 0)
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @foreach($invoices->take(10) as $invoice)
                        <div class="flex items-center justify-between p-3 bg-white bg-opacity-10 rounded-lg">
                            <div>
                                <div class="text-white font-semibold">#{{ $invoice->id }}</div>
                                <div class="text-gray-300 text-sm">{{ $invoice->created_at->format('H:i') }}</div>
                                <div class="text-gray-300 text-sm">{{ $invoice->user->name ?? 'غير محدد' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-white font-bold">{{ number_format($invoice->total, 2) }} ج.م</div>
                                <div class="text-gray-300 text-sm">{{ $invoice->items->sum('quantity') }} قطعة</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-file-invoice"></i>
                        <p>لا توجد فواتير لهذا اليوم</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- جدول تفصيلي للفواتير -->
    @if($invoices->count() > 0)
    <div class="dashboard-card full-width">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-table"></i>
                جدول الفواتير التفصيلي
            </h3>
        </div>
        <div class="card-content">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-white border-opacity-20">
                            <th class="text-right py-3 px-4 text-white font-semibold">رقم الفاتورة</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">الوقت</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">البائع</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">عدد الأصناف</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">الإجمالي</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">الخصم</th>
                            <th class="text-right py-3 px-4 text-white font-semibold">المجموع النهائي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr class="border-b border-white border-opacity-10 hover:bg-white hover:bg-opacity-5">
                            <td class="py-3 px-4 text-white">#{{ $invoice->id }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $invoice->created_at->format('H:i:s') }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $invoice->user->name ?? 'غير محدد' }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ $invoice->items->sum('quantity') }}</td>
                            <td class="py-3 px-4 text-gray-300">{{ number_format($invoice->subtotal, 2) }} ج.م</td>
                            <td class="py-3 px-4 text-gray-300">{{ number_format($invoice->discount, 2) }} ج.م</td>
                            <td class="py-3 px-4 text-white font-semibold">{{ number_format($invoice->total, 2) }} ج.م</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
