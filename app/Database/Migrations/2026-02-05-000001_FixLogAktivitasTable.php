<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixLogAktivitasTable extends Migration
{
    public function up()
    {
        // 1. Rename table if old one exists
        if ($this->db->tableExists('log_aktivitas')) {
            $this->forge->renameTable('log_aktivitas', 'log_aktivitass');
        } else {
            // Create from scratch if it doesn't exist for some reason
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'null' => true,
                ],
                'aktivitas' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('log_aktivitass');
        }

        // 2. Add missing columns to log_aktivitass
        $fields = [
            'deskripsi' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'aktivitas'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'after' => 'deskripsi'
            ],
            'user_agent' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'ip_address'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'created_at'
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'updated_at'
            ],
        ];

        $this->forge->addColumn('log_aktivitass', $fields);
    }

    public function down()
    {
        $this->forge->renameTable('log_aktivitass', 'log_aktivitas');
        $this->forge->dropColumn('log_aktivitas', ['deskripsi', 'ip_address', 'user_agent', 'updated_at', 'deleted_at']);
    }
}
