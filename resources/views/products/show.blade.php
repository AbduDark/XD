
@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('تفاصيل المنتج') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold">{{ $product->name_ar }}</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                            <i class="fas fa-edit ml-2"></i>
                            تعديل
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right ml-2"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>

                <!-- Product Details Card -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-lg font-semibold">معلومات المنتج</h4>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">الاسم باللغة العربية</label>
                                <p class="text-lg font-semibold">{{ $product->name_ar }}</p>
                            </div>

                            <div>
                                <label class="form-label">الاسم باللغة الإنجليزية</label>
                                <p class="text-lg">{{ $product->name_en ?: 'غير محدد' }}</p>
                            </div>

                            <div>
                                <label class="form-label">الكود</label>
                                <p class="text-lg font-mono">{{ $product->code ?: 'غير محدد' }}</p>
                            </div>

                            <div>
                                <label class="form-label">الفئة</label>
                                <span class="badge badge-info text-lg">
                                    {{ $product->category->name_ar ?? 'غير محدد' }}
                                </span>
                            </div>

                            <div>
                                <label class="form-label">سعر الشراء</label>
                                <p class="text-lg font-bold text-blue-600">
                                    {{ number_format($product->purchase_price, 2) }} جنيه
                                </p>
                            </div>

                            <div>
                                <label class="form-label">سعر البيع</label>
                                <p class="text-lg font-bold text-green-600">
                                    {{ number_format($product->selling_price, 2) }} جنيه
                                </p>
                            </div>

                            <div>
                                <label class="form-label">الكمية المتاحة</label>
                                <span class="text-lg font-bold {{ $product->isLowStock() ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $product->quantity }}
                                    @if($product->isLowStock())
                                        <i class="fas fa-exclamation-triangle text-red-600 ml-2"></i>
                                        <span class="text-sm text-red-600">(مخزون منخفض)</span>
                                    @endif
                                </span>
                            </div>

                            <div>
                                <label class="form-label">الحد الأدنى للمخزون</label>
                                <p class="text-lg">{{ $product->min_quantity ?? 0 }}</p>
                            </div>

                            <div>
                                <label class="form-label">هامش الربح</label>
                                <p class="text-lg font-bold text-purple-600">
                                    {{ number_format($product->getProfit(), 2) }} جنيه
                                    <span class="text-sm text-gray-600">
                                        ({{ number_format(($product->getProfit() / $product->purchase_price) * 100, 1) }}%)
                                    </span>
                                </p>
                            </div>

                            <div>
                                <label class="form-label">تاريخ الإنشاء</label>
                                <p class="text-lg">{{ $product->created_at->format('Y-m-d H:i') }}</p>
                            </div>

                            @if($product->description)
                            <div class="md:col-span-2">
                                <label class="form-label">الوصف</label>
                                <p class="text-lg bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    {{ $product->description }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="card mt-6">
                    <div class="card-header">
                        <h4 class="text-lg font-semibold">إحصائيات المنتج</h4>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="stats-card">
                                <div class="stats-icon bg-blue-500">
                                    <i class="fas fa-shopping-cart text-white"></i>
                                </div>
                                <div class="stats-value">{{ $product->invoiceItems->count() }}</div>
                                <div class="stats-label">مرات البيع</div>
                            </div>

                            <div class="stats-card">
                                <div class="stats-icon bg-green-500">
                                    <i class="fas fa-coins text-white"></i>
                                </div>
                                <div class="stats-value">
                                    {{ number_format($product->invoiceItems->sum('total'), 0) }}
                                </div>
                                <div class="stats-label">إجمالي المبيعات (جنيه)</div>
                            </div>

                            <div class="stats-card">
                                <div class="stats-icon bg-yellow-500">
                                    <i class="fas fa-boxes text-white"></i>
                                </div>
                                <div class="stats-value">
                                    {{ $product->invoiceItems->sum('quantity') }}
                                </div>
                                <div class="stats-label">الكمية المباعة</div>
                            </div>

                            <div class="stats-card">
                                <div class="stats-icon bg-purple-500">
                                    <i class="fas fa-chart-line text-white"></i>
                                </div>
                                <div class="stats-value">
                                    {{ number_format($product->invoiceItems->sum('total') - ($product->invoiceItems->sum('quantity') * $product->purchase_price), 0) }}
                                </div>
                                <div class="stats-label">إجمالي الأرباح (جنيه)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center gap-4 mt-6">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                        <i class="fas fa-edit ml-2"></i>
                        تعديل المنتج
                    </a>
                    <button type="button" onclick="confirmDelete({{ $product->id }}, '{{ $product->name_ar }}')" class="btn btn-danger">
                        <i class="fas fa-trash ml-2"></i>
                        حذف المنتج
                    </button>
                    <button onclick="printElement('productDetails')" class="btn btn-info">
                        <i class="fas fa-print ml-2"></i>
                        طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay hidden">
    <div class="modal-content max-w-md">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-center mb-2">تأكيد حذف المنتج</h3>
            <p class="text-gray-600 text-center mb-6">
                هل أنت متأكد من حذف المنتج "<span id="productName" class="font-semibold"></span>"؟
            </p>
            <div class="flex gap-4 justify-center">
                <button type="button" onclick="hideModal(document.getElementById('deleteModal'))" class="btn btn-secondary">
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" action="{{ route('products.destroy', $product) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('هل أنت متأكد من حذف المنتج؟')">
                        حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(productId, productName) {
    document.getElementById('productName').textContent = productName;
    document.getElementById('deleteForm').action = '/products/' + productId;
    showModal('deleteModal');
}
</script>
@endsection
