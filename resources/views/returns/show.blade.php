
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">تفاصيل المرتجع #{{ $return->id }}</h1>
            <a href="{{ route('returns.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للمرتجعات
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">معلومات المرتجع</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">رقم المرتجع:</span>
                                <span class="font-medium text-gray-900 dark:text-white">#{{ $return->id }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">تاريخ المرتجع:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $return->created_at->format('Y-m-d h:i A') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">المستخدم:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $return->user->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">معلومات المنتج</h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">المنتج:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $return->product->name_ar ?? 'غير محدد' }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">الكمية:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $return->quantity }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">المبلغ:</span>
                                <span class="font-medium text-green-600 dark:text-green-400 text-lg">{{ number_format($return->amount, 2) }} ر.س</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">سبب المرتجع</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-gray-800 dark:text-gray-200">{{ $return->reason }}</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <form method="POST" action="{{ route('returns.destroy', $return) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-300"
                                onclick="return confirm('هل أنت متأكد من حذف هذا المرتجع؟ سيتم خصم الكمية من المخزون.')">
                            <i class="fas fa-trash ml-2"></i>
                            حذف المرتجع
                        </button>
                    </form>
                    
                    <button onclick="window.print()" 
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-print ml-2"></i>
                        طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .bg-white, .bg-white * {
        visibility: visible;
    }
    .bg-white {
        position: absolute;
        left: 0;
        top: 0;
    }
    button {
        display: none !important;
    }
}
</style>
@endsection
