<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTarifParkirs extends Migration
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
            // ✅ SOFT DELETE
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey(
            'tipe_kendaraan_id',
            'tipe_kendaraans',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('tarif_parkirs');
    }

    public function down()
    {
        $this->forge->dropTable('tarif_parkirs');
    }
}
