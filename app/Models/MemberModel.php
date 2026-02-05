<?php

namespace App\Models;

class MemberModel extends BaseModel
{
    protected $table = 'members';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama',
        'plat_nomor',
        'tipe_member',
        'tanggal_mulai',
        'tanggal_akhir',
        'discount_percent',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = false;
}
