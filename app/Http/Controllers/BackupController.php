<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function create()
    {
        return view('backup.create');
    }

    public function download(Request $request)
    {
        $tables = [
            'users',
            'product_categories',
            'products',
            'invoices',
            'invoice_items',
            'return_items',
            'repairs',
            'cash_transfers'
        ];

        $sql = "-- Database Backup Created at " . Carbon::now() . "\n\n";

        foreach ($tables as $table) {
            $sql .= "-- Table: $table\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            
            // Get table structure
            $createTable = DB::select("SHOW CREATE TABLE `$table`");
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            
            // Get table data
            $rows = DB::table($table)->get();
            
            if ($rows->count() > 0) {
                $sql .= "INSERT INTO `$table` VALUES\n";
                $values = [];
                
                foreach ($rows as $row) {
                    $rowValues = array_map(function($value) {
                        return $value === null ? 'NULL' : "'" . addslashes($value) . "'";
                    }, (array) $row);
                    
                    $values[] = '(' . implode(', ', $rowValues) . ')';
                }
                
                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }

        $filename = 'backup_' . Carbon::now()->format('Y_m_d_H_i_s') . '.sql';

        return response($sql)
            ->header('Content-Type', 'application/sql')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
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
