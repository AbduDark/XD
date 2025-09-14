
@extends('layouts.app')

@section('title', 'إعدادات المتجر - ' . $store->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    إعدادات المتجر: {{ $store->name }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">إدارة شاملة لإعدادات وصلاحيات المتجر</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <button onclick="resetAllSettings()" class="btn-secondary-modern">
                    <i class="fas fa-undo mr-2"></i>
                    إعادة تعيين
                </button>
                <a href="{{ route('admin.stores.index') }}" class="btn-primary-modern">
                    <i class="fas fa-arrow-right mr-2"></i>
                    العودة للمتاجر
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Settings Navigation -->
            <div class="lg:col-span-1">
                <div class="card-modern overflow-hidden sticky top-8">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">إعدادات المتجر</h3>
                    </div>
                    <nav class="p-4 space-y-2">
                        <button onclick="showSection('basic')" class="settings-nav-btn active w-full text-right p-3 rounded-lg flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-store text-blue-600"></i>
                            <span>الإعدادات الأساسية</span>
                        </button>
                        <button onclick="showSection('business')" class="settings-nav-btn w-full text-right p-3 rounded-lg flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-business-time text-emerald-600"></i>
                            <span>إعدادات العمل</span>
                        </button>
                        <button onclick="showSection('notifications')" class="settings-nav-btn w-full text-right p-3 rounded-lg flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-bell text-amber-600"></i>
                            <span>التنبيهات</span>
                        </button>
                        <button onclick="showSection('permissions')" class="settings-nav-btn w-full text-right p-3 rounded-lg flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-shield-alt text-purple-600"></i>
                            <span>الصلاحيات</span>
                        </button>
                        <button onclick="showSection('security')" class="settings-nav-btn w-full text-right p-3 rounded-lg flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-lock text-red-600"></i>
                            <span>الأمان</span>
                        </button>
                        <button onclick="showSection('appearance')" class="settings-nav-btn w-full text-right p-3 rounded-lg flex items-center space-x-3 space-x-reverse">
                            <i class="fas fa-palette text-pink-600"></i>
                            <span>المظهر</span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-3">
                <!-- Basic Settings -->
                <div id="basic-section" class="settings-section">
                    <div class="card-modern overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-store text-blue-600 mr-3"></i>
                                الإعدادات الأساسية
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">معلومات أساسية عن المتجر</p>
                        </div>
                        <form id="basic-form" class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اسم المتجر</label>
                                    <input type="text" name="name" value="{{ $store->name }}" class="input-modern" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اسم المتجر (بالعربية)</label>
                                    <input type="text" name="name_ar" value="{{ $store->name_ar }}" class="input-modern">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الهاتف</label>
                                    <input type="tel" name="phone" value="{{ $store->phone }}" class="input-modern">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">البريد الإلكتروني</label>
                                    <input type="email" name="email" value="{{ $store->email }}" class="input-modern">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">العنوان</label>
                                <textarea name="address" rows="3" class="input-modern">{{ $store->address }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">العنوان (بالعربية)</label>
                                <textarea name="address_ar" rows="3" class="input-modern">{{ $store->address_ar }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">وصف المتجر</label>
                                <textarea name="description" rows="4" class="input-modern">{{ $store->description }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary-modern">
                                    <i class="fas fa-save mr-2"></i>
                                    حفظ التغييرات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Business Settings -->
                <div id="business-section" class="settings-section hidden">
                    <div class="card-modern overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-business-time text-emerald-600 mr-3"></i>
                                إعدادات العمل
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">ضبط معايير العمل والضرائب</p>
                        </div>
                        <form id="business-form" class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">معدل الضريبة (%)</label>
                                    <input type="number" name="tax_rate" value="{{ $store->tax_rate ?? 15 }}" min="0" max="100" step="0.01" class="input-modern">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">العملة</label>
                                    <select name="currency" class="input-modern">
                                        <option value="SAR" {{ ($store->currency ?? 'SAR') == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                                        <option value="USD" {{ ($store->currency ?? 'SAR') == 'USD' ? 'selected' : '' }}>دولار أمريكي (USD)</option>
                                        <option value="EUR" {{ ($store->currency ?? 'SAR') == 'EUR' ? 'selected' : '' }}>يورو (EUR)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">المنطقة الزمنية</label>
                                    <select name="timezone" class="input-modern">
                                        <option value="Asia/Riyadh">الرياض (UTC+3)</option>
                                        <option value="Asia/Dubai">دبي (UTC+4)</option>
                                        <option value="Asia/Kuwait">الكويت (UTC+3)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Working Hours -->
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">ساعات العمل</h4>
                                <div class="space-y-3">
                                    @php
                                        $days = [
                                            'sunday' => 'الأحد',
                                            'monday' => 'الاثنين', 
                                            'tuesday' => 'الثلاثاء',
                                            'wednesday' => 'الأربعاء',
                                            'thursday' => 'الخميس',
                                            'friday' => 'الجمعة',
                                            'saturday' => 'السبت'
                                        ];
                                    @endphp
                                    @foreach($days as $dayKey => $dayName)
                                    <div class="flex items-center space-x-4 space-x-reverse p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="w-20">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $dayName }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <input type="checkbox" name="working_hours[{{ $dayKey }}][is_closed]" class="rounded">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">مغلق</span>
                                        </div>
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <input type="time" name="working_hours[{{ $dayKey }}][open]" value="09:00" class="input-modern text-sm">
                                            <span class="text-gray-500">إلى</span>
                                            <input type="time" name="working_hours[{{ $dayKey }}][close]" value="17:00" class="input-modern text-sm">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary-modern">
                                    <i class="fas fa-save mr-2"></i>
                                    حفظ إعدادات العمل
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div id="notifications-section" class="settings-section hidden">
                    <div class="card-modern overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-bell text-amber-600 mr-3"></i>
                                إعدادات التنبيهات
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">ضبط التنبيهات والإشعارات</p>
                        </div>
                        <form id="notifications-form" class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">تنبيهات المخزون</h4>
                                    
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">تنبيه المخزون المنخفض</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">إشعار عند انخفاض المخزون</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="low_stock_alert" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">حد التنبيه للمخزون</label>
                                        <input type="number" name="low_stock_threshold" value="5" min="1" class="input-modern">
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">طرق الإشعار</h4>
                                    
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">إشعارات البريد الإلكتروني</span>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="email_notifications" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">إشعارات الرسائل النصية</span>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="sms_notifications" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">التقارير الدورية</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">تقرير يومي</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="daily_report" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">تقرير أسبوعي</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="weekly_report" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">تقرير شهري</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="monthly_report" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary-modern">
                                    <i class="fas fa-save mr-2"></i>
                                    حفظ إعدادات التنبيهات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Permissions Section -->
                <div id="permissions-section" class="settings-section hidden">
                    <div class="card-modern overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-shield-alt text-purple-600 mr-3"></i>
                                صلاحيات المتجر
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">تحديد الصلاحيات المتاحة لمستخدمي المتجر</p>
                        </div>
                        <div class="p-6">
                            <div id="permissions-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Permissions will be loaded here -->
                            </div>
                            <div class="flex justify-end mt-6">
                                <button onclick="savePermissions()" class="btn-primary-modern">
                                    <i class="fas fa-save mr-2"></i>
                                    حفظ الصلاحيات
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div id="security-section" class="settings-section hidden">
                    <div class="card-modern overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-lock text-red-600 mr-3"></i>
                                إعدادات الأمان
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">تعزيز أمان المتجر والبيانات</p>
                        </div>
                        <form id="security-form" class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">المصادقة الثنائية</span>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">تفعيل المصادقة الثنائية لجميع المستخدمين</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="enable_two_factor" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">مهلة انتهاء الجلسة (دقيقة)</label>
                                        <select name="session_timeout" class="input-modern">
                                            <option value="15">15 دقيقة</option>
                                            <option value="30">30 دقيقة</option>
                                            <option value="60">ساعة واحدة</option>
                                            <option value="480" selected>8 ساعات</option>
                                            <option value="1440">24 ساعة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">عناوين IP المسموحة</label>
                                        <textarea name="allowed_ips" rows="4" placeholder="192.168.1.1&#10;10.0.0.1&#10;(واحد في كل سطر)" class="input-modern"></textarea>
                                        <p class="text-xs text-gray-500 mt-1">اتركه فارغاً للسماح لجميع عناوين IP</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تكرار النسخ الاحتياطي</label>
                                        <select name="backup_frequency" class="input-modern">
                                            <option value="daily">يومي</option>
                                            <option value="weekly" selected>أسبوعي</option>
                                            <option value="monthly">شهري</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">سجلات المراجعة</span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">حفظ سجل بجميع العمليات المهمة</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="audit_logs" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="btn-primary-modern">
                                    <i class="fas fa-save mr-2"></i>
                                    حفظ إعدادات الأمان
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Appearance Section -->
                <div id="appearance-section" class="settings-section hidden">
                    <div class="card-modern overflow-hidden">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-pink-50 to-pink-100 dark:from-pink-900/20 dark:to-pink-800/20">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                <i class="fas fa-palette text-pink-600 mr-3"></i>
                                مظهر المتجر
                            </h3>
                            <p class="mt-2 text-gray-600 dark:text-gray-400">تخصيص الشعار والمظهر العام</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Logo Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">شعار المتجر</label>
                                <div class="flex items-center space-x-6 space-x-reverse">
                                    <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center overflow-hidden">
                                        @if($store->logo)
                                            <img src="{{ Storage::url($store->logo) }}" alt="Store Logo" class="w-full h-full object-cover" id="logo-preview">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-2xl" id="logo-placeholder"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="file" id="logo-input" accept="image/*" class="hidden">
                                        <button type="button" onclick="document.getElementById('logo-input').click()" class="btn-primary-modern">
                                            <i class="fas fa-upload mr-2"></i>
                                            رفع شعار جديد
                                        </button>
                                        <p class="text-xs text-gray-500 mt-2">PNG, JPG حتى 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t pt-6">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">إعدادات العرض</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">لون المتجر الأساسي</label>
                                        <div class="flex space-x-2 space-x-reverse">
                                            <input type="color" value="#3B82F6" class="w-12 h-10 rounded border-2 border-gray-300">
                                            <input type="text" value="#3B82F6" class="input-modern flex-1">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">لون المتجر الثانوي</label>
                                        <div class="flex space-x-2 space-x-reverse">
                                            <input type="color" value="#10B981" class="w-12 h-10 rounded border-2 border-gray-300">
                                            <input type="text" value="#10B981" class="input-modern flex-1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const storeId = {{ $store->id }};

// Navigation between sections
function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.settings-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Remove active class from all nav buttons
    document.querySelectorAll('.settings-nav-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById(sectionName + '-section').classList.remove('hidden');
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    // Load specific section data if needed
    if (sectionName === 'permissions') {
        loadPermissions();
    }
}

// Form submissions
document.getElementById('basic-form').addEventListener('submit', function(e) {
    e.preventDefault();
    submitForm('basic-settings', new FormData(this));
});

document.getElementById('business-form').addEventListener('submit', function(e) {
    e.preventDefault();
    submitForm('business-settings', new FormData(this));
});

document.getElementById('notifications-form').addEventListener('submit', function(e) {
    e.preventDefault();
    submitForm('notification-settings', new FormData(this));
});

document.getElementById('security-form').addEventListener('submit', function(e) {
    e.preventDefault();
    submitForm('security-settings', new FormData(this));
});

// Logo upload
document.getElementById('logo-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        uploadLogo(file);
    }
});

function submitForm(endpoint, formData) {
    fetch(`/admin/stores/${storeId}/${endpoint}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('تم حفظ الإعدادات بنجاح', 'success');
        } else {
            showToast('حدث خطأ أثناء حفظ الإعدادات', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ أثناء حفظ الإعدادات', 'error');
    });
}

function uploadLogo(file) {
    const formData = new FormData();
    formData.append('logo', file);
    
    fetch(`/admin/stores/${storeId}/logo`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('logo-preview').src = data.logo_url;
            document.getElementById('logo-placeholder').style.display = 'none';
            showToast('تم رفع الشعار بنجاح', 'success');
        } else {
            showToast('حدث خطأ أثناء رفع الشعار', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ أثناء رفع الشعار', 'error');
    });
}

function loadPermissions() {
    fetch(`/admin/stores/${storeId}/permissions`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        renderPermissions(data.permissions);
    })
    .catch(error => {
        console.error('Error loading permissions:', error);
    });
}

function renderPermissions(permissions) {
    const grid = document.getElementById('permissions-grid');
    grid.innerHTML = '';
    
    Object.keys(permissions).forEach(module => {
        const moduleDiv = document.createElement('div');
        moduleDiv.className = 'space-y-4';
        
        const moduleTitle = document.createElement('h4');
        moduleTitle.className = 'text-lg font-medium text-gray-900 dark:text-white';
        moduleTitle.textContent = getModuleNameInArabic(module);
        
        moduleDiv.appendChild(moduleTitle);
        
        Object.keys(permissions[module]).forEach(permission => {
            const permissionDiv = document.createElement('div');
            permissionDiv.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg';
            
            permissionDiv.innerHTML = `
                <span class="text-sm font-medium text-gray-900 dark:text-white">${getPermissionNameInArabic(permission)}</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" data-module="${module}" data-permission="${permission}" 
                           ${permissions[module][permission] ? 'checked' : ''} 
                           class="sr-only peer permission-checkbox">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
            `;
            
            moduleDiv.appendChild(permissionDiv);
        });
        
        grid.appendChild(moduleDiv);
    });
}

function getModuleNameInArabic(module) {
    const names = {
        'products': 'المنتجات',
        'invoices': 'الفواتير',
        'reports': 'التقارير',
        'users': 'المستخدمون'
    };
    return names[module] || module;
}

function getPermissionNameInArabic(permission) {
    const names = {
        'view': 'عرض',
        'create': 'إنشاء',
        'edit': 'تعديل',
        'delete': 'حذف',
        'import': 'استيراد',
        'export': 'تصدير',
        'print': 'طباعة',
        'daily': 'تقرير يومي',
        'monthly': 'تقرير شهري',
        'inventory': 'تقرير المخزون',
        'sales': 'تقرير المبيعات',
        'financial': 'تقرير مالي'
    };
    return names[permission] || permission;
}

function savePermissions() {
    const permissions = {};
    
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        const module = checkbox.dataset.module;
        const permission = checkbox.dataset.permission;
        
        if (!permissions[module]) {
            permissions[module] = {};
        }
        
        permissions[module][permission] = checkbox.checked;
    });
    
    fetch(`/admin/stores/${storeId}/permissions`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ permissions })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('تم حفظ الصلاحيات بنجاح', 'success');
        } else {
            showToast('حدث خطأ أثناء حفظ الصلاحيات', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ أثناء حفظ الصلاحيات', 'error');
    });
}

function resetAllSettings() {
    if (confirm('هل أنت متأكد من إعادة تعيين جميع الإعدادات؟ لن يمكن التراجع عن هذا الإجراء.')) {
        fetch(`/admin/stores/${storeId}/reset-settings`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('تم إعادة تعيين الإعدادات بنجاح', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('حدث خطأ أثناء إعادة التعيين', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('حدث خطأ أثناء إعادة التعيين', 'error');
        });
    }
}

function showToast(message, type) {
    // Implementation of toast notification
    const toast = document.createElement('div');
    toast.className = `fixed top-4 left-4 p-4 rounded-lg shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Add styles for active navigation
document.head.insertAdjacentHTML('beforeend', `
<style>
.settings-nav-btn {
    transition: all 0.2s ease;
    background: transparent;
    color: #6B7280;
}

.settings-nav-btn:hover {
    background: #F3F4F6;
    color: #374151;
}

.settings-nav-btn.active {
    background: #3B82F6;
    color: white;
}

.dark .settings-nav-btn:hover {
    background: #374151;
    color: #F9FAFB;
}

.dark .settings-nav-btn.active {
    background: #3B82F6;
    color: white;
}
</style>
`);
</script>
@endpush
@endsection
