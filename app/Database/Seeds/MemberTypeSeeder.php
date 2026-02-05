<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MemberTypeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'             => 'Silver',
                'discount_percent' => 5.00,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'nama'             => 'Gold',
                'discount_percent' => 10.00,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'nama'             => 'Platinum',
                'discount_percent' => 20.00,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('member_types')->insertBatch($data);
    }
}
