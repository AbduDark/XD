
@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-lock ml-3"></i>
            تقفيل يومي
        </h1>
        <p class="dashboard-subtitle">تقفيل شامل للمبيعات والحسابات ليوم {{ $date->format('d/m/Y') }}</p>
        
        <div class="mt-4">
            <form method="GET" action="{{ route('reports.daily-closing') }}" class="flex justify-center">
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

    <!-- إحصائيات التقفيل -->
    <div class="stats-grid">
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-number">{{ number_format($sales, 2) }}</div>
            <div class="stat-label">إجمالي المبيعات (ج.م)</div>
        </div>

        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-number">{{ number_format($profit, 2) }}</div>
            <div class="stat-label">إجمالي الأرباح (ج.م)</div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-undo"></i>
            </div>
            <div class="stat-number">{{ number_format($returns, 2) }}</div>
            <div class="stat-label">المرتجعات (ج.م)</div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="stat-number">{{ number_format($netCash, 2) }}</div>
            <div class="stat-label">صافي النقد (ج.م)</div>
        </div>

        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-number">{{ $invoicesCount }}</div>
            <div class="stat-label">عدد الفواتير</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- حركة النقد -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exchange-alt"></i>
                    حركة النقد
                </h3>
            </div>
            <div class="card-content">
                <div class="space-y-4">
                    <div class="p-4 bg-green-500 bg-opacity-20 rounded-lg border border-green-500 border-opacity-30">
                        <div class="flex items-center justify-between">
                            <span class="text-white font-semibold">نقد داخل</span>
                            <span class="text-green-300 font-bold">{{ number_format($cashIn, 2) }} ج.م</span>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-red-500 bg-opacity-20 rounded-lg border border-red-500 border-opacity-30">
                        <div class="flex items-center justify-between">
                            <span class="text-white font-semibold">نقد خارج</span>
                            <span class="text-red-300 font-bold">{{ number_format($cashOut, 2) }} ج.م</span>
                        </div>
                    </div>
                    
                    <div class="p-4 bg-blue-500 bg-opacity-20 rounded-lg border border-blue-500 border-opacity-30">
                        <div class="flex items-center justify-between">
                            <span class="text-white font-semibold">صافي المبيعات</span>
                            <span class="text-blue-300 font-bold">{{ number_format($sales - $returns, 2) }} ج.م</span>
                        </div>
                    </div>

                    <div class="p-4 bg-purple-500 bg-opacity-20 rounded-lg border border-purple-500 border-opacity-30">
                        <div class="flex items-center justify-between">
                            <span class="text-white font-semibold">صافي الربح</span>
                            <span class="text-purple-300 font-bold">{{ number_format($profit, 2) }} ج.م</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- المنتجات المباعة -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-shopping-bag"></i>
                    المنتجات المباعة
                </h3>
            </div>
            <div class="card-content">
                @if($soldProducts->count() > 0)
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @foreach($soldProducts->sortByDesc('total_sold') as $item)
                        <div class="flex items-center justify-between p-3 bg-white bg-opacity-10 rounded-lg">
                            <div>
                                <div class="text-white font-semibold">{{ $item->product->name ?? 'منتج محذوف' }}</div>
                                <div class="text-gray-300 text-sm">{{ $item->total_sold }} قطعة مباعة</div>
                            </div>
                            <div class="text-right">
                                @if($item->product)
                                    <div class="text-white font-bold">{{ number_format($item->total_sold * $item->product->selling_price, 2) }} ج.م</div>
                                    <div class="text-gray-300 text-sm">{{ number_format($item->product->selling_price, 2) }} ج.م/قطعة</div>
                                @endif
                            </div>
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
    </div>

    <!-- ملخص التقفيل -->
    <div class="dashboard-card full-width">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-check"></i>
                ملخص التقفيل النهائي
            </h3>
        </div>
        <div class="card-content">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 bg-white bg-opacity-10 rounded-lg text-center">
                    <div class="text-4xl font-bold text-green-400 mb-2">{{ number_format($sales, 0) }}</div>
                    <div class="text-white text-lg">إجمالي المبيعات (ج.م)</div>
                    <div class="text-gray-300 text-sm mt-1">من {{ $invoicesCount }} فاتورة</div>
                </div>
                
                <div class="p-6 bg-white bg-opacity-10 rounded-lg text-center">
                    <div class="text-4xl font-bold text-purple-400 mb-2">{{ number_format($profit, 0) }}</div>
                    <div class="text-white text-lg">صافي الربح (ج.م)</div>
                    <div class="text-gray-300 text-sm mt-1">
                        {{ $sales > 0 ? number_format(($profit / $sales) * 100, 1) : 0 }}% هامش ربح
                    </div>
                </div>
                
                <div class="p-6 bg-white bg-opacity-10 rounded-lg text-center">
                    <div class="text-4xl font-bold text-blue-400 mb-2">{{ number_format($netCash, 0) }}</div>
                    <div class="text-white text-lg">صافي النقد (ج.م)</div>
                    <div class="text-gray-300 text-sm mt-1">نقد + مبيعات - مرتجعات</div>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-gradient-to-r from-purple-500 to-blue-500 bg-opacity-20 rounded-lg border border-purple-500 border-opacity-30">
                <div class="text-center">
                    <div class="text-white text-lg font-semibold">تم تقفيل يوم {{ $date->format('d/m/Y') }} بنجاح</div>
                    <div class="text-gray-300 text-sm mt-1">آخر تحديث: {{ now()->format('d/m/Y H:i:s') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
