
import './bootstrap';

// Enhanced Store Management System
window.HusseinStore = {
    // Configuration
    config: {
        apiUrl: '/api',
        timeout: 5000,
        retryAttempts: 3,
        animations: true
    },

    // Theme Management
    theme: {
        current: localStorage.getItem('theme') || 'light',
        
        toggle() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            
            if (this.current === 'light') {
                this.setDark();
            } else {
                this.setLight();
            }
        },
        
        setLight() {
            document.documentElement.classList.remove('dark');
            document.getElementById('theme-icon').className = 'fas fa-moon text-lg';
            localStorage.setItem('theme', 'light');
            this.current = 'light';
            this.updateColors();
        },
        
        setDark() {
            document.documentElement.classList.add('dark');
            document.getElementById('theme-icon').className = 'fas fa-sun text-lg';
            localStorage.setItem('theme', 'dark');
            this.current = 'dark';
            this.updateColors();
        },
        
        init() {
            if (this.current === 'dark') {
                this.setDark();
            } else {
                this.setLight();
            }
        },
        
        updateColors() {
            // Update chart colors based on theme
            if (typeof Chart !== 'undefined' && Chart.instances.length > 0) {
                Chart.instances.forEach(chart => {
                    chart.options.plugins.legend.labels.color = this.current === 'dark' ? '#f3f4f6' : '#374151';
                    chart.options.scales.x.ticks.color = this.current === 'dark' ? '#9ca3af' : '#6b7280';
                    chart.options.scales.y.ticks.color = this.current === 'dark' ? '#9ca3af' : '#6b7280';
                    chart.update();
                });
            }
        }
    },

    // Sidebar Management
    sidebar: {
        isOpen: false,
        
        toggle() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('hidden');
                this.isOpen = !this.isOpen;
            }
        },
        
        close() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
            this.isOpen = false;
        },
        
        handleResize() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('open');
                overlay.classList.add('hidden');
                this.isOpen = false;
            }
        }
    },

    // Loading Management
    loading: {
        overlay: null,
        
        init() {
            this.overlay = document.getElementById('loadingOverlay');
        },
        
        show(message = 'جاري التحميل...') {
            if (this.overlay) {
                this.overlay.classList.add('active');
                // Add message if needed
                const messageEl = this.overlay.querySelector('.loading-message');
                if (messageEl) {
                    messageEl.textContent = message;
                }
            }
        },
        
        hide() {
            if (this.overlay) {
                this.overlay.classList.remove('active');
            }
        }
    },

    // Notification System
    notifications: {
        container: null,
        
        init() {
            // Create notification container if it doesn't exist
            if (!document.getElementById('notification-container')) {
                const container = document.createElement('div');
                container.id = 'notification-container';
                container.className = 'fixed top-4 right-4 z-50 space-y-2';
                document.body.appendChild(container);
                this.container = container;
            } else {
                this.container = document.getElementById('notification-container');
            }
        },
        
        show(message, type = 'info', duration = 5000) {
            const notification = this.create(message, type);
            this.container.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
            }, 100);
            
            // Auto remove
            setTimeout(() => {
                this.remove(notification);
            }, duration);
            
            return notification;
        },
        
        create(message, type) {
            const notification = document.createElement('div');
            notification.className = `
                card-glass border-${this.getTypeColor(type)}-300 
                text-${this.getTypeColor(type)}-700 dark:text-${this.getTypeColor(type)}-300
                px-6 py-4 rounded-xl mb-4 transform translate-x-full opacity-0
                transition-all duration-300 ease-in-out max-w-sm
            `;
            
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-${this.getTypeIcon(type)} ml-3 text-lg"></i>
                        <span class="font-medium">${message}</span>
                    </div>
                    <button onclick="HusseinStore.notifications.remove(this.parentElement.parentElement)" 
                            class="mr-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            return notification;
        },
        
        remove(notification) {
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        },
        
        success(message, duration) {
            return this.show(message, 'success', duration);
        },
        
        error(message, duration) {
            return this.show(message, 'error', duration);
        },
        
        warning(message, duration) {
            return this.show(message, 'warning', duration);
        },
        
        info(message, duration) {
            return this.show(message, 'info', duration);
        },
        
        getTypeColor(type) {
            const colors = {
                success: 'green',
                error: 'red',
                warning: 'yellow',
                info: 'blue'
            };
            return colors[type] || 'blue';
        },
        
        getTypeIcon(type) {
            const icons = {
                success: 'check-circle',
                error: 'exclamation-circle',
                warning: 'exclamation-triangle',
                info: 'info-circle'
            };
            return icons[type] || 'info-circle';
        }
    },

    // API Helper
    api: {
        async request(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                ...options
            };

            try {
                HusseinStore.loading.show();
                const response = await fetch(url, defaultOptions);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                return data;
            } catch (error) {
                HusseinStore.notifications.error('حدث خطأ في الاتصال: ' + error.message);
                throw error;
            } finally {
                HusseinStore.loading.hide();
            }
        },
        
        async get(url) {
            return this.request(url, { method: 'GET' });
        },
        
        async post(url, data) {
            return this.request(url, {
                method: 'POST',
                body: JSON.stringify(data)
            });
        },
        
        async put(url, data) {
            return this.request(url, {
                method: 'PUT',
                body: JSON.stringify(data)
            });
        },
        
        async delete(url) {
            return this.request(url, { method: 'DELETE' });
        }
    },

    // Form Utilities
    forms: {
        validate(form) {
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    this.showError(input, 'هذا الحقل مطلوب');
                    isValid = false;
                } else {
                    this.clearError(input);
                }
            });
            
            return isValid;
        },
        
        showError(input, message) {
            this.clearError(input);
            
            input.classList.add('border-red-500');
            const error = document.createElement('div');
            error.className = 'text-red-500 text-sm mt-1 error-message';
            error.textContent = message;
            input.parentNode.appendChild(error);
        },
        
        clearError(input) {
            input.classList.remove('border-red-500');
            const existingError = input.parentNode.querySelector('.error-message');
            if (existingError) {
                existingError.remove();
            }
        },
        
        serialize(form) {
            const formData = new FormData(form);
            const data = {};
            
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            return data;
        },
        
        reset(form) {
            form.reset();
            const errors = form.querySelectorAll('.error-message');
            errors.forEach(error => error.remove());
            
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => input.classList.remove('border-red-500'));
        }
    },

    // Search Functionality
    search: {
        init() {
            const searchInputs = document.querySelectorAll('[data-search]');
            searchInputs.forEach(input => {
                input.addEventListener('input', this.handleSearch.bind(this));
            });
        },
        
        handleSearch(event) {
            const input = event.target;
            const query = input.value.toLowerCase();
            const target = input.getAttribute('data-search');
            const items = document.querySelectorAll(`[data-searchable="${target}"]`);
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = '';
                    item.classList.add('fade-in');
                } else {
                    item.style.display = 'none';
                    item.classList.remove('fade-in');
                }
            });
        }
    },

    // Modal Management
    modals: {
        open(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                
                // Add animation
                const content = modal.querySelector('.card-glass, .modal-content');
                if (content) {
                    content.classList.add('zoom-in');
                }
            }
        },
        
        close(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }
        },
        
        init() {
            // Close modal when clicking outside
            document.addEventListener('click', (event) => {
                if (event.target.classList.contains('modal-overlay')) {
                    const modal = event.target;
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = '';
                }
            });
        }
    },

    // Table Utilities
    tables: {
        init() {
            this.addSortingToTables();
            this.addFilteringToTables();
        },
        
        addSortingToTables() {
            const sortableHeaders = document.querySelectorAll('[data-sortable]');
            sortableHeaders.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', this.handleSort.bind(this));
            });
        },
        
        handleSort(event) {
            const header = event.target;
            const table = header.closest('table');
            const column = Array.from(header.parentNode.children).indexOf(header);
            const isAscending = header.getAttribute('data-sort-direction') !== 'asc';
            
            // Update header indicator
            header.setAttribute('data-sort-direction', isAscending ? 'asc' : 'desc');
            
            // Sort table rows
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                const aText = a.children[column].textContent.trim();
                const bText = b.children[column].textContent.trim();
                
                if (isAscending) {
                    return aText.localeCompare(bText, 'ar');
                } else {
                    return bText.localeCompare(aText, 'ar');
                }
            });
            
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        },
        
        addFilteringToTables() {
            const filterInputs = document.querySelectorAll('[data-table-filter]');
            filterInputs.forEach(input => {
                input.addEventListener('input', this.handleFilter.bind(this));
            });
        },
        
        handleFilter(event) {
            const input = event.target;
            const query = input.value.toLowerCase();
            const tableId = input.getAttribute('data-table-filter');
            const table = document.getElementById(tableId);
            
            if (table) {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        }
    },

    // Local Storage Utilities
    storage: {
        set(key, value) {
            try {
                localStorage.setItem(key, JSON.stringify(value));
            } catch (error) {
                console.warn('Failed to save to localStorage:', error);
            }
        },
        
        get(key, defaultValue = null) {
            try {
                const item = localStorage.getItem(key);
                return item ? JSON.parse(item) : defaultValue;
            } catch (error) {
                console.warn('Failed to read from localStorage:', error);
                return defaultValue;
            }
        },
        
        remove(key) {
            try {
                localStorage.removeItem(key);
            } catch (error) {
                console.warn('Failed to remove from localStorage:', error);
            }
        }
    },

    // Initialization
    init() {
        // Initialize all modules
        this.theme.init();
        this.sidebar.handleResize();
        this.loading.init();
        this.notifications.init();
        this.search.init();
        this.modals.init();
        this.tables.init();
        
        // Add event listeners
        this.addEventListeners();
        
        // Add smooth page transitions
        this.addPageTransitions();
        
        // Initialize tooltips
        this.initTooltips();
        
        console.log('Hussein Store Management System initialized successfully');
    },
    
    addEventListeners() {
        // Theme toggle
        document.getElementById('theme-toggle')?.addEventListener('click', () => {
            this.theme.toggle();
        });
        
        // Sidebar toggle
        document.getElementById('sidebar-toggle')?.addEventListener('click', () => {
            this.sidebar.toggle();
        });
        
        // Sidebar overlay
        document.getElementById('sidebar-overlay')?.addEventListener('click', () => {
            this.sidebar.close();
        });
        
        // Window resize
        window.addEventListener('resize', () => {
            this.sidebar.handleResize();
        });
        
        // Form validation
        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', (event) => {
                if (!this.forms.validate(form)) {
                    event.preventDefault();
                    this.notifications.error('يرجى تصحيح الأخطاء في النموذج');
                }
            });
        });
        
        // Auto-save forms
        document.querySelectorAll('form[data-autosave]').forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('change', () => {
                    const formData = this.forms.serialize(form);
                    this.storage.set(`form_${form.id}`, formData);
                });
            });
        });
    },
    
    addPageTransitions() {
        // Add fade-in animation to page content
        document.addEventListener('DOMContentLoaded', () => {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
        
        // Add loading animation to links
        document.querySelectorAll('a[href]:not([href^="#"]):not([href^="javascript:"]):not([target="_blank"])').forEach(link => {
            link.addEventListener('click', (event) => {
                // Don't animate if it's the same page
                if (link.href === window.location.href) return;
                
                this.loading.show('جاري تحميل الصفحة...');
                
                // Hide loading after a timeout (in case the page doesn't load)
                setTimeout(() => {
                    this.loading.hide();
                }, 10000);
            });
        });
    },
    
    initTooltips() {
        // Simple tooltip implementation
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', (event) => {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip fixed z-50 bg-gray-800 text-white px-2 py-1 rounded text-sm pointer-events-none';
                tooltip.textContent = element.getAttribute('data-tooltip');
                document.body.appendChild(tooltip);
                
                const rect = element.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
                
                element.addEventListener('mouseleave', () => {
                    tooltip.remove();
                }, { once: true });
            });
        });
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        HusseinStore.init();
    });
} else {
    HusseinStore.init();
}

// Export for global access
window.HusseinStore = HusseinStore;
