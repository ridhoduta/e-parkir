<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberTypeModel extends Model
{
    protected $table = 'member_types';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama',
        'discount_percent',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    protected $useTimestamps = false;
}
