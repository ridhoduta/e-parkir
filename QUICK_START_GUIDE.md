# SOFT DELETE QUICK START GUIDE

## 🚀 Implementasi Cepat (5 Menit)

### Step 1: Setup Database

Jalankan SQL queries di `SOFT_DELETE_SETUP.sql`:

```sql
ALTER TABLE users ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
ALTER TABLE members ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
ALTER TABLE transaksi_parkirs ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
```

### Step 2: Verify Models

Pastikan model Anda sudah di-update:

**UserModel.php**:
```php
class UserModel extends BaseModel // ✅ Extend BaseModel, bukan Model
{
    protected $table = 'users';
    // Soft delete otomatis di-handle oleh BaseModel
}
```

**MemberModel.php**:
```php
class MemberModel extends BaseModel // ✅ Extend BaseModel
{
    protected $table = 'members';
}
```

**TransaksiParkirModel.php**:
```php
class TransaksiParkirModel extends BaseModel // ✅ Extend BaseModel
{
    protected $table = 'transaksi_parkirs';
}
```

### Step 3: Update Auth Controller

Gunakan model untuk login check:

```php
public function attemptLogin()
{
    $userModel = new UserModel();
    
    // Otomatis exclude soft deleted user
    $user = $userModel->where('username', $username)->first();
    
    if (!$user) {
        return redirect()->back()->with('error', 'Username tidak ditemukan atau akun sudah dihapus');
    }
    
    // ... rest of code
}
```

### Step 4: Use Helper untuk Operations

```php
// Load helper
helper('SoftDelete');

// Soft delete
softDelete('users', 1);

// Restore
restoreSoftDelete('users', 1);

// Hard delete
forceDelete('users', 1);

// Check if deleted
if (isDeleted('users', 1)) {
    echo "User sudah dihapus";
}
```

---

## 📌 Usage Examples

### Login System (User tidak bisa login jika sudah dihapus)

```php
$userModel = new UserModel();

// Hanya cari user aktif (excluded soft delete)
$user = $userModel->where('username', 'john')->first();

if (!$user) {
    echo "User tidak ditemukan atau akun sudah dihapus";
}
```

### List Active Users

```php
$userModel = new UserModel();

// Otomatis exclude soft deleted
$activeUsers = $userModel->findAll();

// Atau dengan custom condition
$adminUsers = $userModel->where('role_id', 2)->findAll();
```

### List Deleted Users (untuk admin)

```php
$userModel = new UserModel();

// Hanya ambil yang sudah dihapus
$deletedUsers = $userModel->findOnlyDeleted();

// Atau dengan custom condition
$deletedAdmins = $userModel->onlyDeleted()
                           ->where('role_id', 2)
                           ->findAll();
```

### Soft Delete User

```php
$userModel = new UserModel();

// Soft delete (set deleted_at = now)
$userModel->delete(1);
```

### Restore User

```php
$userModel = new UserModel();

// Restore (set deleted_at = NULL)
$userModel->restore(1);
```

### Hard Delete User (Permanent)

```php
$userModel = new UserModel();

// Permanent delete dari database
$userModel->forceDelete(1);
```

### Member tidak bisa digunakan jika sudah dihapus

```php
$memberModel = new MemberModel();

// Hanya ambil member aktif untuk transaksi
$activeMember = $memberModel->find($memberId);

if (!$activeMember) {
    echo "Member tidak ditemukan atau sudah dihapus";
}
```

---

## 🎯 Real World Scenarios

### Scenario 1: User Dihapus, Tidak Bisa Login

```
1. Admin delete user dengan ID 5
   → $userModel->delete(5)
   → deleted_at = '2024-02-04 10:30:00'

2. User coba login
   → Query hanya cari WHERE deleted_at IS NULL
   → User ID 5 tidak ketemu
   → Login gagal dengan message "akun sudah dihapus"
```

### Scenario 2: Member Dihapus, Tidak Bisa Checkout

```
1. Admin delete member dengan ID 3
   → $memberModel->delete(3)

2. Customer dengan member ID 3 coba checkout
   → System cek $memberModel->find(3)
   → Tidak ketemu (soft deleted)
   → Tidak bisa apply member discount
```

### Scenario 3: Accidental Delete, Restore dengan Mudah

```
1. Admin delete user secara accidental
   → $userModel->delete(1)

2. Admin pergi ke trash page
   → Lihat list deleted users

3. Admin restore user
   → $userModel->restore(1)
   → User bisa login lagi
```

---

## 📊 Model Methods Reference

```php
$userModel = new UserModel();

// ===== READ =====
$userModel->find($id);                  // Get by ID (exclude soft delete)
$userModel->findAll();                  // Get all (exclude soft delete)
$userModel->findById($id);              // Alias untuk find()

$userModel->findByIdWithDeleted($id);   // Get by ID (include soft delete)
$userModel->findAllWithDeleted();       // Get all (include soft delete)
$userModel->withDeleted()->findAll();   // Get all (include soft delete)

$userModel->findOnlyDeleted();          // Get only deleted records
$userModel->onlyDeleted()->findAll();   // Get only deleted records

// ===== WRITE =====
$userModel->delete($id);                // Soft delete
$userModel->restore($id);               // Restore
$userModel->forceDelete($id);           // Hard delete

// ===== CUSTOM QUERY =====
$userModel->where('role_id', 2)
          ->findAll();                   // Exclude soft delete otomatis

$userModel->withDeleted()
          ->where('role_id', 2)
          ->findAll();                   // Include soft delete

$userModel->onlyDeleted()
          ->where('role_id', 2)
          ->findAll();                   // Only deleted dengan condition
```

---

## 🔧 Helper Functions Reference

```php
helper('SoftDelete');

// Soft delete single atau multiple
softDelete('users', 1);
softDelete('users', [1, 2, 3]);

// Restore single atau multiple
restoreSoftDelete('users', 1);
restoreSoftDelete('users', [1, 2, 3]);

// Hard delete single atau multiple
forceDelete('users', 1);
forceDelete('users', [1, 2, 3]);

// Check if deleted
isDeleted('users', 1);  // return true/false

// Get deleted records
$deleted = getDeletedRecords('users');

// Get all including deleted
$all = getAllRecords('users');
```

---

## ⚠️ Common Mistakes

### ❌ WRONG: Using raw query dengan join

```php
// INI SALAH - tidak exclude soft delete
$db = \Config\Database::connect();
$user = $db->table('users')
           ->join('roles', 'roles.id = users.role_id')
           ->where('username', 'john')
           ->first();
```

### ✅ RIGHT: Using model

```php
// INI BENAR - otomatis exclude soft delete
$userModel = new UserModel();
$user = $userModel->select('users.*, roles.nama_role')
                  ->join('roles', 'roles.id = users.role_id')
                  ->where('username', 'john')
                  ->first();
```

### ❌ WRONG: Forget to add deleted_at to allowedFields

```php
class UserModel extends BaseModel
{
    protected $allowedFields = [
        'username', 'email',
        // LUPA deleted_at ❌
    ];
}
```

### ✅ RIGHT: Include deleted_at

```php
class UserModel extends BaseModel
{
    protected $allowedFields = [
        'username', 'email',
        'deleted_at',  // ✅ Pastikan include
    ];
}
```

---

## 🧪 Testing Your Implementation

```php
// Test soft delete works
$userModel = new UserModel();

// Create test user
$id = $userModel->insert(['username' => 'testuser']);

// Soft delete
$userModel->delete($id);

// Verify it's not in active list
$active = $userModel->find($id);  // null ✓

// Verify it's in deleted list
$deleted = $userModel->findByIdWithDeleted($id);  // found ✓

// Verify deleted_at is set
echo $deleted['deleted_at'];  // '2024-02-04 10:30:00' ✓

// Restore
$userModel->restore($id);

// Verify restore works
$restored = $userModel->find($id);  // found ✓
echo $restored['deleted_at'];  // null ✓

// Hard delete
$userModel->forceDelete($id);

// Verify hard delete
$gone = $userModel->findByIdWithDeleted($id);  // null ✓
```

---

## 📚 Files Created

1. **BaseModel.php** - Base model dengan soft delete functionality
2. **SoftDeleteHelper.php** - Helper functions untuk soft delete
3. **Auth.php** - Updated auth controller dengan model usage
4. **ManageUsers.php** - Example admin controller untuk manage users
5. **SOFT_DELETE_DOCUMENTATION.md** - Complete documentation
6. **SOFT_DELETE_CHECKLIST.md** - Implementation checklist
7. **SOFT_DELETE_SETUP.sql** - SQL setup script
8. **QUICK_START_GUIDE.md** - This file

---

## 🎓 Next Steps

1. ✅ Run `SOFT_DELETE_SETUP.sql` untuk add kolom deleted_at
2. ✅ Verify models extend `BaseModel`
3. ✅ Update auth controller
4. ✅ Create admin controllers untuk manage deleted items
5. ✅ Create views untuk trash/deleted items
6. ✅ Update routes
7. ✅ Test soft delete functionality
8. ✅ Deploy to production

---

**Last Updated**: February 4, 2026  
**Version**: 1.0  
**Status**: Ready to Use
