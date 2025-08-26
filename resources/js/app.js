// Import Bootstrap
import './bootstrap';

// Chart.js for dashboard charts
import Chart from 'chart.js/auto';

// Global variables
window.Chart = Chart;

// Initialize application
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

    // Initialize search
    initializeSearch();

    // Update statistics every 30 seconds
    if (document.querySelector('.dashboard-stats')) {
        setInterval(updateDashboardStats, 30000);
    }
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

    // Mobile menu toggle (kept for tablet support)
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
    initializeRealtimeUpdates();
}

function loadDashboardData() {
    // Load recent invoices
    fetch('/api/dashboard/recent-invoices', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        displayRecentInvoices(data);
    })
    .catch(error => {
        console.error('Error loading recent invoices:', error);
        displayRecentInvoices([]);
    });

    // Load recent repairs
    fetch('/api/dashboard/recent-repairs', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        displayRecentRepairs(data);
    })
    .catch(error => {
        console.error('Error loading recent repairs:', error);
        displayRecentRepairs([]);
    });
}

function displayRecentInvoices(invoices) {
    const container = document.getElementById('recentInvoices');
    if (!container) return;

    if (invoices.length === 0) {
        container.innerHTML = '<div class="text-center py-8"><i class="fas fa-receipt text-gray-300 text-4xl mb-4"></i><p class="text-gray-500">لا توجد فواتير حديثة</p></div>';
        return;
    }

    container.innerHTML = invoices.map(invoice => `
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 border border-blue-100">
            <div class="flex items-center">
                <div class="p-2 bg-blue-500 rounded-full ml-3">
                    <i class="fas fa-receipt text-white text-sm"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">فاتورة #${invoice.id}</p>
                    <p class="text-sm text-gray-600">${invoice.customer_name || 'عميل نقدي'}</p>
                </div>
            </div>
            <div class="text-left">
                <p class="font-bold text-green-600 text-lg">${formatCurrency(invoice.total)}</p>
                <p class="text-xs text-gray-500">${formatDate(invoice.created_at)}</p>
            </div>
        </div>
    `).join('');
}

function displayRecentRepairs(repairs) {
    const container = document.getElementById('recentRepairs');
    if (!container) return;

    if (repairs.length === 0) {
        container.innerHTML = '<div class="text-center py-8"><i class="fas fa-tools text-gray-300 text-4xl mb-4"></i><p class="text-gray-500">لا توجد صيانات حديثة</p></div>';
        return;
    }

    container.innerHTML = repairs.map(repair => `
        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg hover:from-yellow-100 hover:to-orange-100 transition-all duration-200 border border-yellow-100">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-500 rounded-full ml-3">
                    <i class="fas fa-tools text-white text-sm"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">${repair.device_type}</p>
                    <p class="text-sm text-gray-600">${repair.customer_name}</p>
                </div>
            </div>
            <div class="text-left">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(repair.status)}">${getStatusText(repair.status)}</span>
                <p class="text-xs text-gray-500 mt-1">${formatDate(repair.created_at)}</p>
            </div>
        </div>
    `).join('');
}

function initializeSalesChart() {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    fetch('/api/dashboard/sales-chart', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [{
                    label: 'المبيعات (جنيه)',
                    data: data.sales || [],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Cairo',
                                size: 14
                            }
                        }
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
                            },
                            font: {
                                family: 'Cairo'
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Cairo'
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error('Error loading sales chart:', error);
        ctx.parentElement.innerHTML = '<div class="flex items-center justify-center h-64"><p class="text-gray-500">خطأ في تحميل الرسم البياني</p></div>';
    });
}

function initializeRealtimeUpdates() {
    // Update dashboard stats every 30 seconds
    setInterval(() => {
        updateDashboardStats();
    }, 30000);
}

function updateDashboardStats() {
    fetch('/api/dashboard/stats', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update stats with animation
        animateStatUpdate('totalProducts', data.totalProducts);
        animateStatUpdate('todaySales', formatCurrency(data.todaySales));
        animateStatUpdate('pendingRepairs', data.pendingRepairs);
        animateStatUpdate('lowStock', data.lowStock);
    })
    .catch(error => {
        console.error('Error updating dashboard stats:', error);
    });
}

function animateStatUpdate(elementId, newValue) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.transform = 'scale(1.1)';
        element.style.transition = 'transform 0.3s ease';
        setTimeout(() => {
            element.textContent = newValue;
            element.style.transform = 'scale(1)';
        }, 150);
    }
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

    // Initialize product search with autocomplete
    initializeProductSearch();
}

function initializeProductSearch() {
    const productSearchInputs = document.querySelectorAll('.product-search');
    productSearchInputs.forEach(input => {
        let searchTimeout;
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchProducts(this.value, this);
            }, 300);
        });
    });
}

function searchProducts(query, inputElement) {
    if (query.length < 2) return;

    fetch(`/api/products/search?q=${encodeURIComponent(query)}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(products => {
        showProductSuggestions(products, inputElement);
    })
    .catch(error => {
        console.error('Error searching products:', error);
    });
}

function showProductSuggestions(products, inputElement) {
    // Remove existing suggestions
    const existingSuggestions = document.querySelector('.product-suggestions');
    if (existingSuggestions) {
        existingSuggestions.remove();
    }

    if (products.length === 0) return;

    const suggestions = document.createElement('div');
    suggestions.className = 'product-suggestions absolute bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1';

    suggestions.innerHTML = products.map(product => `
        <div class="product-suggestion flex items-center justify-between p-3 hover:bg-blue-50 cursor-pointer border-b border-gray-100" data-product-id="${product.id}" data-product-name="${product.name}" data-product-price="${product.price}">
            <div>
                <p class="font-medium text-gray-900">${product.name}</p>
                <p class="text-sm text-gray-600">المتاح: ${product.quantity}</p>
            </div>
            <p class="font-bold text-blue-600">${formatCurrency(product.price)}</p>
        </div>
    `).join('');

    inputElement.parentElement.style.position = 'relative';
    inputElement.parentElement.appendChild(suggestions);

    // Handle suggestion clicks
    suggestions.addEventListener('click', function(e) {
        const suggestion = e.target.closest('.product-suggestion');
        if (suggestion) {
            inputElement.value = suggestion.dataset.productName;
            suggestions.remove();

            // Trigger product selection event
            const event = new CustomEvent('productSelected', {
                detail: {
                    id: suggestion.dataset.productId,
                    name: suggestion.dataset.productName,
                    price: suggestion.dataset.productPrice
                }
            });
            inputElement.dispatchEvent(event);
        }
    });
}

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('ar-EG', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount) + ' جنيه';
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
        'pending': 'bg-yellow-100 text-yellow-800',
        'in_progress': 'bg-blue-100 text-blue-800',
        'completed': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800',
        'delivered': 'bg-green-100 text-green-800'
    };
    return statusClasses[status] || 'bg-gray-100 text-gray-800';
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
    if (!container) {
        // Create toast container if it doesn't exist
        const toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'fixed top-4 left-4 z-50 space-y-2';
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.className = `toast max-w-sm bg-white border border-gray-200 rounded-xl shadow-lg p-4 transform transition-all duration-300 ${getToastTypeClass(type)}`;

    const icon = getToastIcon(type);
    toast.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="${icon} text-lg"></i>
            </div>
            <div class="mr-3 flex-1">
                <p class="text-sm font-medium text-gray-900">${message}</p>
            </div>
            <div class="mr-auto pl-3">
                <button class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;

    // Animation
    toast.style.transform = 'translateY(-20px)';
    toast.style.opacity = '0';

    document.getElementById('toastContainer').appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateY(0)';
        toast.style.opacity = '1';
    }, 100);

    // Auto remove after duration
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.transform = 'translateY(-20px)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }
    }, duration);
}

function getToastTypeClass(type) {
    const typeClasses = {
        'success': 'border-green-200 bg-green-50',
        'error': 'border-red-200 bg-red-50',
        'warning': 'border-yellow-200 bg-yellow-50',
        'info': 'border-blue-200 bg-blue-50'
    };
    return typeClasses[type] || typeClasses['info'];
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

// Modal Functions
function initializeModals() {
    // Modal triggers
    document.addEventListener('click', function(e) {
        if (e.target.dataset.modal) {
            showModal(e.target.dataset.modal);
        }

        if (e.target.classList.contains('modal-close') || e.target.closest('.modal-close')) {
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
        modal.querySelector('.modal-content').style.transform = 'scale(0.9)';
        modal.querySelector('.modal-content').style.opacity = '0';

        setTimeout(() => {
            modal.querySelector('.modal-content').style.transform = 'scale(1)';
            modal.querySelector('.modal-content').style.opacity = '1';
        }, 100);

        document.body.style.overflow = 'hidden';
    }
}

function hideModal(modal) {
    if (modal) {
        const modalContent = modal.querySelector('.modal-content');
        modalContent.style.transform = 'scale(0.9)';
        modalContent.style.opacity = '0';

        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }
}

// Initialize search functionality
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

// Initialize tooltips
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
    tooltip.className = 'tooltip bg-gray-900 text-white text-sm rounded py-2 px-3 absolute z-50 pointer-events-none shadow-lg';
    tooltip.textContent = text;

    document.body.appendChild(tooltip);

    const rect = e.target.getBoundingClientRect();
    tooltip.style.top = (rect.top - tooltip.offsetHeight - 8) + 'px';
    tooltip.style.left = (rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)) + 'px';

    // Animate in
    tooltip.style.opacity = '0';
    tooltip.style.transform = 'translateY(5px)';
    setTimeout(() => {
        tooltip.style.opacity = '1';
        tooltip.style.transform = 'translateY(0)';
    }, 100);

    e.target.tooltip = tooltip;
}

function hideTooltip(e) {
    if (e.target.tooltip) {
        const tooltip = e.target.tooltip;
        tooltip.style.opacity = '0';
        tooltip.style.transform = 'translateY(5px)';
        setTimeout(() => tooltip.remove(), 200);
        delete e.target.tooltip;
    }
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

    field.classList.add('border-red-500', 'bg-red-50');

    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-red-500 text-sm mt-1 flex items-center';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle ml-1"></i>${message}`;

    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('border-red-500', 'bg-red-50');

    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Auto-save functionality
function initializeAutoSave(form) {
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
            if (input && input.value === '') {
                input.value = value;
            }
        });
    } catch (error) {
        console.error('Error loading auto-saved data:', error);
    }
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

// Print functionality
function printElement(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const printWindow = window.open('', '_blank');
    const cssLinks = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
        .map(link => `<link rel="stylesheet" href="${link.href}">`)
        .join('');

    printWindow.document.write(`
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="utf-8">
            <title>طباعة - متجر الحسيني</title>
            ${cssLinks}
            <style>
                body { 
                    font-family: 'Cairo', Arial, sans-serif; 
                    direction: rtl;
                }
                .no-print { display: none !important; }
                @media print {
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        </head>
        <body onload="window.print(); window.close();">
            ${element.outerHTML}
        </body>
        </html>
    `);

    printWindow.document.close();
}

// Performance optimization
function performSearch(query, target) {
    // Implementation will be added based on specific search requirements
    console.log('Search:', query, 'Target:', target);
}

// Export functions for global use
window.showToast = showToast;
window.showModal = showModal;
window.hideModal = hideModal;
window.printElement = printElement;
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;