
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('حركة النقدية') }}
            </h2>
            <a href="{{ route('cash-transfers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                إضافة حركة نقدية
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
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">رقم العملية</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">نوع العملية</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">المبلغ</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">الوصف</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">التاريخ</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transfers as $transfer)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">#{{ $transfer->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($transfer->type == 'income') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $transfer->type == 'income' ? 'إيراد' : 'مصروف' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ number_format($transfer->amount, 2) }} ج.م</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ $transfer->description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-b border-gray-200">{{ $transfer->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b border-gray-200">
                                        <a href="{{ route('cash-transfers.edit', $transfer) }}" class="text-green-600 hover:text-green-900 ml-2">تعديل</a>
                                        <form method="POST" action="{{ route('cash-transfers.destroy', $transfer) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">لا توجد حركات نقدية</td>
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
