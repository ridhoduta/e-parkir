<?php

namespace App\Models;

use CodeIgniter\Model;

class TipeKendaraanModel extends Model
{
    protected $table = 'tipe_kendaraans';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_tipe',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Soft delete
    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    // Disable auto timestamp - akan di-handle manual di controller
    protected $useTimestamps = false;
}
