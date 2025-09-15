
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('التقارير المتقدمة') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- System Metrics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">مؤشرات النظام</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold">إجمالي المتاجر</h4>
                            <p class="text-2xl font-bold text-blue-600">{{ $systemMetrics['total_stores'] }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold">المتاجر النشطة</h4>
                            <p class="text-2xl font-bold text-green-600">{{ $systemMetrics['active_stores'] }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold">إجمالي المستخدمين</h4>
                            <p class="text-2xl font-bold text-purple-600">{{ $systemMetrics['total_users'] }}</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h4 class="font-semibold">الإيرادات الشهرية</h4>
                            <p class="text-2xl font-bold text-yellow-600">{{ number_format($systemMetrics['current_month_revenue'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">تصدير التقارير</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.reports.export', ['type' => 'store_performance']) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            تصدير أداء المتاجر
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'financial_summary']) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            تصدير الملخص المالي
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'system_analytics']) }}" 
                           class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            تصدير تحليلات النظام
                        </a>
                    </div>
                </div>
            </div>

            <!-- Store Performance Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">أداء المتاجر</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المتجر</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المالك</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المنتجات</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفواتير</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإيرادات</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($storePerformance as $store)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $store['name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $store['owner'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $store['products_count'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $store['invoices_count'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($store['total_revenue'], 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $store['status'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
