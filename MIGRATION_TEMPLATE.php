<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * MIGRATION TEMPLATE UNTUK SOFT DELETE
 * 
 * Jika Anda membuat table baru atau ingin menggunakan migration,
 * gunakan template ini sebagai referensi.
 * 
 * Cara pakai:
 * 1. Copy file ini ke: app/Database/Migrations/
 * 2. Rename sesuai timestamp (contoh: 2024020410300_AddSoftDeleteToUsers.php)
 * 3. Jalankan: php spark migrate
 */

class AddSoftDeleteToExistingTables extends Migration
{
    public function up()
    {
        // ===== ADD deleted_at COLUMN TO USERS TABLE =====
        if (!$this->db->fieldExists('deleted_at', 'users')) {
            $this->forge->addColumn('users', [
                'deleted_at' => [
                    'type'   => 'DATETIME',
                    'null'   => true,
                    'default' => null,
                    'comment' => 'Soft delete timestamp'
                ],
            ]);
        }

        // ===== ADD deleted_at COLUMN TO MEMBERS TABLE =====
        if (!$this->db->fieldExists('deleted_at', 'members')) {
            $this->forge->addColumn('members', [
                'deleted_at' => [
                    'type'   => 'DATETIME',
                    'null'   => true,
                    'default' => null,
                    'comment' => 'Soft delete timestamp'
                ],
            ]);
        }

        // ===== ADD deleted_at COLUMN TO TRANSAKSI_PARKIRS TABLE =====
        if (!$this->db->fieldExists('deleted_at', 'transaksi_parkirs')) {
            $this->forge->addColumn('transaksi_parkirs', [
                'deleted_at' => [
                    'type'   => 'DATETIME',
                    'null'   => true,
                    'default' => null,
                    'comment' => 'Soft delete timestamp'
                ],
            ]);
        }

        // ===== ADD deleted_at COLUMN TO ROLES TABLE (OPTIONAL) =====
        if ($this->db->tableExists('roles') && !$this->db->fieldExists('deleted_at', 'roles')) {
            $this->forge->addColumn('roles', [
                'deleted_at' => [
                    'type'   => 'DATETIME',
                    'null'   => true,
                    'default' => null,
                    'comment' => 'Soft delete timestamp'
                ],
            ]);
        }

        // ===== ADD deleted_at COLUMN TO AREAS TABLE (OPTIONAL) =====
        if ($this->db->tableExists('areas') && !$this->db->fieldExists('deleted_at', 'areas')) {
            $this->forge->addColumn('areas', [
                'deleted_at' => [
                    'type'   => 'DATETIME',
                    'null'   => true,
                    'default' => null,
                    'comment' => 'Soft delete timestamp'
                ],
            ]);
        }

        // ===== ADD deleted_at COLUMN TO TIPE_KENDARAAN TABLE (OPTIONAL) =====
        if ($this->db->tableExists('tipe_kendaraan') && !$this->db->fieldExists('deleted_at', 'tipe_kendaraan')) {
            $this->forge->addColumn('tipe_kendaraan', [
                'deleted_at' => [
                    'type'   => 'DATETIME',
                    'null'   => true,
                    'default' => null,
                    'comment' => 'Soft delete timestamp'
                ],
            ]);
        }
    }

    public function down()
    {
        // ===== DROP deleted_at COLUMN FROM USERS TABLE =====
        if ($this->db->fieldExists('deleted_at', 'users')) {
            $this->forge->dropColumn('users', 'deleted_at');
        }

        // ===== DROP deleted_at COLUMN FROM MEMBERS TABLE =====
        if ($this->db->fieldExists('deleted_at', 'members')) {
            $this->forge->dropColumn('members', 'deleted_at');
        }

        // ===== DROP deleted_at COLUMN FROM TRANSAKSI_PARKIRS TABLE =====
        if ($this->db->fieldExists('deleted_at', 'transaksi_parkirs')) {
            $this->forge->dropColumn('transaksi_parkirs', 'deleted_at');
        }

        // ===== DROP deleted_at COLUMN FROM ROLES TABLE =====
        if ($this->db->tableExists('roles') && $this->db->fieldExists('deleted_at', 'roles')) {
            $this->forge->dropColumn('roles', 'deleted_at');
        }

        // ===== DROP deleted_at COLUMN FROM AREAS TABLE =====
        if ($this->db->tableExists('areas') && $this->db->fieldExists('deleted_at', 'areas')) {
            $this->forge->dropColumn('areas', 'deleted_at');
        }

        // ===== DROP deleted_at COLUMN FROM TIPE_KENDARAAN TABLE =====
        if ($this->db->tableExists('tipe_kendaraan') && $this->db->fieldExists('deleted_at', 'tipe_kendaraan')) {
            $this->forge->dropColumn('tipe_kendaraan', 'deleted_at');
        }
    }
}

/*
 * ===== ALTERNATIVE: CREATE NEW TABLE WITH SOFT DELETE =====
 * 
 * Jika Anda membuat table baru, gunakan template ini:
 * 
 * class CreateUsersTable extends Migration
 * {
 *     public function up()
 *     {
 *         $this->forge->addField([
 *             'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
 *             'username'    => ['type' => 'VARCHAR', 'constraint' => 100],
 *             'email'       => ['type' => 'VARCHAR', 'constraint' => 100],
 *             'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
 *             'created_at'  => ['type' => 'DATETIME', 'null' => true],
 *             'updated_at'  => ['type' => 'DATETIME', 'null' => true],
 *             'deleted_at'  => ['type' => 'DATETIME', 'null' => true],  // ✅ SOFT DELETE
 *         ]);
 * 
 *         $this->forge->addKey('id', false, true);
 *         $this->forge->addUniqueKey('email');
 *         $this->forge->createTable('users');
 *     }
 * 
 *     public function down()
 *     {
 *         $this->forge->dropTable('users');
 *     }
 * }
 */
