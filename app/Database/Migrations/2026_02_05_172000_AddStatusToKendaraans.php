<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToKendaraans extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kendaraans', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
                'after'      => 'tipe_kendaraan_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('kendaraans', 'status');
    }
}
