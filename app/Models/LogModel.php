<?php

namespace App\Models;

class LogModel extends BaseModel
{
    protected $table = 'log_aktivitass';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'aktivitas',
        'deskripsi',
        'ip_address',
        'user_agent',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = false;

    public function findWithUser()
    {
        return $this->select('log_aktivitass.*, users.nama as user_nama')
            ->join('users', 'users.id = log_aktivitass.user_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
