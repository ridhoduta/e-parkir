<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransaksiParkir extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_tiket' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'unique'     => true,
            ],
            'plat_nomor' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'area_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'tipe_kendaraan_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'waktu_masuk' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'waktu_keluar' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'durasi_menit' => [
                'type' => 'INT',
                'null' => true,
            ],
            'tarif' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'metode_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'tunai, kartu, e-wallet',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'masuk',
                'comment'    => 'masuk, keluar, pembayaran_pending, selesai',
            ],
            'petugas_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('area_id', 'areas', 'id');
        $this->forge->addForeignKey('tipe_kendaraan_id', 'tipe_kendaraans', 'id');
        $this->forge->addForeignKey('petugas_id', 'users', 'id');
        $this->forge->createTable('transaksi_parkirs');
    }

    public function down()
    {
        $this->forge->dropTable('transaksi_parkirs');
    }
}
