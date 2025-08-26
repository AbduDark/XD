
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">إضافة منتج جديد</h1>
                    <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        العودة للقائمة
                    </a>
                </div>

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">الاسم بالعربية</label>
                            <input type="text" name="name_ar" value="{{ old('name_ar') }}" class="w-full border rounded px-3 py-2" required>
                            @error('name_ar')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">الاسم بالإنجليزية</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">كود المنتج</label>
                            <input type="text" name="code" value="{{ old('code') }}" class="w-full border rounded px-3 py-2" required>
                            @error('code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">الفئة</label>
                            <select name="category_id" class="w-full border rounded px-3 py-2" required>
                                <option value="">اختر الفئة</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">سعر الشراء</label>
                            <input type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}" class="w-full border rounded px-3 py-2" required>
                            @error('purchase_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">سعر البيع</label>
                            <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price') }}" class="w-full border rounded px-3 py-2" required>
                            @error('selling_price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">الكمية</label>
                            <input type="number" name="quantity" value="{{ old('quantity') }}" class="w-full border rounded px-3 py-2" required>
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">الحد الأدنى للكمية</label>
                            <input type="number" name="min_quantity" value="{{ old('min_quantity') }}" class="w-full border rounded px-3 py-2" required>
                            @error('min_quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            حفظ المنتج
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
