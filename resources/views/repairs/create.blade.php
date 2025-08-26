
@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">أمر صيانة جديد</h1>
        <a href="{{ route('repairs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-right ml-2"></i>
            العودة
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="p-6">
            <form method="POST" action="{{ route('repairs.store') }}">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- بيانات العميل -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اسم العميل *</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('customer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">رقم الهاتف *</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('customer_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- بيانات الجهاز -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع الجهاز *</label>
                        <input type="text" name="device_type" value="{{ old('device_type') }}" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="مثال: iPhone 14 Pro, Samsung Galaxy S23">
                        @error('device_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع الصيانة *</label>
                        <select name="repair_type" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">اختر نوع الصيانة</option>
                            <option value="hardware" {{ old('repair_type') == 'hardware' ? 'selected' : '' }}>صيانة هاردوير</option>
                            <option value="software" {{ old('repair_type') == 'software' ? 'selected' : '' }}>صيانة سوفتوير</option>
                        </select>
                        @error('repair_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تكلفة الصيانة (ر.س) *</label>
                        <input type="number" name="repair_cost" value="{{ old('repair_cost') }}" step="0.01" min="0" required
                               class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('repair_cost')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">وصف المشكلة *</label>
                    <textarea name="problem_description" rows="4" required
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="اكتب وصفاً مفصلاً للمشكلة...">{{ old('problem_description') }}</textarea>
                    @error('problem_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('repairs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                        إلغاء
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                        <i class="fas fa-save ml-2"></i>
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
