<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreSettingsController extends Controller
{
    // Middleware is handled in routes/web.php

    public function index(Store $store)
    {
        $store->load(['owner', 'users']);

        return view('admin.store-settings.index', compact('store'));
    }

    public function updateBasicSettings(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'address_ar' => 'nullable|string|max:500',
        ]);

        $store->update($request->only([
            'name', 'name_ar', 'description', 'description_ar',
            'phone', 'email', 'address', 'address_ar'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الإعدادات الأساسية بنجاح'
        ]);
    }

    public function updateBusinessSettings(Request $request, Store $store)
    {
        $request->validate([
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|size:3',
            'timezone' => 'nullable|string',
            'working_hours' => 'nullable|array',
            'working_hours.*.day' => 'required|string',
            'working_hours.*.open' => 'nullable|string',
            'working_hours.*.close' => 'nullable|string',
            'working_hours.*.is_closed' => 'boolean',
        ]);

        $settings = $store->settings ?? [];

        $settings['business'] = [
            'tax_rate' => $request->tax_rate,
            'currency' => $request->currency ?? 'SAR',
            'timezone' => $request->timezone ?? 'Asia/Riyadh',
            'working_hours' => $request->working_hours ?? [],
        ];

        $store->update([
            'tax_rate' => $request->tax_rate,
            'currency' => $request->currency,
            'settings' => $settings
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات العمل بنجاح'
        ]);
    }

    public function updateNotificationSettings(Request $request, Store $store)
    {
        $request->validate([
            'low_stock_alert' => 'boolean',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'daily_report' => 'boolean',
            'weekly_report' => 'boolean',
            'monthly_report' => 'boolean',
        ]);

        $settings = $store->settings ?? [];

        $settings['notifications'] = [
            'low_stock_alert' => $request->boolean('low_stock_alert'),
            'low_stock_threshold' => $request->low_stock_threshold ?? 5,
            'email_notifications' => $request->boolean('email_notifications'),
            'sms_notifications' => $request->boolean('sms_notifications'),
            'reports' => [
                'daily' => $request->boolean('daily_report'),
                'weekly' => $request->boolean('weekly_report'),
                'monthly' => $request->boolean('monthly_report'),
            ]
        ];

        $store->update(['settings' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات التنبيهات بنجاح'
        ]);
    }

    public function updateSecuritySettings(Request $request, Store $store)
    {
        $request->validate([
            'enable_two_factor' => 'boolean',
            'session_timeout' => 'nullable|integer|min:15|max:1440',
            'allowed_ips' => 'nullable|string',
            'backup_frequency' => 'nullable|string|in:daily,weekly,monthly',
            'audit_logs' => 'boolean',
        ]);

        $settings = $store->settings ?? [];

        $settings['security'] = [
            'enable_two_factor' => $request->boolean('enable_two_factor'),
            'session_timeout' => $request->session_timeout ?? 480,
            'allowed_ips' => array_filter(explode("\n", $request->allowed_ips ?? '')),
            'backup_frequency' => $request->backup_frequency ?? 'weekly',
            'audit_logs' => $request->boolean('audit_logs'),
        ];

        $store->update(['settings' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث إعدادات الأمان بنجاح'
        ]);
    }

    public function updateLogo(Request $request, Store $store)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old logo if exists
        if ($store->logo && Storage::exists('public/' . $store->logo)) {
            Storage::delete('public/' . $store->logo);
        }

        $logoPath = $request->file('logo')->store('store-logos', 'public');

        $store->update(['logo' => $logoPath]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الشعار بنجاح',
            'logo_url' => Storage::url($logoPath)
        ]);
    }

    public function getPermissions(Store $store)
    {
        $permissions = [
            'products' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => false,
                'import' => true,
                'export' => true
            ],
            'invoices' => [
                'view' => true,
                'create' => true,
                'edit' => true,
                'delete' => false,
                'print' => true
            ],
            'reports' => [
                'daily' => true,
                'monthly' => true,
                'inventory' => true,
                'sales' => true,
                'financial' => false
            ],
            'users' => [
                'view' => false,
                'create' => false,
                'edit' => false,
                'delete' => false
            ]
        ];

        return response()->json([
            'permissions' => $store->settings['permissions'] ?? $permissions
        ]);
    }

    public function updatePermissions(Request $request, Store $store)
    {
        $request->validate([
            'permissions' => 'required|array',
        ]);

        $settings = $store->settings ?? [];
        $settings['permissions'] = $request->permissions;

        $store->update(['settings' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الصلاحيات بنجاح'
        ]);
    }

    public function resetSettings(Store $store)
    {
        $store->update([
            'settings' => [],
            'tax_rate' => 15.0,
            'currency' => 'SAR'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة تعيين الإعدادات إلى القيم الافتراضية'
        ]);
    }
}
