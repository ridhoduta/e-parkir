<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_role' => 'owner',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_role' => 'petugas',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('roles')->insertBatch($data);
    }
}
