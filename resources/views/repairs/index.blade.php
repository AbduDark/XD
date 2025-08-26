
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('الصيانة') }}
            </h2>
            <a href="{{ route('repairs.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                إضافة صيانة جديدة
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رقم الصيانة</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">اسم العميل</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نوع الجهاز</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الحالة</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">تكلفة الصيانة</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repairs as $repair)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">#{{ $repair->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ $repair->customer_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ $repair->device_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($repair->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($repair->status == 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($repair->status == 'completed') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($repair->status == 'pending') قيد الانتظار
                                            @elseif($repair->status == 'in_progress') قيد التنفيذ
                                            @elseif($repair->status == 'completed') مكتمل
                                            @else ملغي @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ number_format($repair->cost, 2) }} ج.م</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b border-gray-200">
                                        <a href="{{ route('repairs.show', $repair) }}" class="text-blue-600 hover:text-blue-900 ml-2">عرض</a>
                                        <a href="{{ route('repairs.edit', $repair) }}" class="text-green-600 hover:text-green-900 ml-2">تعديل</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">لا توجد أعمال صيانة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
