
// Import Bootstrap
import './bootstrap';

// Chart.js for dashboard charts
import Chart from 'chart.js/auto';

// Global variables
window.Chart = Chart;

// DOM Content Loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Initialize navigation
    initializeNavigation();
    
    // Initialize dashboard
    if (document.getElementById('salesChart')) {
        initializeDashboard();
    }
    
    // Initialize forms
    initializeForms();
    
    // Initialize modals
    initializeModals();
    
    // Initialize tooltips
    initializeTooltips();
}

// Navigation Functions
function initializeNavigation() {
    // User menu toggle
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenu = document.getElementById('userMenu');
    
    if (userMenuButton && userMenu) {
        userMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenu.contains(e.target) && !userMenuButton.contains(e.target)) {
                userMenu.classList.add('hidden');
            }
        });
    }
    
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
}

// Dashboard Functions
function initializeDashboard() {
    loadDashboardData();
    initializeSalesChart();
}

function loadDashboardData() {
    // Load recent invoices
    fetch('/api/dashboard/recent-invoices')
        .then(response => response.json())
        .then(data => {
            displayRecentInvoices(data);
        })
        .catch(error => {
            console.error('Error loading recent invoices:', error);
        });
    
    // Load recent repairs
    fetch('/api/dashboard/recent-repairs')
        .then(response => response.json())
        .then(data => {
            displayRecentRepairs(data);
        })
        .catch(error => {
            console.error('Error loading recent repairs:', error);
        });
}

function displayRecentInvoices(invoices) {
    const container = document.getElementById('recentInvoices');
    if (!container) return;
    
    if (invoices.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center">لا توجد فواتير حديثة</p>';
        return;
    }
    
    container.innerHTML = invoices.map(invoice => `
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div>
                <p class="font-medium text-gray-900">فاتورة #${invoice.id}</p>
                <p class="text-sm text-gray-600">${invoice.customer_name || 'عميل نقدي'}</p>
            </div>
            <div class="text-left">
                <p class="font-medium text-green-600">${formatCurrency(invoice.total)}</p>
                <p class="text-sm text-gray-500">${formatDate(invoice.created_at)}</p>
            </div>
        </div>
    `).join('');
}

function displayRecentRepairs(repairs) {
    const container = document.getElementById('recentRepairs');
    if (!container) return;
    
    if (repairs.length === 0) {
        container.innerHTML = '<p class="text-gray-500 text-center">لا توجد صيانات حديثة</p>';
        return;
    }
    
    container.innerHTML = repairs.map(repair => `
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div>
                <p class="font-medium text-gray-900">${repair.device_type}</p>
                <p class="text-sm text-gray-600">${repair.customer_name}</p>
            </div>
            <div class="text-left">
                <span class="badge ${getStatusBadgeClass(repair.status)}">${getStatusText(repair.status)}</span>
                <p class="text-sm text-gray-500 mt-1">${formatDate(repair.created_at)}</p>
            </div>
        </div>
    `).join('');
}

function initializeSalesChart() {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;
    
    fetch('/api/dashboard/sales-chart')
        .then(response => response.json())
        .then(data => {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'المبيعات (جنيه)',
                        data: data.sales,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading sales chart:', error);
        });
}

// Form Functions
function initializeForms() {
    // Auto-calculate invoice totals
    const invoiceForms = document.querySelectorAll('.invoice-form');
    invoiceForms.forEach(form => {
        initializeInvoiceForm(form);
    });
    
    // Form validation
    const forms = document.querySelectorAll('form[data-validate="true"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-save drafts
    const draftForms = document.querySelectorAll('form[data-autosave="true"]');
    draftForms.forEach(form => {
        initializeAutoSave(form);
    });
}

function initializeInvoiceForm(form) {
    const itemsContainer = form.querySelector('.invoice-items');
    const addItemBtn = form.querySelector('.add-item-btn');
    const totalInput = form.querySelector('input[name="total"]');
    
    if (!itemsContainer || !addItemBtn) return;
    
    // Add new item
    addItemBtn.addEventListener('click', function() {
        addInvoiceItem(itemsContainer);
        calculateInvoiceTotal(form);
    });
    
    // Remove item
    itemsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.closest('.invoice-item').remove();
            calculateInvoiceTotal(form);
        }
    });
    
    // Calculate total on input change
    itemsContainer.addEventListener('input', function() {
        calculateInvoiceTotal(form);
    });
    
    // Initial calculation
    calculateInvoiceTotal(form);
}

function addInvoiceItem(container) {
    const itemCount = container.children.length;
    const itemHtml = `
        <div class="invoice-item grid grid-cols-1 md:grid-cols-5 gap-4 p-4 border border-gray-200 rounded-lg">
            <div>
                <label class="form-label">المنتج</label>
                <select name="items[${itemCount}][product_id]" class="form-select product-select" required>
                    <option value="">اختر المنتج</option>
                    <!-- Products will be loaded via AJAX -->
                </select>
            </div>
            <div>
                <label class="form-label">الكمية</label>
                <input type="number" name="items[${itemCount}][quantity]" class="form-input quantity-input" min="1" value="1" required>
            </div>
            <div>
                <label class="form-label">السعر</label>
                <input type="number" name="items[${itemCount}][price]" class="form-input price-input" step="0.01" required>
            </div>
            <div>
                <label class="form-label">الإجمالي</label>
                <input type="number" class="form-input item-total" readonly>
            </div>
            <div class="flex items-end">
                <button type="button" class="btn btn-danger remove-item-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    loadProductsForSelect(container.lastElementChild.querySelector('.product-select'));
}

function calculateInvoiceTotal(form) {
    const items = form.querySelectorAll('.invoice-item');
    let total = 0;
    
    items.forEach(item => {
        const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(item.querySelector('.price-input').value) || 0;
        const itemTotal = quantity * price;
        
        item.querySelector('.item-total').value = itemTotal.toFixed(2);
        total += itemTotal;
    });
    
    const totalInput = form.querySelector('input[name="total"]');
    if (totalInput) {
        totalInput.value = total.toFixed(2);
    }
    
    // Update display total
    const totalDisplay = form.querySelector('.total-display');
    if (totalDisplay) {
        totalDisplay.textContent = formatCurrency(total);
    }
}

function loadProductsForSelect(select) {
    fetch('/api/products')
        .then(response => response.json())
        .then(products => {
            products.forEach(product => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} - ${formatCurrency(product.price)}`;
                option.dataset.price = product.price;
                select.appendChild(option);
            });
            
            // Auto-fill price when product is selected
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const priceInput = this.closest('.invoice-item').querySelector('.price-input');
                if (selectedOption.dataset.price && priceInput) {
                    priceInput.value = selectedOption.dataset.price;
                    calculateInvoiceTotal(this.closest('form'));
                }
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
        });
}

// Modal Functions
function initializeModals() {
    // Modal triggers
    document.addEventListener('click', function(e) {
        if (e.target.dataset.modal) {
            showModal(e.target.dataset.modal);
        }
        
        if (e.target.classList.contains('modal-close')) {
            hideModal(e.target.closest('.modal-overlay'));
        }
    });
    
    // Close modal on overlay click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            hideModal(e.target);
        }
    });
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal-overlay:not(.hidden)');
            if (activeModal) {
                hideModal(activeModal);
            }
        }
    });
}

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function hideModal(modal) {
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Tooltip Functions
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(e) {
    const text = e.target.dataset.tooltip;
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip bg-gray-900 text-white text-sm rounded py-1 px-2 absolute z-50 pointer-events-none';
    tooltip.textContent = text;
    
    document.body.appendChild(tooltip);
    
    const rect = e.target.getBoundingClientRect();
    tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
    tooltip.style.left = (rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)) + 'px';
    
    e.target.tooltip = tooltip;
}

function hideTooltip(e) {
    if (e.target.tooltip) {
        e.target.tooltip.remove();
        delete e.target.tooltip;
    }
}

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('ar-EG', {
        style: 'currency',
        currency: 'EGP',
        minimumFractionDigits: 2
    }).format(amount);
}

function formatDate(dateString) {
    return new Intl.DateTimeFormat('ar-EG', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(dateString));
}

function getStatusBadgeClass(status) {
    const statusClasses = {
        'pending': 'badge-warning',
        'in_progress': 'badge-info',
        'completed': 'badge-success',
        'cancelled': 'badge-danger',
        'delivered': 'badge-success'
    };
    return statusClasses[status] || 'badge-secondary';
}

function getStatusText(status) {
    const statusTexts = {
        'pending': 'معلق',
        'in_progress': 'قيد التنفيذ',
        'completed': 'مكتمل',
        'cancelled': 'ملغي',
        'delivered': 'تم التسليم'
    };
    return statusTexts[status] || status;
}

// Toast Notifications
function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} fade-in`;
    
    const icon = getToastIcon(type);
    toast.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="${icon} text-lg"></i>
            </div>
            <div class="mr-3 flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="mr-auto pl-3">
                <button class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after duration
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, duration);
}

function getToastIcon(type) {
    const icons = {
        'success': 'fas fa-check-circle text-green-500',
        'error': 'fas fa-exclamation-circle text-red-500',
        'warning': 'fas fa-exclamation-triangle text-yellow-500',
        'info': 'fas fa-info-circle text-blue-500'
    };
    return icons[type] || icons['info'];
}

// Search functionality
function initializeSearch() {
    const searchInputs = document.querySelectorAll('.search-input');
    
    searchInputs.forEach(input => {
        let searchTimeout;
        
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value, this.dataset.target);
            }, 300);
        });
    });
}

function performSearch(query, target) {
    const targetElement = document.getElementById(target);
    if (!targetElement) return;
    
    // Add loading state
    targetElement.classList.add('opacity-50');
    
    // Perform search via AJAX
    fetch(`/api/search?q=${encodeURIComponent(query)}&target=${target}`)
        .then(response => response.json())
        .then(data => {
            updateSearchResults(targetElement, data);
        })
        .catch(error => {
            console.error('Search error:', error);
            showToast('حدث خطأ أثناء البحث', 'error');
        })
        .finally(() => {
            targetElement.classList.remove('opacity-50');
        });
}

function updateSearchResults(container, data) {
    // Update container with new results
    // Implementation depends on the specific use case
    container.innerHTML = data.html || '';
}

// Print functionality
function printElement(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="utf-8">
            <title>طباعة</title>
            <style>
                ${document.querySelector('link[href*="style.css"]')?.textContent || ''}
                body { font-family: Arial, sans-serif; }
                .no-print { display: none !important; }
            </style>
        </head>
        <body>
            ${element.innerHTML}
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
}

// Auto-save functionality
function initializeAutoSave(form) {
    const formData = new FormData(form);
    const formKey = `autosave_${form.id || 'form'}`;
    
    // Load saved data
    loadAutoSavedData(form, formKey);
    
    // Save on input change
    form.addEventListener('input', debounce(() => {
        saveFormData(form, formKey);
    }, 1000));
}

function saveFormData(form, key) {
    const formData = new FormData(form);
    const data = {};
    
    for (let [name, value] of formData.entries()) {
        data[name] = value;
    }
    
    localStorage.setItem(key, JSON.stringify(data));
}

function loadAutoSavedData(form, key) {
    const savedData = localStorage.getItem(key);
    if (!savedData) return;
    
    try {
        const data = JSON.parse(savedData);
        
        Object.entries(data).forEach(([name, value]) => {
            const input = form.querySelector(`[name="${name}"]`);
            if (input) {
                input.value = value;
            }
        });
    } catch (error) {
        console.error('Error loading auto-saved data:', error);
    }
}

function clearAutoSavedData(formKey) {
    localStorage.removeItem(formKey);
}

// Debounce utility
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

// Form validation
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'هذا الحقل مطلوب');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });
    
    return isValid;
}

function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('border-red-500');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-red-500 text-sm mt-1';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('border-red-500');
    
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Export functions for global use
window.showToast = showToast;
window.showModal = showModal;
window.hideModal = hideModal;
window.printElement = printElement;
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;
