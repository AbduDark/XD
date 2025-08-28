
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Header -->
        <div class="card-enhanced mb-8">
            <div class="card-header-enhanced" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full ml-4">
                        <i class="fas fa-tools text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">إدارة الصيانة</h1>
                        <p class="text-yellow-100 text-sm">تتبع وإدارة أوامر صيانة الأجهزة</p>
                    </div>
                </div>
                <a href="{{ route('repairs.create') }}" class="btn-enhanced bg-white text-yellow-600 hover:bg-yellow-50">
                    <i class="fas fa-plus ml-2"></i>
                    أمر صيانة جديد
                </a>
            </div>
        </div>

        <!-- Enhanced Status Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-yellow-400 to-yellow-600 text-white">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-value">{{ $repairs->where('status', 'pending')->count() }}</div>
                <div class="stats-label">في الانتظار</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                    <i class="fas fa-cog fa-spin"></i>
                </div>
                <div class="stats-value">{{ $repairs->where('status', 'in_progress')->count() }}</div>
                <div class="stats-label">قيد العمل</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-green-400 to-green-600 text-white">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stats-value">{{ $repairs->where('status', 'completed')->count() }}</div>
                <div class="stats-label">مكتملة</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-purple-400 to-purple-600 text-white">
                    <i class="fas fa-hand-holding"></i>
                </div>
                <div class="stats-value">{{ $repairs->where('status', 'delivered')->count() }}</div>
                <div class="stats-label">مُسلمة</div>
            </div>
        </div>

        <!-- Revenue Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-emerald-400 to-emerald-600 text-white">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stats-value">{{ number_format($repairs->sum('repair_cost'), 0) }}</div>
                <div class="stats-label">إجمالي الإيرادات (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-indigo-400 to-indigo-600 text-white">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="stats-value">{{ number_format($repairs->avg('repair_cost'), 0) }}</div>
                <div class="stats-label">متوسط التكلفة (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-pink-400 to-pink-600 text-white">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-value">{{ $repairs->where('created_at', '>=', now()->startOfDay())->sum('repair_cost') }}</div>
                <div class="stats-label">إيرادات اليوم (ج.م)</div>
            </div>
        </div>

        <!-- Enhanced Repairs Table -->
        <div class="card-enhanced">
            <div class="overflow-x-auto">
                <table class="table-enhanced">
                    <thead style="background: linear-gradient(135deg, #92400e, #a16207);">
                        <tr>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag ml-2"></i>
                                    رقم الصيانة
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-user ml-2"></i>
                                    بيانات العميل
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-mobile-alt ml-2"></i>
                                    نوع الجهاز
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-wrench ml-2"></i>
                                    المشكلة
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill-wave ml-2"></i>
                                    التكلفة
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-flag ml-2"></i>
                                    الحالة
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar ml-2"></i>
                                    التاريخ
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
                        @forelse($repairs as $repair)
                        <tr class="hover:bg-gradient-to-r hover:from-yellow-50 hover:to-orange-50 transition-all duration-300">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                        {{ substr($repair->repair_number, -2) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $repair->repair_number }}</div>
                                        <div class="text-xs text-gray-500">REP-{{ $repair->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white ml-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $repair->customer_name }}</div>
                                        <div class="flex items-center text-sm text-gray-600 mt-1">
                                            <i class="fas fa-phone text-green-500 ml-1"></i>
                                            {{ $repair->customer_phone }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm ml-2">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $repair->device_type }}</div>
                                        @if($repair->device_model)
                                        <div class="text-xs text-gray-500">{{ $repair->device_model }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="max-w-xs">
                                    <div class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full inline-block mb-1">
                                        <i class="fas fa-exclamation-triangle ml-1"></i>
                                        المشكلة
                                    </div>
                                    <div class="text-sm text-gray-700">
                                        {{ Str::limit($repair->problem_description, 50) }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <div class="text-xl font-bold text-green-600">
                                        {{ number_format($repair->repair_cost, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500">جنيه مصري</div>
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-center">
                                    @if($repair->status == 'pending')
                                        <span class="status-badge-enhanced status-pending">
                                            <i class="fas fa-clock ml-1"></i>
                                            في الانتظار
                                        </span>
                                    @elseif($repair->status == 'in_progress')
                                        <span class="status-badge-enhanced status-in-progress">
                                            <i class="fas fa-cog fa-spin ml-1"></i>
                                            قيد العمل
                                        </span>
                                    @elseif($repair->status == 'completed')
                                        <span class="status-badge-enhanced status-completed">
                                            <i class="fas fa-check ml-1"></i>
                                            مكتملة
                                        </span>
                                    @elseif($repair->status == 'delivered')
                                        <span class="status-badge-enhanced status-delivered">
                                            <i class="fas fa-hand-holding ml-1"></i>
                                            مُسلمة
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-900">
                                        {{ $repair->created_at->format('Y/m/d') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $repair->created_at->format('H:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $repair->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('repairs.show', $repair) }}" class="action-btn action-btn-view" title="عرض التفاصيل">
                                        <i class="fas fa-eye ml-1"></i>
                                        عرض
                                    </a>
                                    <a href="{{ route('repairs.edit', $repair) }}" class="action-btn action-btn-edit" title="تعديل">
                                        <i class="fas fa-edit ml-1"></i>
                                        تعديل
                                    </a>
                                    <form method="POST" action="{{ route('repairs.destroy', $repair) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" 
                                                onclick="return confirm('هل أنت متأكد من حذف أمر الصيانة؟')" title="حذف">
                                            <i class="fas fa-trash ml-1"></i>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="text-center py-16">
                                    <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                                        <i class="fas fa-tools text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد أوامر صيانة</h3>
                                    <p class="text-gray-500 mb-6">لم يتم تسجيل أي أوامر صيانة حتى الآن</p>
                                    <a href="{{ route('repairs.create') }}" class="btn-enhanced" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                                        <i class="fas fa-plus ml-2"></i>
                                        إضافة أمر صيانة جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            @if($repairs->hasPages())
            <div class="px-6 py-4 bg-yellow-50 border-t border-yellow-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        عرض {{ $repairs->firstItem() }} إلى {{ $repairs->lastItem() }} من إجمالي {{ $repairs->total() }} أمر صيانة
                    </div>
                    <div class="pagination-enhanced">
                        {{ $repairs->links() }}
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
    // Status update animation
    const statusBadges = document.querySelectorAll('.status-badge-enhanced');
    statusBadges.forEach((badge, index) => {
        setTimeout(() => {
            badge.style.animation = 'pulse 2s infinite';
        }, index * 200);
    });

    // Auto refresh for status updates
    setInterval(function() {
        const statusElements = document.querySelectorAll('.status-in-progress');
        if (statusElements.length > 0) {
            window.location.reload();
        }
    }, 60000); // Refresh every minute if there are repairs in progress
});
</script>
@endsection
