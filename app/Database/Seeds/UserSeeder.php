<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'owner', // ✅
                'nama' => 'Owner',
                'email' => 'owner@gmail.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'admin', // ✅
                'nama' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role_id' => 2,
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'petugas', // ✅
                'nama' => 'Petugas',
                'email' => 'petugas@gmail.com',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role_id' => 3,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
