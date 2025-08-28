
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 to-pink-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Header -->
        <div class="card-enhanced mb-8">
            <div class="card-header-enhanced" style="background: linear-gradient(135deg, #dc2626, #b91c1c);">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full ml-4">
                        <i class="fas fa-undo-alt text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">إدارة المرتجعات</h1>
                        <p class="text-red-100 text-sm">تتبع وإدارة مرتجعات المنتجات</p>
                    </div>
                </div>
                <a href="{{ route('returns.create') }}" class="btn-enhanced bg-white text-red-600 hover:bg-red-50">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة مرتجع جديد
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-red-400 to-red-600 text-white">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <div class="stats-value">{{ $returns->total() }}</div>
                <div class="stats-label">إجمالي المرتجعات</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-orange-400 to-red-500 text-white">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stats-value">{{ number_format($returns->sum('amount'), 0) }}</div>
                <div class="stats-label">قيمة المرتجعات (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-yellow-400 to-orange-500 text-white">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-value">{{ $returns->where('created_at', '>=', now()->startOfDay())->count() }}</div>
                <div class="stats-label">مرتجعات اليوم</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-pink-400 to-red-500 text-white">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stats-value">{{ $returns->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
                <div class="stats-label">مرتجعات الشهر</div>
            </div>
        </div>

        <!-- Enhanced Search & Filter -->
        <div class="card-enhanced mb-8">
            <div class="card-body-enhanced">
                <form method="GET" action="{{ route('returns.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <div class="md:col-span-2">
                            <div class="search-bar-enhanced">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="البحث في المرتجعات..." class="form-input-enhanced">
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
                            <button type="submit" class="btn-enhanced btn-danger-enhanced flex-1">
                                <i class="fas fa-search ml-2"></i>
                                بحث
                            </button>
                            <a href="{{ route('returns.index') }}" class="btn-enhanced bg-gray-500 text-white hover:bg-gray-600">
                                <i class="fas fa-times ml-2"></i>
                                مسح
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Enhanced Returns Table -->
        <div class="card-enhanced">
            <div class="overflow-x-auto">
                <table class="table-enhanced">
                    <thead style="background: linear-gradient(135deg, #7f1d1d, #991b1b);">
                        <tr>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag ml-2"></i>
                                    رقم المرتجع
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-box ml-2"></i>
                                    المنتج
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-sort-numeric-up ml-2"></i>
                                    الكمية
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle ml-2"></i>
                                    سبب الإرجاع
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill-wave ml-2"></i>
                                    المبلغ
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
                        @forelse($returns as $return)
                        <tr class="hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-300">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                        {{ $return->id }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">#{{ $return->id }}</div>
                                        <div class="text-xs text-gray-500">RET-{{ str_pad($return->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center text-white text-sm ml-3">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            {{ $return->product ? $return->product->name_ar : 'غير محدد' }}
                                        </div>
                                        @if($return->product)
                                        <div class="text-xs text-gray-500">
                                            كود: {{ $return->product->barcode ?? 'غير متاح' }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold mx-auto">
                                        {{ $return->quantity }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">قطعة</div>
                                </div>
                            </td>
                            <td>
                                <div class="max-w-xs">
                                    <div class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full inline-block mb-1">
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                        سبب الإرجاع
                                    </div>
                                    <div class="text-sm text-gray-700 leading-relaxed">
                                        {{ Str::limit($return->reason, 50) }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <div class="text-xl font-bold text-red-600">
                                        {{ number_format($return->amount, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500">جنيه مصري</div>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-900">
                                        {{ $return->created_at->format('Y/m/d') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $return->created_at->format('H:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $return->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('returns.show', $return) }}" class="action-btn action-btn-view" title="عرض التفاصيل">
                                        <i class="fas fa-eye ml-1"></i>
                                        عرض
                                    </a>
                                    <form method="POST" action="{{ route('returns.destroy', $return) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا المرتجع؟')" title="حذف المرتجع">
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
                                        <i class="fas fa-undo-alt text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد مرتجعات</h3>
                                    <p class="text-gray-500 mb-6">لم يتم تسجيل أي مرتجعات حتى الآن</p>
                                    <a href="{{ route('returns.create') }}" class="btn-enhanced btn-danger-enhanced">
                                        <i class="fas fa-plus ml-2"></i>
                                        إضافة مرتجع جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            @if($returns->hasPages())
            <div class="px-6 py-4 bg-red-50 border-t border-red-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        عرض {{ $returns->firstItem() }} إلى {{ $returns->lastItem() }} من إجمالي {{ $returns->total() }} مرتجع
                    </div>
                    <div class="pagination-enhanced">
                        {{ $returns->appends(request()->query())->links() }}
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
    // Auto refresh every 30 seconds
    setInterval(function() {
        if (!document.querySelector('input:focus')) {
            window.location.reload();
        }
    }, 30000);

    // Enhanced animations
    const cards = document.querySelectorAll('.stats-card-enhanced');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('scale-in');
        }, index * 100);
    });
});
</script>
@endsection
