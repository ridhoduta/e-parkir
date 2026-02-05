<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixAreaAttributes extends Migration
{
    public function up()
    {
        // 1. Add kode_area to areas table
        $fields = [
            'kode_area' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'unique' => true,
                'after' => 'id'
            ],
        ];
        $this->forge->addColumn('areas', $fields);

        // 2. Create area_kapasitas table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'area_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'tipe_kendaraan_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'kapasitas' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('area_id', 'areas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('tipe_kendaraan_id', 'tipe_kendaraans', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('area_kapasitas');
    }

    public function down()
    {
        $this->forge->dropTable('area_kapasitas');
        $this->forge->dropColumn('areas', 'kode_area');
    }
}
