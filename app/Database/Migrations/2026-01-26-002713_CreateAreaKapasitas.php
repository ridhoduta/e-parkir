<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAreaKapasitas extends Migration
{
    // Migration ini sudah digabungkan ke dalam CreateAreas
    // Field kapasitas sekarang merupakan bagian dari tabel areas
    public function up()
    {
        // Tidak perlu menjalankan apa-apa, sudah ada di CreateAreas
    }

    public function down()
    {
        // Tidak perlu menjalankan apa-apa
    }
}

