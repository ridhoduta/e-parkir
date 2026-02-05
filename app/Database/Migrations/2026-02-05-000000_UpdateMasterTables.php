<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMasterTables extends Migration
{
    public function up()
    {
        // Add columns to areas
        $this->forge->addColumn('areas', [
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'nama_area'
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'kapasitas'
            ],
        ]);

        // Add columns to kendaraans
        $this->forge->addColumn('kendaraans', [
            'pemilik' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'plat_nomor'
            ],
        ]);

        // Add columns to transaksi_parkirs
        $this->forge->addColumn('transaksi_parkirs', [
            'slot_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'area_id'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('areas', ['lokasi', 'foto']);
        $this->forge->dropColumn('kendaraans', ['pemilik']);
        $this->forge->dropColumn('transaksi_parkirs', ['slot_number']);
    }
}
