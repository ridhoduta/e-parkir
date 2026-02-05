<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBackupDatabases extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_file' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('backup_databases');
    }

    public function down()
    {
        $this->forge->dropTable('backup_databases');
    }
}
