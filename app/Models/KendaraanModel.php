<?php

namespace App\Models;

use CodeIgniter\Model;

class KendaraanModel extends Model
{
    protected $table = 'kendaraans';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'plat_nomor',
        'pemilik',
        'tipe_kendaraan_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    protected $useTimestamps = false;
}
