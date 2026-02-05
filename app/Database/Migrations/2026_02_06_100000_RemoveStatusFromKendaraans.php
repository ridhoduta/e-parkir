<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveStatusFromKendaraans extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('kendaraans', 'status');
    }

    public function down()
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
}
