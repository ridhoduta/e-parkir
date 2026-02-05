<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('RoleSeeder');
        $this->call('UserSeeder');
        $this->call('AreaSeeder');
        $this->call('TipeKendaraanSeeder');
        $this->call('TarifParkirSeeder');
        $this->call('MemberTypeSeeder');
        $this->call('MemberSeeder');
    }
}
