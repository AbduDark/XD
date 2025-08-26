
@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">إدارة الصيانة</h1>
        <a href="{{ route('repairs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus ml-2"></i>
            أمر صيانة جديد
        </a>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">في الانتظار</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $repairs->where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fas fa-cog text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">قيد العمل</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $repairs->where('status', 'in_progress')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <i class="fas fa-check text-green-600 dark:text-green-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">مكتملة</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $repairs->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <i class="fas fa-hand-holding text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">مُسلمة</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $repairs->where('status', 'delivered')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول الصيانة -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">أوامر الصيانة</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">رقم الصيانة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">العميل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">الهاتف</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">نوع الجهاز</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">التكلفة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($repairs as $repair)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $repair->repair_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $repair->customer_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $repair->customer_phone }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $repair->device_type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($repair->repair_cost, 2) }} ر.س
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($repair->status == 'pending')
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">في الانتظار</span>
                            @elseif($repair->status == 'in_progress')
                                <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">قيد العمل</span>
                            @elseif($repair->status == 'completed')
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">مكتملة</span>
                            @elseif($repair->status == 'delivered')
                                <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full">مُسلمة</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                            {{ $repair->created_at->format('Y/m/d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex gap-2">
                                <a href="{{ route('repairs.show', $repair) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('repairs.edit', $repair) }}" class="text-yellow-600 hover:text-yellow-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('repairs.destroy', $repair) }}" class="inline" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف أمر الصيانة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $repairs->links() }}
        </div>
    </div>
</div>
@endsection
