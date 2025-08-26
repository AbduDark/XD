
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'الحسيني ستور') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cairo:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1e40af;
            --secondary-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --dark-bg: #0f172a;
            --dark-card: #1e293b;
            --dark-border: #334155;
        }
        
        * {
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
        }
        
        .app-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .dark .app-container {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        
        .sidebar {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        }
        
        .dark .sidebar {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(51, 65, 85, 0.3);
        }
        
        .nav-item {
            border-radius: 12px;
            margin: 8px 0;
            position: relative;
            overflow: hidden;
        }
        
        .nav-item:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }
        
        .nav-item.active:before,
        .nav-item:hover:before {
            opacity: 0.1;
        }
        
        .nav-item.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(16, 185, 129, 0.1));
            color: var(--primary-color);
        }
        
        .main-content {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px 0 0 20px;
            margin: 20px 0 20px 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .dark .main-content {
            background: rgba(15, 23, 42, 0.1);
            border: 1px solid rgba(51, 65, 85, 0.3);
        }
        
        .card-glass {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .dark .card-glass {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(51, 65, 85, 0.3);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }
        
        .btn-gradient:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-gradient:hover:before {
            left: 100%;
        }
        
        .stats-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .dark .stats-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(30, 41, 59, 0.7));
            border: 1px solid rgba(51, 65, 85, 0.3);
        }
        
        .stats-card:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            opacity: 0.1;
            transform: translate(20px, -20px);
        }
        
        .notification-badge {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .data-table {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .dark .data-table {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(51, 65, 85, 0.3);
        }
        
        .form-input {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .dark .form-input {
            background: rgba(51, 65, 85, 0.9);
            border: 2px solid rgba(71, 85, 105, 0.3);
            color: white;
        }
        
        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(100%);
                position: fixed;
                z-index: 1000;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
            
            .main-content {
                margin: 20px;
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="spinner"></div>
        </div>
        
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside id="sidebar" class="sidebar fixed right-0 top-0 h-full w-72 shadow-2xl z-40">
                <div class="p-6">
                    <!-- Logo -->
                    <div class="text-center mb-10">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-store text-white text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">الحسيني ستور</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">نظام إدارة المحل</p>
                    </div>

                    <!-- Navigation -->
                    <nav class="space-y-3">
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-blue-50 dark:hover:bg-gray-700 transition-all {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt w-5 text-lg"></i>
                            <span class="font-medium">الرئيسية</span>
                        </a>

                        <a href="{{ route('products.index') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="fas fa-mobile-alt w-5 text-lg"></i>
                            <span class="font-medium">المنتجات</span>
                        </a>

                        <a href="{{ route('invoices.index') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                            <i class="fas fa-file-invoice w-5 text-lg"></i>
                            <span class="font-medium">الفواتير</span>
                        </a>

                        <a href="{{ route('returns.index') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all {{ request()->routeIs('returns.*') ? 'active' : '' }}">
                            <i class="fas fa-undo w-5 text-lg"></i>
                            <span class="font-medium">المرتجعات</span>
                        </a>

                        <a href="{{ route('repairs.index') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all {{ request()->routeIs('repairs.*') ? 'active' : '' }}">
                            <i class="fas fa-tools w-5 text-lg"></i>
                            <span class="font-medium">الصيانة</span>
                        </a>

                        <a href="{{ route('cash-transfers.index') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all {{ request()->routeIs('cash-transfers.*') ? 'active' : '' }}">
                            <i class="fas fa-exchange-alt w-5 text-lg"></i>
                            <span class="font-medium">حركة النقدية</span>
                        </a>

                        @if(auth()->user()->role === 'admin')
                        <a href="{{ route('reports.index') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar w-5 text-lg"></i>
                            <span class="font-medium">التقارير</span>
                        </a>

                        <a href="{{ route('backup.create') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                            <i class="fas fa-database w-5 text-lg"></i>
                            <span class="font-medium">النسخ الاحتياطي</span>
                        </a>
                        @endif

                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('users.index') }}" class="nav-item flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-users w-5 text-lg"></i>
                            <span class="font-medium">المستخدمين</span>
                        </a>
                        @endif
                    </nav>

                    <!-- User Section -->
                    <div class="absolute bottom-6 left-6 right-6">
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex items-center space-x-3 space-x-reverse mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ Auth::user()->role }}</p>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('profile.edit') }}" class="flex-1 btn-gradient text-center text-sm py-2">
                                    <i class="fas fa-cog ml-1"></i>
                                    الإعدادات
                                </a>
                                
                                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white px-3 py-2 rounded-lg text-sm hover:from-red-600 hover:to-red-700 transition-all">
                                        <i class="fas fa-sign-out-alt ml-1"></i>
                                        خروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 mr-72 main-content" id="main-content">
                <!-- Top Header -->
                <header class="p-6 border-b border-gray-200 dark:border-gray-700 border-opacity-20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <button id="sidebar-toggle" class="md:hidden text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 btn-gradient p-2">
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                @yield('page-title', 'الحسيني ستور')
                            </h2>
                        </div>

                        <div class="flex items-center space-x-4 space-x-reverse">
                            <!-- Search -->
                            <div class="hidden lg:block">
                                <div class="relative">
                                    <input type="text" placeholder="البحث..." class="form-input w-64 pr-10">
                                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                            </div>
                            
                            <!-- Theme Toggle -->
                            <button id="theme-toggle" class="p-3 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-xl hover:bg-white hover:bg-opacity-20 transition-all">
                                <i id="theme-icon" class="fas fa-moon text-lg"></i>
                            </button>

                            <!-- Notifications -->
                            <button class="p-3 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-xl hover:bg-white hover:bg-opacity-20 transition-all relative">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center">3</span>
                            </button>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="p-6">
                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="card-glass border-green-300 dark:border-green-600 text-green-700 dark:text-green-300 px-6 py-4 rounded-xl mb-6 relative" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle ml-3 text-xl"></i>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="card-glass border-red-300 dark:border-red-600 text-red-700 dark:text-red-300 px-6 py-4 rounded-xl mb-6 relative" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle ml-3 text-xl"></i>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <script>
        // Global App Object
        window.HusseinStore = {
            // Theme Management
            theme: {
                toggle: function() {
                    const html = document.documentElement;
                    const themeIcon = document.getElementById('theme-icon');
                    
                    if (html.classList.contains('dark')) {
                        html.classList.remove('dark');
                        themeIcon.className = 'fas fa-moon text-lg';
                        localStorage.setItem('theme', 'light');
                    } else {
                        html.classList.add('dark');
                        themeIcon.className = 'fas fa-sun text-lg';
                        localStorage.setItem('theme', 'dark');
                    }
                },
                
                init: function() {
                    const savedTheme = localStorage.getItem('theme') || 'light';
                    const html = document.documentElement;
                    const themeIcon = document.getElementById('theme-icon');
                    
                    if (savedTheme === 'dark') {
                        html.classList.add('dark');
                        themeIcon.className = 'fas fa-sun text-lg';
                    } else {
                        html.classList.remove('dark');
                        themeIcon.className = 'fas fa-moon text-lg';
                    }
                }
            },
            
            // Sidebar Management
            sidebar: {
                toggle: function() {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebar-overlay');
                    
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('hidden');
                },
                
                close: function() {
                    const sidebar = document.getElementById('sidebar');
                    const overlay = document.getElementById('sidebar-overlay');
                    
                    sidebar.classList.remove('open');
                    overlay.classList.add('hidden');
                },
                
                handleResize: function() {
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.getElementById('main-content');
                    const overlay = document.getElementById('sidebar-overlay');
                    
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('open');
                        overlay.classList.add('hidden');
                        mainContent.classList.add('mr-72');
                    } else {
                        mainContent.classList.remove('mr-72');
                    }
                }
            },
            
            // Loading Management
            loading: {
                show: function() {
                    document.getElementById('loadingOverlay').classList.add('active');
                },
                
                hide: function() {
                    document.getElementById('loadingOverlay').classList.remove('active');
                }
            },
            
            // Notifications
            notify: {
                success: function(message) {
                    this.show(message, 'success');
                },
                
                error: function(message) {
                    this.show(message, 'error');
                },
                
                warning: function(message) {
                    this.show(message, 'warning');
                },
                
                info: function(message) {
                    this.show(message, 'info');
                },
                
                show: function(message, type = 'info') {
                    const notification = document.createElement('div');
                    notification.className = `card-glass border-${this.getColor(type)}-300 text-${this.getColor(type)}-700 px-6 py-4 rounded-xl mb-4 fixed top-4 right-4 z-50 transform translate-x-full transition-transform duration-300`;
                    notification.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-${this.getIcon(type)} ml-3 text-xl"></i>
                            <span class="font-medium">${message}</span>
                            <button onclick="this.parentElement.parentElement.remove()" class="mr-4 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.classList.remove('translate-x-full');
                    }, 100);
                    
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => notification.remove(), 300);
                    }, 5000);
                },
                
                getColor: function(type) {
                    const colors = {
                        success: 'green',
                        error: 'red',
                        warning: 'yellow',
                        info: 'blue'
                    };
                    return colors[type] || 'blue';
                },
                
                getIcon: function(type) {
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
                get: async function(url) {
                    try {
                        HusseinStore.loading.show();
                        const response = await fetch(url, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            }
                        });
                        return await response.json();
                    } catch (error) {
                        HusseinStore.notify.error('حدث خطأ في الاتصال');
                        throw error;
                    } finally {
                        HusseinStore.loading.hide();
                    }
                },
                
                post: async function(url, data) {
                    try {
                        HusseinStore.loading.show();
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });
                        return await response.json();
                    } catch (error) {
                        HusseinStore.notify.error('حدث خطأ في الاتصال');
                        throw error;
                    } finally {
                        HusseinStore.loading.hide();
                    }
                }
            },
            
            // Initialize App
            init: function() {
                this.theme.init();
                this.sidebar.handleResize();
                
                // Event Listeners
                document.getElementById('theme-toggle')?.addEventListener('click', this.theme.toggle);
                document.getElementById('sidebar-toggle')?.addEventListener('click', this.sidebar.toggle);
                document.getElementById('sidebar-overlay')?.addEventListener('click', this.sidebar.close);
                window.addEventListener('resize', this.sidebar.handleResize);
                
                // Add smooth animations
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.style.opacity = '0';
                    setTimeout(() => {
                        document.body.style.transition = 'opacity 0.5s ease';
                        document.body.style.opacity = '1';
                    }, 100);
                });
            }
        };
        
        // Initialize the app
        HusseinStore.init();
    </script>
</body>
</html>
