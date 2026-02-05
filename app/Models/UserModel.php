<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'nama',
        'email',
        'password',
        'role_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Disable auto timestamp - akan di-handle manual di controller
    protected $useTimestamps = false;

    /**
     * Cari user berdasarkan username (hanya active user)
     */
    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Cari user berdasarkan email (hanya active user)
     */
    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}
