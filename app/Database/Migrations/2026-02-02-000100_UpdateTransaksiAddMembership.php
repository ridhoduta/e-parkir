<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTransaksiAddMembership extends Migration
{
    public function up()
    {
        $fields = [
            'member_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'discount_percent' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'discount_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'tarif_awal' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('transaksi_parkirs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaksi_parkirs', ['member_id','discount_percent','discount_amount','tarif_awal']);
    }
}
