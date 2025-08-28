
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Header -->
        <div class="card-enhanced mb-8">
            <div class="card-header-enhanced">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full ml-4">
                        <i class="fas fa-file-invoice text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">إدارة الفواتير</h1>
                        <p class="text-blue-100 text-sm">إدارة وتتبع جميع فواتير المبيعات</p>
                    </div>
                </div>
                <a href="{{ route('invoices.create') }}" class="btn-enhanced btn-success-enhanced">
                    <i class="fas fa-plus ml-2"></i>
                    فاتورة جديدة
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="stats-value">{{ $invoices->total() }}</div>
                <div class="stats-label">إجمالي الفواتير</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-green-400 to-green-600 text-white">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-value">{{ number_format($invoices->sum('total'), 0) }}</div>
                <div class="stats-label">إجمالي المبيعات (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-purple-400 to-purple-600 text-white">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-value">{{ $invoices->where('created_at', '>=', now()->startOfDay())->count() }}</div>
                <div class="stats-label">فواتير اليوم</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-yellow-400 to-orange-500 text-white">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stats-value">{{ number_format($invoices->avg('total'), 0) }}</div>
                <div class="stats-label">متوسط الفاتورة (ج.م)</div>
            </div>
        </div>

        <!-- Enhanced Search & Filter -->
        <div class="card-enhanced mb-8">
            <div class="card-body-enhanced">
                <form method="GET" action="{{ route('invoices.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <div class="md:col-span-2">
                            <div class="search-bar-enhanced">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="البحث في الفواتير..." class="form-input-enhanced">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <div>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="form-input-enhanced" placeholder="من تاريخ">
                        </div>
                        <div>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="form-input-enhanced" placeholder="إلى تاريخ">
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="btn-enhanced btn-primary-enhanced flex-1">
                                <i class="fas fa-search ml-2"></i>
                                بحث
                            </button>
                            <a href="{{ route('invoices.index') }}" class="btn-enhanced bg-gray-500 text-white hover:bg-gray-600">
                                <i class="fas fa-times ml-2"></i>
                                مسح
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Enhanced Invoices Table -->
        <div class="card-enhanced">
            <div class="overflow-x-auto">
                <table class="table-enhanced">
                    <thead>
                        <tr>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag ml-2"></i>
                                    رقم الفاتورة
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-user ml-2"></i>
                                    العميل
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-phone ml-2"></i>
                                    الهاتف
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-shopping-cart ml-2"></i>
                                    عدد الأصناف
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill-wave ml-2"></i>
                                    إجمالي المبلغ
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar ml-2"></i>
                                    التاريخ والوقت
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-cogs ml-2"></i>
                                    العمليات
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-300">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                        {{ substr($invoice->invoice_number, -2) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $invoice->invoice_number }}</div>
                                        <div class="text-xs text-gray-500">INV-{{ $invoice->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white text-sm ml-2">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            {{ $invoice->customer_name ?? 'عميل نقدي' }}
                                        </div>
                                        @if($invoice->customer_name)
                                        <div class="text-xs text-gray-500">عميل مسجل</div>
                                        @else
                                        <div class="text-xs text-gray-500">مبيع نقدي</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($invoice->customer_phone)
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-phone text-green-500 ml-2"></i>
                                    {{ $invoice->customer_phone }}
                                </div>
                                @else
                                <span class="text-gray-400 italic">غير مسجل</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white text-sm ml-2">
                                        {{ $invoice->items->count() }}
                                    </div>
                                    <span class="text-sm text-gray-600">صنف</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <div class="text-xl font-bold text-green-600">
                                        {{ number_format($invoice->total, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500">جنيه مصري</div>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-900">
                                        {{ $invoice->created_at->format('Y/m/d') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $invoice->created_at->format('H:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $invoice->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="action-btn action-btn-view" title="عرض التفاصيل">
                                        <i class="fas fa-eye ml-1"></i>
                                        عرض
                                    </a>
                                    <a href="{{ route('invoices.print', $invoice) }}" class="action-btn bg-indigo-100 text-indigo-700 hover:bg-indigo-200" target="_blank" title="طباعة الفاتورة">
                                        <i class="fas fa-print ml-1"></i>
                                        طباعة
                                    </a>
                                    <form method="POST" action="{{ route('invoices.destroy', $invoice) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟')" title="حذف الفاتورة">
                                            <i class="fas fa-trash ml-1"></i>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-16">
                                    <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-file-invoice text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد فواتير</h3>
                                    <p class="text-gray-500 mb-6">لم يتم إنشاء أي فواتير حتى الآن</p>
                                    <a href="{{ route('invoices.create') }}" class="btn-enhanced btn-primary-enhanced">
                                        <i class="fas fa-plus ml-2"></i>
                                        إنشاء فاتورة جديدة
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            @if($invoices->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        عرض {{ $invoices->firstItem() }} إلى {{ $invoices->lastItem() }} من إجمالي {{ $invoices->total() }} فاتورة
                    </div>
                    <div class="pagination-enhanced">
                        {{ $invoices->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh every 2 minutes
    setInterval(function() {
        if (!document.querySelector('input:focus')) {
            window.location.reload();
        }
    }, 120000);

    // Enhanced row click effect
    document.querySelectorAll('.table-enhanced tbody tr').forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.boxShadow = 'none';
        });
    });

    // Search with enter key
    document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });
});
</script>
@endsection
