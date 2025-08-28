
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Header -->
        <div class="card-enhanced mb-8">
            <div class="card-header-enhanced" style="background: linear-gradient(135deg, #059669, #047857);">
                <div class="flex items-center">
                    <div class="bg-white bg-opacity-20 p-3 rounded-full ml-4">
                        <i class="fas fa-exchange-alt text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">إدارة التحويلات النقدية</h1>
                        <p class="text-green-100 text-sm">تتبع وإدارة حركة الأموال الداخلة والخارجة</p>
                    </div>
                </div>
                <a href="{{ route('cash-transfers.create') }}" class="btn-enhanced bg-white text-green-600 hover:bg-green-50">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة حركة نقدية
                </a>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-green-400 to-green-600 text-white">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="stats-value">{{ number_format($transfers->where('type', 'income')->sum('amount'), 0) }}</div>
                <div class="stats-label">إجمالي الإيرادات (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-red-400 to-red-600 text-white">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="stats-value">{{ number_format($transfers->where('type', 'expense')->sum('amount'), 0) }}</div>
                <div class="stats-label">إجمالي المصروفات (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-blue-400 to-blue-600 text-white">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="stats-value">{{ number_format($transfers->where('type', 'income')->sum('amount') - $transfers->where('type', 'expense')->sum('amount'), 0) }}</div>
                <div class="stats-label">صافي الرصيد (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-purple-400 to-purple-600 text-white">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="stats-value">{{ $transfers->count() }}</div>
                <div class="stats-label">إجمالي العمليات</div>
            </div>
        </div>

        <!-- Today's Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-emerald-400 to-emerald-600 text-white">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-value">{{ number_format($transfers->where('type', 'income')->where('created_at', '>=', now()->startOfDay())->sum('amount'), 0) }}</div>
                <div class="stats-label">إيرادات اليوم (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-orange-400 to-red-500 text-white">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stats-value">{{ number_format($transfers->where('type', 'expense')->where('created_at', '>=', now()->startOfDay())->sum('amount'), 0) }}</div>
                <div class="stats-label">مصروفات اليوم (ج.م)</div>
            </div>

            <div class="stats-card-enhanced">
                <div class="stats-icon bg-gradient-to-br from-indigo-400 to-indigo-600 text-white">
                    <i class="fas fa-trending-up"></i>
                </div>
                <div class="stats-value">{{ $transfers->where('created_at', '>=', now()->startOfDay())->count() }}</div>
                <div class="stats-label">عمليات اليوم</div>
            </div>
        </div>

        <!-- Enhanced Transfers Table -->
        <div class="card-enhanced">
            <div class="overflow-x-auto">
                <table class="table-enhanced">
                    <thead style="background: linear-gradient(135deg, #065f46, #047857);">
                        <tr>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag ml-2"></i>
                                    رقم العملية
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-tag ml-2"></i>
                                    نوع العملية
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
                                    <i class="fas fa-align-left ml-2"></i>
                                    الوصف
                                </div>
                            </th>
                            <th>
                                <div class="flex items-center">
                                    <i class="fas fa-user ml-2"></i>
                                    المستخدم
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
                        @forelse($transfers as $transfer)
                        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 transition-all duration-300">
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br {{ $transfer->type == 'income' ? 'from-green-400 to-green-600' : 'from-red-400 to-red-600' }} rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                        {{ $transfer->id }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900">#{{ str_pad($transfer->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="text-xs text-gray-500">CT-{{ $transfer->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex justify-center">
                                    @if($transfer->type == 'income')
                                    <div class="status-badge-enhanced bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up ml-1"></i>
                                        إيراد
                                    </div>
                                    @else
                                    <div class="status-badge-enhanced bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-down ml-1"></i>
                                        مصروف
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <div class="text-xl font-bold {{ $transfer->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $transfer->type == 'income' ? '+' : '-' }}{{ number_format($transfer->amount, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500">جنيه مصري</div>
                                </div>
                            </td>
                            <td>
                                <div class="max-w-xs">
                                    <div class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full inline-block mb-1">
                                        <i class="fas fa-info-circle ml-1"></i>
                                        التفاصيل
                                    </div>
                                    <div class="text-sm text-gray-700 leading-relaxed">
                                        {{ Str::limit($transfer->description, 50) }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-sm ml-2">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $transfer->user->name ?? 'غير محدد' }}</div>
                                        <div class="text-xs text-gray-500">{{ $transfer->user->role ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-900">
                                        {{ $transfer->created_at->format('Y/m/d') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $transfer->created_at->format('H:i A') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $transfer->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('cash-transfers.edit', $transfer) }}" class="action-btn action-btn-edit" title="تعديل">
                                        <i class="fas fa-edit ml-1"></i>
                                        تعديل
                                    </a>
                                    <form method="POST" action="{{ route('cash-transfers.destroy', $transfer) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذه العملية؟')" title="حذف">
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
                                        <i class="fas fa-exchange-alt text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-600 mb-2">لا توجد حركات نقدية</h3>
                                    <p class="text-gray-500 mb-6">لم يتم تسجيل أي حركات نقدية حتى الآن</p>
                                    <a href="{{ route('cash-transfers.create') }}" class="btn-enhanced" style="background: linear-gradient(135deg, #059669, #047857); color: white;">
                                        <i class="fas fa-plus ml-2"></i>
                                        إضافة حركة نقدية جديدة
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            @if($transfers->hasPages())
            <div class="px-6 py-4 bg-green-50 border-t border-green-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        عرض {{ $transfers->firstItem() }} إلى {{ $transfers->lastItem() }} من إجمالي {{ $transfers->total() }} عملية
                    </div>
                    <div class="pagination-enhanced">
                        {{ $transfers->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Quick Analysis Chart -->
        <div class="card-enhanced mt-8">
            <div class="card-header-enhanced" style="background: linear-gradient(135deg, #059669, #047857);">
                <h3 class="text-xl font-bold">تحليل سريع للتدفق النقدي</h3>
            </div>
            <div class="card-body-enhanced">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="text-center">
                        <canvas id="cashFlowChart" width="300" height="200"></canvas>
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                                <span class="font-semibold text-green-800">الإيرادات:</span>
                                <span class="text-2xl font-bold text-green-600">{{ number_format($transfers->where('type', 'income')->sum('amount'), 0) }} ج.م</span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg">
                                <span class="font-semibold text-red-800">المصروفات:</span>
                                <span class="text-2xl font-bold text-red-600">{{ number_format($transfers->where('type', 'expense')->sum('amount'), 0) }} ج.م</span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
                                <span class="font-semibold text-blue-800">الرصيد الصافي:</span>
                                <span class="text-2xl font-bold {{ ($transfers->where('type', 'income')->sum('amount') - $transfers->where('type', 'expense')->sum('amount')) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($transfers->where('type', 'income')->sum('amount') - $transfers->where('type', 'expense')->sum('amount'), 0) }} ج.م
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for cash flow visualization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('cashFlowChart').getContext('2d');
    const incomeAmount = {{ $transfers->where('type', 'income')->sum('amount') }};
    const expenseAmount = {{ $transfers->where('type', 'expense')->sum('amount') }};
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['الإيرادات', 'المصروفات'],
            datasets: [{
                data: [incomeAmount, expenseAmount],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgb(16, 185, 129)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 14,
                            family: 'Cairo'
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'توزيع التدفق النقدي',
                    font: {
                        size: 16,
                        family: 'Cairo'
                    }
                }
            }
        }
    });

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
