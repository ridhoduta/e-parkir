<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BaseModel dengan Soft Delete
 * Semua model yang menggunakan soft delete harus extend class ini
 */
class BaseModel extends Model
{
    // ====== SOFT DELETE CONFIGURATION ======
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';
    
    /**
     * Override default findAll untuk exclude soft deleted records
     */
    public function findAll($limit = 0, $offset = 0)
    {
        // Hanya ambil data yang tidak di-soft delete
        return parent::findAll($limit, $offset);
    }

    /**
     * Ambil semua data termasuk yang sudah soft delete
     */
    public function findAllWithDeleted()
    {
        return $this->withDeleted()->findAll();
    }

    /**
     * Ambil hanya data yang sudah soft delete
     */
    public function findOnlyDeleted()
    {
        return $this->onlyDeleted()->findAll();
    }

    /**
     * Cari satu record berdasarkan ID (exclude soft delete)
     */
    public function findById($id)
    {
        return $this->find($id);
    }

    /**
     * Cari satu record berdasarkan ID termasuk soft delete
     */
    public function findByIdWithDeleted($id)
    {
        return $this->withDeleted()->find($id);
    }

    /**
     * Custom where clause yang exclude soft delete
     * Gunakan method ini sebagai pengganti where() untuk query kompleks
     */
    public function whereActive()
    {
        // Otomatis exclude soft delete karena $useSoftDeletes = true
        return $this;
    }

    /**
     * Custom where clause yang include soft delete
     */
    public function whereWithDeleted()
    {
        return $this->withDeleted();
    }

    /**
     * Restore data yang sudah soft delete
     */
    public function restore($id = null)
    {
        if ($id === null) {
            return false;
        }

        $data = [$this->deletedField => null];
        return $this->update($id, $data);
    }

    /**
     * Permanently delete (hard delete)
     */
    public function forceDelete($id = null)
    {
        if ($id === null) {
            return false;
        }

        return $this->db->table($this->table)->delete([$this->primaryKey => $id]);
    }
}
