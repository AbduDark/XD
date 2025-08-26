
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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        body {
            transition: all 0.3s ease;
        }
        
        .sidebar {
            transition: transform 0.3s ease;
            z-index: 40;
        }
        
        .sidebar-hidden {
            transform: translateX(100%);
        }
        
        .main-content {
            transition: margin 0.3s ease;
        }
        
        .theme-toggle {
            transition: all 0.3s ease;
        }
        
        .dark .bg-white { background-color: #1f2937; }
        .dark .text-gray-900 { color: #f9fafb; }
        .dark .text-gray-800 { color: #f3f4f6; }
        .dark .text-gray-700 { color: #d1d5db; }
        .dark .text-gray-600 { color: #9ca3af; }
        .dark .text-gray-500 { color: #6b7280; }
        .dark .border-gray-200 { border-color: #374151; }
        .dark .border-gray-300 { border-color: #4b5563; }
        .dark .bg-gray-50 { background-color: #111827; }
        .dark .bg-gray-100 { background-color: #1f2937; }
        .dark .bg-gray-200 { background-color: #374151; }
        .dark .hover\:bg-gray-100:hover { background-color: #374151; }
        .dark .hover\:bg-gray-50:hover { background-color: #1f2937; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="min-h-screen flex" id="app">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar fixed right-0 top-0 h-full w-64 bg-white dark:bg-gray-800 shadow-lg border-l border-gray-200 dark:border-gray-700 z-40">
            <div class="p-4">
                <!-- Logo -->
                <div class="flex items-center justify-center mb-8">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-blue-600 dark:text-blue-400">الحسيني ستور</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">نظام إدارة المحل</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>الرئيسية</span>
                    </a>

                    <a href="{{ route('products.index') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('products.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-mobile-alt w-5"></i>
                        <span>المنتجات</span>
                    </a>

                    <a href="{{ route('invoices.index') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('invoices.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-file-invoice w-5"></i>
                        <span>الفواتير</span>
                    </a>

                    <a href="{{ route('returns.index') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('returns.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-undo w-5"></i>
                        <span>المرتجعات</span>
                    </a>

                    <a href="{{ route('repairs.index') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('repairs.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-tools w-5"></i>
                        <span>الصيانة</span>
                    </a>

                    <a href="{{ route('cash-transfers.index') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('cash-transfers.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-exchange-alt w-5"></i>
                        <span>حركة النقدية</span>
                    </a>

                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('reports.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>التقارير</span>
                    </a>

                    <a href="{{ route('backup.create') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('backup.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-database w-5"></i>
                        <span>النسخ الاحتياطي</span>
                    </a>
                    @endif

                    @if(auth()->user()->isSuperAdmin())
                    <a href="{{ route('users.index') }}" class="flex items-center space-x-3 space-x-reverse px-4 py-3 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>المستخدمين</span>
                    </a>
                    @endif
                </nav>

                <!-- User Section -->
                <div class="absolute bottom-4 left-4 right-4">
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex items-center space-x-3 space-x-reverse mb-3">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ Auth::user()->role }}</p>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 space-x-reverse">
                            <a href="{{ route('profile.edit') }}" class="flex-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm text-center hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <i class="fas fa-cog ml-1"></i>
                                الإعدادات
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 px-3 py-2 rounded-lg text-sm hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
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
        <div class="flex-1 mr-64" id="main-content">
            <!-- Top Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <button id="sidebar-toggle" class="md:hidden text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                @yield('page-title', 'الحسيني ستور')
                            </h2>
                        </div>

                        <div class="flex items-center space-x-3 space-x-reverse">
                            <!-- Theme Toggle -->
                            <button id="theme-toggle" class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i id="theme-icon" class="fas fa-moon text-lg"></i>
                            </button>

                            <!-- Notifications -->
                            <button class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
                                <i class="fas fa-bell text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-6 relative" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle ml-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-6 relative" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle ml-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <script>
        // Theme Management
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const htmlElement = document.documentElement;

        // Check for saved theme or default to light
        const savedTheme = localStorage.getItem('theme') || 'light';
        
        if (savedTheme === 'dark') {
            htmlElement.classList.add('dark');
            themeIcon.className = 'fas fa-sun text-lg';
        } else {
            htmlElement.classList.remove('dark');
            themeIcon.className = 'fas fa-moon text-lg';
        }

        themeToggle.addEventListener('click', () => {
            if (htmlElement.classList.contains('dark')) {
                htmlElement.classList.remove('dark');
                themeIcon.className = 'fas fa-moon text-lg';
                localStorage.setItem('theme', 'light');
            } else {
                htmlElement.classList.add('dark');
                themeIcon.className = 'fas fa-sun text-lg';
                localStorage.setItem('theme', 'dark');
            }
        });

        // Sidebar Management
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mainContent = document.getElementById('main-content');

        sidebarToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-hidden');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay?.addEventListener('click', () => {
            sidebar.classList.add('sidebar-hidden');
            sidebarOverlay.classList.add('hidden');
        });

        // Responsive handling
        function handleResize() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-hidden');
                sidebarOverlay.classList.add('hidden');
                mainContent.classList.add('mr-64');
            } else {
                sidebar.classList.add('sidebar-hidden');
                mainContent.classList.remove('mr-64');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize(); // Initial call
    </script>
</body>
</html>
