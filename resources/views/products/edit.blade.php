
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('تعديل المنتج') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold">تعديل المنتج: {{ $product->name_ar }}</h3>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right ml-2"></i>
                        العودة للقائمة
                    </a>
                </div>

                <!-- Edit Form -->
                <form method="POST" action="{{ route('products.update', $product) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Arabic Name -->
                        <div class="form-group">
                            <label for="name_ar" class="form-label">الاسم باللغة العربية *</label>
                            <input type="text" name="name_ar" id="name_ar" 
                                   class="form-input @error('name_ar') border-red-500 @enderror" 
                                   value="{{ old('name_ar', $product->name_ar) }}" required>
                            @error('name_ar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- English Name -->
                        <div class="form-group">
                            <label for="name_en" class="form-label">الاسم باللغة الإنجليزية</label>
                            <input type="text" name="name_en" id="name_en" 
                                   class="form-input @error('name_en') border-red-500 @enderror" 
                                   value="{{ old('name_en', $product->name_en) }}">
                            @error('name_en')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Code -->
                        <div class="form-group">
                            <label for="code" class="form-label">كود المنتج</label>
                            <input type="text" name="code" id="code" 
                                   class="form-input @error('code') border-red-500 @enderror" 
                                   value="{{ old('code', $product->code) }}">
                            @error('code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="form-group">
                            <label for="category_id" class="form-label">الفئة *</label>
                            <select name="category_id" id="category_id" 
                                    class="form-select @error('category_id') border-red-500 @enderror" required>
                                <option value="">اختر الفئة</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Purchase Price -->
                        <div class="form-group">
                            <label for="purchase_price" class="form-label">سعر الشراء (جنيه) *</label>
                            <input type="number" name="purchase_price" id="purchase_price" step="0.01" min="0"
                                   class="form-input @error('purchase_price') border-red-500 @enderror" 
                                   value="{{ old('purchase_price', $product->purchase_price) }}" required>
                            @error('purchase_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Selling Price -->
                        <div class="form-group">
                            <label for="selling_price" class="form-label">سعر البيع (جنيه) *</label>
                            <input type="number" name="selling_price" id="selling_price" step="0.01" min="0"
                                   class="form-input @error('selling_price') border-red-500 @enderror" 
                                   value="{{ old('selling_price', $product->selling_price) }}" required>
                            @error('selling_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="form-group">
                            <label for="quantity" class="form-label">الكمية *</label>
                            <input type="number" name="quantity" id="quantity" min="0"
                                   class="form-input @error('quantity') border-red-500 @enderror" 
                                   value="{{ old('quantity', $product->quantity) }}" required>
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Minimum Quantity -->
                        <div class="form-group">
                            <label for="min_quantity" class="form-label">الحد الأدنى للمخزون</label>
                            <input type="number" name="min_quantity" id="min_quantity" min="0"
                                   class="form-input @error('min_quantity') border-red-500 @enderror" 
                                   value="{{ old('min_quantity', $product->min_quantity) }}">
                            @error('min_quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="form-textarea @error('description') border-red-500 @enderror" 
                                  placeholder="وصف تفصيلي للمنتج...">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Profit Calculation Display -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">معاينة الأرباح</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">هامش الربح:</span>
                                <span id="profitMargin" class="font-bold text-green-600">0.00 جنيه</span>
                            </div>
                            <div>
                                <span class="text-gray-600">نسبة الربح:</span>
                                <span id="profitPercentage" class="font-bold text-blue-600">0%</span>
                            </div>
                            <div>
                                <span class="text-gray-600">إجمالي قيمة المخزون:</span>
                                <span id="totalValue" class="font-bold text-purple-600">0.00 جنيه</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex gap-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save ml-2"></i>
                                حفظ التعديلات
                            </button>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
                                إلغاء
                            </a>
                        </div>
                        
                        <div class="text-sm text-gray-500">
                            آخر تحديث: {{ $product->updated_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const purchasePriceInput = document.getElementById('purchase_price');
    const sellingPriceInput = document.getElementById('selling_price');
    const quantityInput = document.getElementById('quantity');

    function calculateProfit() {
        const purchasePrice = parseFloat(purchasePriceInput.value) || 0;
        const sellingPrice = parseFloat(sellingPriceInput.value) || 0;
        const quantity = parseInt(quantityInput.value) || 0;

        const profitMargin = sellingPrice - purchasePrice;
        const profitPercentage = purchasePrice > 0 ? (profitMargin / purchasePrice * 100) : 0;
        const totalValue = sellingPrice * quantity;

        document.getElementById('profitMargin').textContent = profitMargin.toFixed(2) + ' جنيه';
        document.getElementById('profitPercentage').textContent = profitPercentage.toFixed(1) + '%';
        document.getElementById('totalValue').textContent = totalValue.toFixed(2) + ' جنيه';

        // Update colors based on profit
        const profitMarginEl = document.getElementById('profitMargin');
        if (profitMargin > 0) {
            profitMarginEl.className = 'font-bold text-green-600';
        } else if (profitMargin < 0) {
            profitMarginEl.className = 'font-bold text-red-600';
        } else {
            profitMarginEl.className = 'font-bold text-gray-600';
        }
    }

    // Calculate profit on input change
    [purchasePriceInput, sellingPriceInput, quantityInput].forEach(input => {
        input.addEventListener('input', calculateProfit);
    });

    // Initial calculation
    calculateProfit();

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const purchasePrice = parseFloat(purchasePriceInput.value) || 0;
        const sellingPrice = parseFloat(sellingPriceInput.value) || 0;

        if (sellingPrice <= purchasePrice) {
            if (!confirm('سعر البيع أقل من أو يساوي سعر الشراء. هل تريد المتابعة؟')) {
                e.preventDefault();
            }
        }
    });
});
</script>
@endsection
