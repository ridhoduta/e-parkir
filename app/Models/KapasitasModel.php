<?php

namespace App\Models;

use CodeIgniter\Model;

class KapasitasModel extends Model
{
    protected $table = 'area_kapasitas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'area_id',
        'kapasitas',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    protected $useTimestamps = false;
}
