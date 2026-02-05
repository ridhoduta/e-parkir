<?php

namespace App\Models;

use CodeIgniter\Model;

class TarifParkirModel extends Model
{
    protected $table = 'tarif_parkirs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tipe_kendaraan_id',
        'tarif',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    protected $useTimestamps = false;
}
