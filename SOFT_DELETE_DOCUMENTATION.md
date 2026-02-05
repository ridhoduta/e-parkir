# Dokumentasi Soft Delete System

## Pengenalan

Sistem Soft Delete memungkinkan Anda untuk menghapus data tanpa benar-benar menghapus dari database. Data yang dihapus akan ditandai dengan waktu penghapusan (timestamp) di kolom `deleted_at`. Ini sangat berguna untuk:

- **Audit trail**: Melacak data yang telah dihapus
- **Keamanan**: Tidak ada data yang hilang selamanya
- **Recovery**: Data dapat dipulihkan jika diperlukan
- **Business Logic**: Misalnya, user yang dihapus tidak bisa login

## Struktur Database

Setiap table yang menggunakan soft delete harus memiliki kolom `deleted_at`:

```sql
ALTER TABLE users ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
ALTER TABLE members ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
ALTER TABLE transaksi_parkirs ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
```

## Implementasi di Model

### 1. Extend BaseModel

Semua model yang menggunakan soft delete harus extend dari `BaseModel`:

```php
<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'username',
        'nama',
        'email',
        'password',
        'role_id',
        'deleted_at', // ✅ Pastikan kolom ini ada
    ];
    
    protected $useTimestamps = false;
}
```

### 2. BaseModel Sudah Mengatur Soft Delete Secara Otomatis

`BaseModel` sudah mengkonfigurasi:
```php
protected $useSoftDeletes = true;
protected $deletedField = 'deleted_at';
```

**Anda tidak perlu menambahkan ini lagi di model child Anda!**

## Penggunaan Soft Delete

### A. Menggunakan Model Methods

#### 1. Menampilkan Data (Exclude Soft Deleted)

```php
$userModel = new UserModel();

// Otomatis exclude soft deleted data
$activeUsers = $userModel->findAll();

// Atau dengan where clause
$user = $userModel->where('username', 'john')->first();
```

#### 2. Menampilkan Data Termasuk Soft Deleted

```php
$userModel = new UserModel();

// Include soft deleted
$allUsers = $userModel->findAllWithDeleted();

// Cari specific record termasuk soft deleted
$user = $userModel->findByIdWithDeleted(1);
```

#### 3. Menampilkan Hanya Data yang Sudah Dihapus

```php
$userModel = new UserModel();
$deletedUsers = $userModel->findOnlyDeleted();
```

#### 4. Soft Delete (Penghapusan Lunak)

```php
$userModel = new UserModel();

// Soft delete single record
$userModel->delete(1); // Akan set deleted_at = current timestamp

// Atau langsung gunakan model
$userModel->delete([1, 2, 3]); // Multiple delete
```

#### 5. Restore (Mengembalikan Data)

```php
$userModel = new UserModel();

// Restore data yang sudah di-soft delete
$userModel->restore(1);
```

#### 6. Hard Delete (Penghapusan Permanen)

```php
$userModel = new UserModel();

// Permanent delete dari database
$userModel->forceDelete(1);
```

### B. Menggunakan Helper Functions

Load helper di controller:

```php
helper('SoftDelete');
```

Kemudian gunakan:

```php
// Soft delete
softDelete('users', 1);
softDelete('users', [1, 2, 3]); // Multiple

// Restore
restoreSoftDelete('users', 1);

// Hard delete
forceDelete('users', 1);

// Check apakah sudah dihapus
if (isDeleted('users', 1)) {
    echo "User sudah dihapus";
}

// Ambil hanya deleted records
$deletedUsers = getDeletedRecords('users');

// Ambil semua termasuk deleted
$allUsers = getAllRecords('users');
```

## Contoh Real-World: Login System

Ketika user mencoba login, data yang sudah soft delete otomatis dikecualikan:

```php
<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();

        // Otomatis hanya mencari user yang AKTIF (tidak di-soft delete)
        $user = $userModel->select('users.*, roles.nama_role')
                          ->join('roles', 'roles.id = users.role_id')
                          ->where('users.username', $username)
                          ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Username tidak ditemukan atau akun sudah dihapus');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah');
        }

        // Set session
        session()->set([
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'nama'      => $user['nama'],
            'role_id'   => $user['role_id'],
            'logged_in' => true,
        ]);

        return redirect()->to('/dashboard');
    }
}
```

## Contoh: Admin Dashboard (Kelola Deleted Records)

```php
<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;

class Users extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        
        $data = [
            'activeUsers'   => $userModel->findAll(),
            'deletedUsers'  => $userModel->findOnlyDeleted(),
            'totalUsers'    => count($userModel->findAllWithDeleted()),
        ];
        
        return view('admin/users/index', $data);
    }

    public function restore($id)
    {
        $userModel = new UserModel();
        $userModel->restore($id);
        
        return redirect()->back()->with('success', 'User berhasil dikembalikan');
    }

    public function destroy($id) // Hard delete
    {
        $userModel = new UserModel();
        $userModel->forceDelete($id);
        
        return redirect()->back()->with('success', 'User berhasil dihapus permanen');
    }
}
```

## Custom Query dengan Soft Delete

Untuk query yang kompleks, Anda dapat menggunakan builder dengan method soft delete:

```php
$userModel = new UserModel();

// Method 1: Default (exclude soft delete)
$users = $userModel
    ->where('role_id', 1)
    ->where('created_at >', '2024-01-01')
    ->findAll();

// Method 2: Include soft delete
$allUsers = $userModel
    ->withDeleted()
    ->where('role_id', 1)
    ->findAll();

// Method 3: Only deleted
$deletedUsers = $userModel
    ->onlyDeleted()
    ->where('role_id', 1)
    ->findAll();
```

## Best Practices

1. **Selalu gunakan BaseModel** untuk model yang membutuhkan soft delete
2. **Jangan lupa kolom deleted_at** di database dan migration
3. **Update view** untuk menampilkan deleted status jika diperlukan
4. **Audit log** - log setiap soft delete untuk keamanan
5. **Backup reguler** - meskipun soft delete aman, tetap backup database
6. **Restore dengan hati-hati** - pastikan restore tidak melanggar business logic

## Testing Soft Delete

```php
// Di test file
public function testSoftDeleteUser()
{
    $userModel = new UserModel();
    
    // Create user
    $userId = $userModel->insert(['username' => 'testuser']);
    
    // Soft delete
    $userModel->delete($userId);
    
    // Verify soft delete
    $deletedUser = $userModel->findByIdWithDeleted($userId);
    $this->assertNotNull($deletedUser['deleted_at']);
    
    // Verify not in active list
    $activeUser = $userModel->find($userId);
    $this->assertNull($activeUser);
    
    // Restore
    $userModel->restore($userId);
    
    // Verify restore
    $restoredUser = $userModel->find($userId);
    $this->assertNull($restoredUser['deleted_at']);
}
```

## Troubleshooting

### Masalah: Data yang dihapus masih terlihat

**Solusi**: Pastikan model extend `BaseModel`, bukan `Model`

### Masalah: Tidak bisa restore data

**Solusi**: Gunakan method `restore()` atau update langsung deleted_at ke NULL

### Masalah: Join query tidak bekerja dengan soft delete

**Solusi**: Tambahkan WHERE clause ke related table jika diperlukan

```php
$userModel = new UserModel();
$users = $userModel
    ->select('users.*, roles.nama_role')
    ->join('roles', 'roles.id = users.role_id')
    ->where('roles.deleted_at IS NULL') // Tambahkan ini jika roles juga soft delete
    ->findAll();
```

## Migration Template

Jika Anda membuat table baru dengan soft delete:

```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'username'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'    => ['type' => 'VARCHAR', 'constraint' => 255],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', false, true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
```

---

**Created**: February 4, 2026  
**Version**: 1.0  
**Last Updated**: February 4, 2026
