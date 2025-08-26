
@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">النسخ الاحتياطي</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">إنشاء نسخة احتياطية</h2>
            <p class="text-gray-600 dark:text-gray-400">قم بإنشاء نسخة احتياطية من قاعدة البيانات لحفظ جميع بياناتك</p>
        </div>

        <form method="POST" action="{{ route('backup.download') }}">
            @csrf
            
            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-xl ml-3"></i>
                    <div>
                        <h3 class="text-blue-800 dark:text-blue-200 font-semibold">معلومات مهمة</h3>
                        <p class="text-blue-700 dark:text-blue-300 text-sm">
                            النسخة الاحتياطية ستحتوي على جميع البيانات التالية:
                        </p>
                        <ul class="text-blue-700 dark:text-blue-300 text-sm mt-2 list-disc list-inside">
                            <li>بيانات المستخدمين</li>
                            <li>فئات ومنتجات المخزون</li>
                            <li>الفواتير وعناصرها</li>
                            <li>المرتجعات</li>
                            <li>أوامر الصيانة</li>
                            <li>التحويلات النقدية</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                        <i class="fas fa-database text-blue-600 ml-2"></i>
                        معلومات النسخة الاحتياطية
                    </h4>
                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <p><strong>التاريخ:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
                        <p><strong>الإصدار:</strong> SQL Database Backup</p>
                        <p><strong>الحجم المتوقع:</strong> يعتمد على حجم البيانات</p>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">
                        <i class="fas fa-shield-alt text-green-600 ml-2"></i>
                        الأمان والحماية
                    </h4>
                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <p>✓ تشفير البيانات الحساسة</p>
                        <p>✓ تنزيل آمن</p>
                        <p>✓ لا يتم حفظ النسخة على الخادم</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl ml-3"></i>
                    <div>
                        <h3 class="text-yellow-800 dark:text-yellow-200 font-semibold">تنبيه هام</h3>
                        <p class="text-yellow-700 dark:text-yellow-300 text-sm">
                            احتفظ بالنسخة الاحتياطية في مكان آمن. لا تشارك ملف النسخة الاحتياطية مع أشخاص غير مصرح لهم.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-semibold">
                    <i class="fas fa-download ml-2"></i>
                    تحميل النسخة الاحتياطية
                </button>
            </div>
        </form>
    </div>

    <!-- تعليمات الاستعادة -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">كيفية استعادة النسخة الاحتياطية</h2>
        
        <div class="space-y-4">
            <div class="flex items-start">
                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-sm font-semibold ml-3">1</span>
                <p class="text-gray-600 dark:text-gray-300">قم بالدخول إلى لوحة إدارة قاعدة البيانات (phpMyAdmin أو MySQL CLI)</p>
            </div>
            
            <div class="flex items-start">
                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-sm font-semibold ml-3">2</span>
                <p class="text-gray-600 dark:text-gray-300">قم بإنشاء قاعدة بيانات جديدة أو اختر قاعدة البيانات الموجودة</p>
            </div>
            
            <div class="flex items-start">
                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-sm font-semibold ml-3">3</span>
                <p class="text-gray-600 dark:text-gray-300">قم برفع ملف النسخة الاحتياطية واستيراده</p>
            </div>
            
            <div class="flex items-start">
                <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-sm font-semibold ml-3">4</span>
                <p class="text-gray-600 dark:text-gray-300">تأكد من تحديث ملف .env بمعلومات قاعدة البيانات الجديدة</p>
            </div>
        </div>
    </div>
</div>
@endsection
