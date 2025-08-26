
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">إدارة المنتجات</h1>
                    <a href="{{ route('products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        إضافة منتج جديد
                    </a>
                </div>

                <!-- Search Form -->
                <form method="GET" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="البحث..." class="border rounded px-3 py-2">
                        <select name="category_id" class="border rounded px-3 py-2">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ar }}
                                </option>
                            @endforeach
                        </select>
                        <label class="flex items-center">
                            <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} class="mr-2">
                            منتجات قاربت النفاد
                        </label>
                        <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            بحث
                        </button>
                    </div>
                </form>

                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكود</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سعر الشراء</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سعر البيع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                            @foreach($products as $product)
                            <tr class="{{ $product->quantity <= $product->min_quantity ? 'bg-red-50 dark:bg-red-900' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $product->code }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->name_ar }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->category->name_ar ?? 'غير محدد' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($product->purchase_price) }} جنيه</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ number_format($product->selling_price) }} جنيه</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="{{ $product->quantity <= $product->min_quantity ? 'text-red-600 font-bold' : '' }}">
                                        {{ $product->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-900 mr-3">عرض</a>
                                    <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">تعديل</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد؟')">حذف</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
