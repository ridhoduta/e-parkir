<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'              => 'Budi Santoso',
                'plat_nomor'        => 'B 1234 ABC',
                'tipe_member'       => 'Gold',
                'tanggal_mulai'     => date('Y-m-d'),
                'tanggal_akhir'     => date('Y-m-d', strtotime('+1 year')),
                'discount_percent'  => 10.00,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
            [
                'nama'              => 'Siti Aminah',
                'plat_nomor'        => 'D 5678 XYZ',
                'tipe_member'       => 'Silver',
                'tanggal_mulai'     => date('Y-m-d'),
                'tanggal_akhir'     => date('Y-m-d', strtotime('+6 months')),
                'discount_percent'  => 5.00,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('members')->insertBatch($data);
    }
}
