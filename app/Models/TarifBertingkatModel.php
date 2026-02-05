<?php

namespace App\Models;

use CodeIgniter\Model;

class TarifBertingkatModel extends Model
{
    protected $table = 'tarif_bertingkat';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tipe_kendaraan_id',
        'jam_mulai',
        'jam_selesai',
        'tarif',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
