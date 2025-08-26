@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('المنتجات') }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                <!-- Header with Add Button -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">إدارة المنتجات</h3>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة منتج جديد
                    </a>
                </div>

                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('products.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="البحث عن منتج..." 
                                   class="form-input w-full">
                        </div>
                        <div>
                            <select name="category" class="form-select w-full">
                                <option value="">جميع الفئات</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search ml-2"></i>
                                بحث
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh ml-2"></i>
                                إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>الكود</th>
                                <th>الاسم العربي</th>
                                <th>الاسم الإنجليزي</th>
                                <th>الفئة</th>
                                <th>سعر الشراء</th>
                                <th>سعر البيع</th>
                                <th>الكمية</th>
                                <th>الحد الأدنى</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="{{ $product->isLowStock() ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                <td>{{ $product->code ?: 'غير محدد' }}</td>
                                <td class="font-semibold">{{ $product->name_ar }}</td>
                                <td>{{ $product->name_en ?: 'غير محدد' }}</td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $product->category->name_ar ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td>{{ number_format($product->purchase_price, 2) }} جنيه</td>
                                <td>{{ number_format($product->selling_price, 2) }} جنيه</td>
                                <td>
                                    <span class="{{ $product->isLowStock() ? 'badge badge-danger' : 'badge badge-success' }}">
                                        {{ $product->quantity }}
                                    </span>
                                </td>
                                <td>{{ $product->min_quantity ?? 0 }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="{{ route('products.show', $product) }}" 
                                           class="btn btn-info btn-sm" 
                                           title="عرض المنتج"
                                           data-tooltip="عرض تفاصيل المنتج">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" 
                                           class="btn btn-warning btn-sm" 
                                           title="تعديل المنتج"
                                           data-tooltip="تعديل بيانات المنتج">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                onclick="confirmDelete({{ $product->id }}, '{{ $product->name_ar }}')"
                                                class="btn btn-danger btn-sm" 
                                                title="حذف المنتج"
                                                data-tooltip="حذف المنتج نهائياً">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500">لا توجد منتجات متاحة</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary mt-4">
                                            إضافة منتج جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $products->links() }}
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
                <br>
                <span class="text-red-600 text-sm">لا يمكن التراجع عن هذا الإجراء!</span>
            </p>
            <div class="flex gap-4 justify-center">
                <button type="button" onclick="hideModal(document.getElementById('deleteModal'))" class="btn btn-secondary">
                    إلغاء
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash ml-2"></i>
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
    
    // Show modal using the global function
    if (typeof showModal === 'function') {
        showModal('deleteModal');
    } else {
        // Fallback if showModal is not available
        document.getElementById('deleteModal').classList.remove('hidden');
    }
}

// Show success/error messages only if functions are available
document.addEventListener('DOMContentLoaded', function() {
    // Show messages
    @if(session('success'))
        if (typeof showToast === 'function') {
            showToast('{{ session('success') }}', 'success');
        }
    @endif

    @if(session('error'))
        if (typeof showToast === 'function') {
            showToast('{{ session('error') }}', 'error');
        }
    @endif

    // Add search functionality
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            if (this.value.length >= 3 || this.value.length === 0) {
                this.form.submit();
            }
        }, 500));
    }
});

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Manual modal functions if global ones are not available
function hideModalManual(modalElement) {
    if (modalElement) {
        modalElement.classList.add('hidden');
    }
}

// Override the hideModal call in the modal
document.addEventListener('DOMContentLoaded', function() {
    const cancelButton = document.querySelector('#deleteModal button[onclick*="hideModal"]');
    if (cancelButton) {
        cancelButton.onclick = function() {
            if (typeof hideModal === 'function') {
                hideModal(document.getElementById('deleteModal'));
            } else {
                hideModalManual(document.getElementById('deleteModal'));
            }
        };
    }
});
</script>
@endsection