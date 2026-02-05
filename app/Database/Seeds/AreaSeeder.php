<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_area'   => 'Area Motor',
                'kapasitas'   => 50,
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_area'   => 'Area Mobil',
                'kapasitas'   => 30,
                'created_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nama_area'   => 'Area VIP',
                'kapasitas'   => 10,
                'created_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('areas')->insertBatch($data);
    }
}
