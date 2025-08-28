
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen">
        <!-- Enhanced Navigation -->
        <nav class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 shadow-xl border-b-4 border-purple-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <!-- Enhanced Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-white hover:text-purple-200 transition-colors duration-300">
                                <div class="bg-white bg-opacity-20 p-3 rounded-full backdrop-blur-sm">
                                    <i class="fas fa-store text-2xl text-white"></i>
                                </div>
                                <div class="text-right">
                                    <h1 class="text-2xl font-bold">متجر الحسيني</h1>
                                    <p class="text-xs text-purple-200">نظام إدارة المبيعات</p>
                                </div>
                            </a>
                        </div>

                        <!-- Enhanced Navigation Links -->
                        <div class="hidden lg:flex lg:space-x-1 lg:mr-10">
                            <a href="{{ route('dashboard') }}" class="nav-link-enhanced {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt ml-2"></i>
                                لوحة التحكم
                            </a>
                            <a href="{{ route('products.index') }}" class="nav-link-enhanced {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <i class="fas fa-box ml-2"></i>
                                المنتجات
                            </a>
                            <a href="{{ route('invoices.index') }}" class="nav-link-enhanced {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice ml-2"></i>
                                الفواتير
                            </a>
                            <a href="{{ route('returns.index') }}" class="nav-link-enhanced {{ request()->routeIs('returns.*') ? 'active' : '' }}">
                                <i class="fas fa-undo ml-2"></i>
                                المرتجعات
                            </a>
                            <a href="{{ route('repairs.index') }}" class="nav-link-enhanced {{ request()->routeIs('repairs.*') ? 'active' : '' }}">
                                <i class="fas fa-tools ml-2"></i>
                                الصيانة
                            </a>
                            <a href="{{ route('cash-transfers.index') }}" class="nav-link-enhanced {{ request()->routeIs('cash-transfers.*') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt ml-2"></i>
                                التحويلات
                            </a>
                            <a href="{{ route('reports.index') }}" class="nav-link-enhanced {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar ml-2"></i>
                                التقارير
                            </a>
                        </div>
                    </div>

                    <!-- Enhanced User Dropdown -->
                    <div class="flex items-center space-x-4">
                        <!-- Quick Actions -->
                        <div class="hidden md:flex items-center space-x-2">
                            <a href="{{ route('invoices.create') }}" class="quick-action-btn bg-green-500 hover:bg-green-600">
                                <i class="fas fa-plus text-sm"></i>
                            </a>
                            <button class="quick-action-btn bg-yellow-500 hover:bg-yellow-600" title="إشعارات">
                                <i class="fas fa-bell text-sm"></i>
                                <span class="notification-badge">3</span>
                            </button>
                        </div>

                        <!-- User Profile -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                            <button @click="open = ! open" class="flex items-center space-x-3 bg-white bg-opacity-20 rounded-full px-4 py-2 hover:bg-opacity-30 transition-all duration-300">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div class="text-right hidden sm:block">
                                    <div class="text-white font-medium">{{ Auth::user()->name }}</div>
                                    <div class="text-purple-200 text-xs">{{ Auth::user()->role == 'admin' ? 'مدير' : 'موظف' }}</div>
                                </div>
                                <i class="fas fa-chevron-down text-white text-sm" :class="{'rotate-180': open}"></i>
                            </button>

                            <div x-show="open" x-transition class="absolute left-0 mt-2 w-56 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" style="display: none;">
                                <div class="p-4 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                        <i class="fas fa-user-edit text-gray-400"></i>
                                        الملف الشخصي
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="dropdown-item">
                                        <i class="fas fa-cog text-gray-400"></i>
                                        الإعدادات
                                    </a>
                                </div>
                                <div class="border-t border-gray-100 py-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-red-600 hover:bg-red-50 w-full text-right">
                                            <i class="fas fa-sign-out-alt text-red-500"></i>
                                            تسجيل الخروج
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-white hover:bg-white hover:bg-opacity-20 transition-colors duration-300">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="sidebarOpen" x-transition class="lg:hidden bg-white bg-opacity-10 backdrop-blur-sm">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt ml-2"></i>
                        لوحة التحكم
                    </a>
                    <a href="{{ route('products.index') }}" class="mobile-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="fas fa-box ml-2"></i>
                        المنتجات
                    </a>
                    <a href="{{ route('invoices.index') }}" class="mobile-nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice ml-2"></i>
                        الفواتير
                    </a>
                    <a href="{{ route('returns.index') }}" class="mobile-nav-link {{ request()->routeIs('returns.*') ? 'active' : '' }}">
                        <i class="fas fa-undo ml-2"></i>
                        المرتجعات
                    </a>
                    <a href="{{ route('repairs.index') }}" class="mobile-nav-link {{ request()->routeIs('repairs.*') ? 'active' : '' }}">
                        <i class="fas fa-tools ml-2"></i>
                        الصيانة
                    </a>
                    <a href="{{ route('cash-transfers.index') }}" class="mobile-nav-link {{ request()->routeIs('cash-transfers.*') ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt ml-2"></i>
                        التحويلات
                    </a>
                    <a href="{{ route('reports.index') }}" class="mobile-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar ml-2"></i>
                        التقارير
                    </a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="min-h-screen">
            @yield('content')
        </main>

        <!-- Enhanced Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-4 mb-4 md:mb-0">
                        <div class="bg-purple-600 p-2 rounded-full">
                            <i class="fas fa-store text-white"></i>
                        </div>
                        <div class="text-right">
                            <h3 class="font-semibold">متجر الحسيني</h3>
                            <p class="text-sm text-gray-300">نظام إدارة شامل للمبيعات</p>
                        </div>
                    </div>
                    <div class="text-center md:text-left">
                        <p class="text-sm text-gray-300">&copy; 2025 جميع الحقوق محفوظة</p>
                        <p class="text-xs text-gray-400">الإصدار 2.0</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 left-4 z-50 space-y-2"></div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
            <span class="text-gray-700 font-medium">جاري التحميل...</span>
        </div>
    </div>

    <script>
        // Enhanced toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast-enhanced toast-${type} transform transition-all duration-500 ease-in-out translate-x-full opacity-0`;
            toast.innerHTML = `
                <div class="flex items-center p-4 rounded-xl shadow-2xl backdrop-blur-sm border">
                    <div class="flex-shrink-0">
                        ${type === 'success' ? 
                            '<div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center"><i class="fas fa-check text-white text-sm"></i></div>' : 
                            '<div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center"><i class="fas fa-exclamation text-white text-sm"></i></div>'
                        }
                    </div>
                    <div class="mr-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="mr-4 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.getElementById('toast-container').appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);

            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 5000);
        }

        // Loading overlay functions
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
        }

        // Page transition effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate page load
            document.body.classList.add('fade-in');
            
            // Add smooth scrolling
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });

        // CSRF token setup
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</body>
</html>
