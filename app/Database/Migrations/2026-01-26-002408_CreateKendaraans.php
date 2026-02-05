<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKendaraans extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'plat_nomor' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],
            'tipe_kendaraan_id' => [
                'type'     => 'INT',
                'unsigned' => true,
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

        $this->forge->createTable('kendaraans');
    }

    public function down()
    {
        $this->forge->dropTable('kendaraans');
    }
}
