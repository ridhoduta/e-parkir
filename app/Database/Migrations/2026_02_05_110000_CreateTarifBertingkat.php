<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTarifBertingkat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tipe_kendaraan_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'jam_mulai' => [
                'type' => 'INT',
                'comment' => 'Start hour (inclusive)',
            ],
            'jam_selesai' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'End hour (inclusive), null means until forever',
            ],
            'tarif' => [
                'type' => 'INT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tipe_kendaraan_id', 'tipe_kendaraans', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tarif_bertingkat');
    }

    public function down()
    {
        $this->forge->dropTable('tarif_bertingkat');
    }
}
