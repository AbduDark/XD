
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800 dark:text-white">
                                <i class="fas fa-store ml-2"></i>
                                متجر الحسيني
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fas fa-tachometer-alt ml-2"></i>
                                لوحة التحكم
                            </a>
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <i class="fas fa-box ml-2"></i>
                                المنتجات
                            </a>
                            <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                                <i class="fas fa-file-invoice ml-2"></i>
                                الفواتير
                            </a>
                            <a href="{{ route('returns.index') }}" class="nav-link {{ request()->routeIs('returns.*') ? 'active' : '' }}">
                                <i class="fas fa-undo ml-2"></i>
                                المرتجعات
                            </a>
                            <a href="{{ route('repairs.index') }}" class="nav-link {{ request()->routeIs('repairs.*') ? 'active' : '' }}">
                                <i class="fas fa-tools ml-2"></i>
                                الصيانة
                            </a>
                            <a href="{{ route('cash-transfers.index') }}" class="nav-link {{ request()->routeIs('cash-transfers.*') ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt ml-2"></i>
                                التحويلات
                            </a>
                            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar ml-2"></i>
                                التقارير
                            </a>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <div class="relative">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 left-4 z-50 space-y-2"></div>

    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type} transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;
            toast.innerHTML = `
                <div class="flex items-center p-4 rounded-lg shadow-lg">
                    <div class="flex-shrink-0">
                        ${type === 'success' ? '<i class="fas fa-check-circle text-green-500"></i>' : '<i class="fas fa-exclamation-circle text-red-500"></i>'}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.getElementById('toast-container').appendChild(toast);
            
            // Show toast
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);
            
            // Hide toast after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Global error handling
        window.addEventListener('error', function(e) {
            console.error('خطأ في التطبيق:', e.error);
        });

        // CSRF token setup for AJAX requests
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</body>
</html>
