<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TarifParkirSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'tipe_kendaraan_id' => 1, // Motor
                'tarif'             => 2000,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'tipe_kendaraan_id' => 2, // Mobil
                'tarif'             => 5000,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('tarif_parkirs')->insertBatch($data);
    }
}
