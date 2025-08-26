<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BackupController extends Controller
{
    public function create()
    {
        return view('backup.create');
    }

    public function download(Request $request)
    {
        try {
            // إنشاء نسخة احتياطية من قاعدة البيانات
            $filename = 'backup_' . now()->format('Y_m_d_H_i_s') . '.sql';

            // تصدير قاعدة البيانات (SQLite)
            $database = database_path('database.sqlite');

            if (!file_exists($database)) {
                return redirect()->back()->with('error', 'ملف قاعدة البيانات غير موجود');
            }

            // نسخ ملف قاعدة البيانات
            $backupPath = storage_path('app/backups/' . $filename);

            if (!is_dir(dirname($backupPath))) {
                mkdir(dirname($backupPath), 0755, true);
            }

            copy($database, $backupPath);

            return response()->download($backupPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }
}
