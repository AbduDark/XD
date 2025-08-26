
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إضافة مرتجع جديد') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('returns.store') }}" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="invoice_id" :value="__('رقم الفاتورة')" />
                                <select id="invoice_id" name="invoice_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="">اختر فاتورة</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}">#{{ $invoice->id }} - {{ $invoice->customer_name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('invoice_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="return_date" :value="__('تاريخ المرتجع')" />
                                <x-text-input id="return_date" name="return_date" type="date" class="mt-1 block w-full" value="{{ date('Y-m-d') }}" required />
                                <x-input-error :messages="$errors->get('return_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="reason" :value="__('سبب المرتجع')" />
                                <textarea id="reason" name="reason" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                                <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="total_amount" :value="__('المبلغ المرتجع')" />
                                <x-text-input id="total_amount" name="total_amount" type="number" step="0.01" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('total_amount')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('returns.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-3">
                                إلغاء
                            </a>
                            <x-primary-button>
                                {{ __('حفظ المرتجع') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
