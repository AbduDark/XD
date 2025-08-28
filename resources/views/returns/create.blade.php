
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">إضافة مرتجع جديد</h1>
            <a href="{{ route('returns.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للمرتجعات
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <form method="POST" action="{{ route('returns.store') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اختيار المنتج -->
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            المنتج <span class="text-red-500">*</span>
                        </label>
                        <select name="product_id" id="product_id" required 
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">اختر المنتج</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name_ar }} - {{ number_format($product->price, 2) }} ر.س
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- الكمية -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            الكمية <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" min="1" value="{{ old('quantity', 1) }}" required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- المبلغ -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            المبلغ (ر.س) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount') }}" required
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- سبب المرتجع -->
                <div class="mt-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        سبب المرتجع <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" id="reason" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="اذكر سبب المرتجع...">{{ old('reason') }}</textarea>
                    @error('reason')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- أزرار الإجراءات -->
                <div class="flex justify-end space-x-4 mt-8">
                    <button type="button" onclick="window.history.back()" 
                            class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-300">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                        <i class="fas fa-save ml-2"></i>
                        حفظ المرتجع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity');
    const amountInput = document.getElementById('amount');

    // تحديث المبلغ تلقائياً عند تغيير المنتج أو الكمية
    function updateAmount() {
        const selectedOption = productSelect.selectedOptions[0];
        if (selectedOption && selectedOption.value) {
            const priceText = selectedOption.text;
            const price = parseFloat(priceText.split(' - ')[1].replace(' ر.س', '').replace(',', ''));
            const quantity = parseInt(quantityInput.value) || 1;
            
            if (price && quantity) {
                amountInput.value = (price * quantity).toFixed(2);
            }
        }
    }

    productSelect.addEventListener('change', updateAmount);
    quantityInput.addEventListener('input', updateAmount);
});
</script>
@endsection
