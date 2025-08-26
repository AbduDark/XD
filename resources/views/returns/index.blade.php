
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('المرتجعات') }}
            </h2>
            <a href="{{ route('returns.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                إضافة مرتجع جديد
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
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رقم المرتجع</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رقم الفاتورة</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">العميل</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">تاريخ المرتجع</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">المبلغ</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returns as $return)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">#{{ $return->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">#{{ $return->invoice_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ $return->customer_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ $return->return_date }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ number_format($return->total_amount, 2) }} ج.م</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b border-gray-200">
                                        <a href="{{ route('returns.show', $return) }}" class="text-blue-600 hover:text-blue-900 ml-2">عرض</a>
                                        <a href="{{ route('returns.edit', $return) }}" class="text-green-600 hover:text-green-900 ml-2">تعديل</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">لا توجد مرتجعات</td>
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
