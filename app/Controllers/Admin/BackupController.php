<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BackupController extends BaseController
{
    public function index()
    {
        if (!session('logged_in') || session('role_id') != 2) {
            return redirect()->to('/login');
        }

        return view('admin/backup/index', ['title' => 'Backup & Restore']);
    }

    public function download()
    {
        if (!session('logged_in') || session('role_id') != 2) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $dbname = $db->getDatabase();
        $filename = $dbname . '_' . date('Y-m-d_H-i-s') . '.sql';

        // CodeIgniter 4 doesn't have a built-in DB Utility for dump in the core yet (like v3)
        // We will create a simple export or try to use command line if available.
        
        // For this demo, we'll generate a simple structure and data dump manually for key tables
        $tables = $db->listTables();
        $output = "-- Database Backup: " . $dbname . "\n";
        $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            $output .= "-- Table: " . $table . "\n";
            $query = $db->query("SHOW CREATE TABLE " . $table)->getRowArray();
            $output .= $query['Create Table'] . ";\n\n";

            $rows = $db->table($table)->get()->getResultArray();
            foreach ($rows as $row) {
                $columns = array_keys($row);
                $values = array_values($row);
                $values = array_map(function($v) use ($db) {
                    if ($v === null) return "NULL";
                    return $db->escape($v);
                }, $values);

                $output .= "INSERT INTO " . $table . " (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ");\n";
            }
            $output .= "\n";
        }

        helper('log');
        \save_log('BACKUP_DB', 'Melakukan backup database ' . $dbname);

        return $this->response
            ->setHeader('Content-Type', 'application/sql')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($output);
    }
}
