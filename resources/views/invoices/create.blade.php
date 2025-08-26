
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">فاتورة جديدة</h1>
                    <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        العودة للقائمة
                    </a>
                </div>

                <form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
                    @csrf
                    
                    <!-- Customer Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">اسم العميل (اختياري)</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">هاتف العميل (اختياري)</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="w-full border rounded px-3 py-2">
                        </div>
                    </div>

                    <!-- Products Search -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">البحث عن المنتجات</label>
                        <input type="text" id="product-search" placeholder="ابحث عن منتج..." class="w-full border rounded px-3 py-2">
                        <div id="search-results" class="mt-2 bg-white border rounded shadow-lg hidden"></div>
                    </div>

                    <!-- Selected Products -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-4">المنتجات المحددة</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto" id="products-table">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th class="px-4 py-2 text-right">المنتج</th>
                                        <th class="px-4 py-2 text-right">السعر</th>
                                        <th class="px-4 py-2 text-right">الكمية</th>
                                        <th class="px-4 py-2 text-right">المجموع</th>
                                        <th class="px-4 py-2 text-right">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="products-tbody">
                                    <!-- Products will be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Invoice Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">المجموع الفرعي</label>
                            <input type="text" id="subtotal" readonly class="w-full border rounded px-3 py-2 bg-gray-100" value="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">الخصم</label>
                            <input type="number" step="0.01" name="discount" id="discount" value="0" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">المجموع النهائي</label>
                            <input type="text" id="total" readonly class="w-full border rounded px-3 py-2 bg-gray-100 font-bold" value="0">
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            حفظ الفاتورة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let selectedProducts = [];
let productIndex = 0;

document.getElementById('product-search').addEventListener('input', function() {
    const searchTerm = this.value;
    if (searchTerm.length < 2) {
        document.getElementById('search-results').classList.add('hidden');
        return;
    }

    fetch(`/products/search/api?term=${searchTerm}`)
        .then(response => response.json())
        .then(products => {
            const resultsDiv = document.getElementById('search-results');
            resultsDiv.innerHTML = '';
            
            if (products.length > 0) {
                products.forEach(product => {
                    const div = document.createElement('div');
                    div.className = 'p-2 hover:bg-gray-100 cursor-pointer border-b';
                    div.innerHTML = `
                        <div class="font-bold">${product.name_ar}</div>
                        <div class="text-sm text-gray-600">كود: ${product.code} - السعر: ${product.selling_price} جنيه - المتوفر: ${product.quantity}</div>
                    `;
                    div.addEventListener('click', () => addProduct(product));
                    resultsDiv.appendChild(div);
                });
                resultsDiv.classList.remove('hidden');
            } else {
                resultsDiv.classList.add('hidden');
            }
        });
});

function addProduct(product) {
    if (selectedProducts.find(p => p.id === product.id)) {
        alert('هذا المنتج مضاف بالفعل');
        return;
    }

    selectedProducts.push({
        id: product.id,
        name_ar: product.name_ar,
        selling_price: product.selling_price,
        available_quantity: product.quantity,
        quantity: 1
    });

    renderProducts();
    document.getElementById('search-results').classList.add('hidden');
    document.getElementById('product-search').value = '';
}

function removeProduct(index) {
    selectedProducts.splice(index, 1);
    renderProducts();
}

function updateQuantity(index, quantity) {
    const product = selectedProducts[index];
    if (quantity > product.available_quantity) {
        alert(`الكمية المتوفرة: ${product.available_quantity}`);
        return;
    }
    selectedProducts[index].quantity = parseInt(quantity);
    updateTotals();
}

function renderProducts() {
    const tbody = document.getElementById('products-tbody');
    tbody.innerHTML = '';

    selectedProducts.forEach((product, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-4 py-2">${product.name_ar}</td>
            <td class="px-4 py-2">${product.selling_price} جنيه</td>
            <td class="px-4 py-2">
                <input type="number" min="1" max="${product.available_quantity}" value="${product.quantity}" 
                       onchange="updateQuantity(${index}, this.value)" class="w-20 border rounded px-2 py-1">
                <input type="hidden" name="items[${index}][product_id]" value="${product.id}">
                <input type="hidden" name="items[${index}][quantity]" value="${product.quantity}">
            </td>
            <td class="px-4 py-2">${(product.selling_price * product.quantity).toFixed(2)} جنيه</td>
            <td class="px-4 py-2">
                <button type="button" onclick="removeProduct(${index})" class="text-red-600 hover:text-red-900">حذف</button>
            </td>
        `;
        tbody.appendChild(row);
    });

    updateTotals();
}

function updateTotals() {
    const subtotal = selectedProducts.reduce((total, product) => {
        return total + (product.selling_price * product.quantity);
    }, 0);

    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const total = subtotal - discount;

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);

    // Update hidden inputs for quantities
    selectedProducts.forEach((product, index) => {
        const quantityInput = document.querySelector(`input[name="items[${index}][quantity]"]`);
        if (quantityInput) {
            quantityInput.value = product.quantity;
        }
    });
}

document.getElementById('discount').addEventListener('input', updateTotals);

document.getElementById('invoice-form').addEventListener('submit', function(e) {
    if (selectedProducts.length === 0) {
        e.preventDefault();
        alert('يجب إضافة منتج واحد على الأقل');
    }
});
</script>
@endsection
