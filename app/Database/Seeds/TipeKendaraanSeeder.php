<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TipeKendaraanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_tipe'  => 'Motor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama_tipe'  => 'Mobil',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tipe_kendaraans')->insertBatch($data);
    }
}
