<?php

/**
 * Helper untuk Soft Delete Operations
 * 
 * Gunakan helper ini untuk mengelola soft delete di aplikasi
 */

if (!function_exists('softDelete')) {
    /**
     * Soft delete sebuah record
     * 
     * @param string $table Nama tabel
     * @param mixed $id ID atau array of IDs
     * @return bool
     */
    function softDelete($table, $id)
    {
        $db = \Config\Database::connect();
        
        if (is_array($id)) {
            return $db->table($table)
                ->whereIn('id', $id)
                ->update(['deleted_at' => date('Y-m-d H:i:s')]);
        }
        
        return $db->table($table)
            ->where('id', $id)
            ->update(['deleted_at' => date('Y-m-d H:i:s')]);
    }
}

if (!function_exists('restoreSoftDelete')) {
    /**
     * Restore record yang sudah di-soft delete
     * 
     * @param string $table Nama tabel
     * @param mixed $id ID atau array of IDs
     * @return bool
     */
    function restoreSoftDelete($table, $id)
    {
        $db = \Config\Database::connect();
        
        if (is_array($id)) {
            return $db->table($table)
                ->whereIn('id', $id)
                ->update(['deleted_at' => null]);
        }
        
        return $db->table($table)
            ->where('id', $id)
            ->update(['deleted_at' => null]);
    }
}

if (!function_exists('forceDelete')) {
    /**
     * Permanently delete record (hard delete)
     * 
     * @param string $table Nama tabel
     * @param mixed $id ID atau array of IDs
     * @return bool
     */
    function forceDelete($table, $id)
    {
        $db = \Config\Database::connect();
        
        if (is_array($id)) {
            return $db->table($table)->whereIn('id', $id)->delete();
        }
        
        return $db->table($table)->where('id', $id)->delete();
    }
}

if (!function_exists('isDeleted')) {
    /**
     * Check apakah record sudah di-soft delete
     * 
     * @param string $table Nama tabel
     * @param int $id ID
     * @return bool
     */
    function isDeleted($table, $id)
    {
        $db = \Config\Database::connect();
        
        $result = $db->table($table)
            ->where('id', $id)
            ->whereNotNull('deleted_at')
            ->countAllResults();
        
        return $result > 0;
    }
}

if (!function_exists('getDeletedRecords')) {
    /**
     * Ambil hanya record yang sudah di-soft delete
     * 
     * @param string $table Nama tabel
     * @return array
     */
    function getDeletedRecords($table)
    {
        $db = \Config\Database::connect();
        
        return $db->table($table)
            ->whereNotNull('deleted_at')
            ->get()
            ->getResultArray();
    }
}

if (!function_exists('getAllRecords')) {
    /**
     * Ambil semua record termasuk yang soft delete
     * 
     * @param string $table Nama tabel
     * @return array
     */
    function getAllRecords($table)
    {
        $db = \Config\Database::connect();
        
        return $db->table($table)
            ->get()
            ->getResultArray();
    }
}
