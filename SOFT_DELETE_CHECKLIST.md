# Soft Delete Implementation Checklist

Gunakan checklist ini untuk memastikan soft delete sudah diimplementasikan dengan benar di aplikasi Anda.

## ✅ Database Setup

- [ ] Tambahkan kolom `deleted_at` ke tabel `users`:
  ```sql
  ALTER TABLE users ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
  ```

- [ ] Tambahkan kolom `deleted_at` ke tabel `members`:
  ```sql
  ALTER TABLE members ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
  ```

- [ ] Tambahkan kolom `deleted_at` ke tabel `transaksi_parkirs`:
  ```sql
  ALTER TABLE transaksi_parkirs ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL;
  ```

- [ ] Tambahkan kolom `deleted_at` ke tabel lain jika diperlukan

## ✅ Model Implementation

- [ ] Create `BaseModel.php` yang extend Model dengan soft delete configuration
- [ ] Update `UserModel.php` to extend `BaseModel`
- [ ] Update `MemberModel.php` to extend `BaseModel`
- [ ] Update `TransaksiParkirModel.php` to extend `BaseModel`
- [ ] Add `deleted_at` ke `allowedFields` di semua model
- [ ] Pastikan tidak ada duplikasi `useSoftDeletes` dan `deletedField`

## ✅ Authentication

- [ ] Update `Auth::attemptLogin()` menggunakan `UserModel`
- [ ] Verify user tidak bisa login jika `deleted_at` tidak null
- [ ] Update error message untuk mention "akun sudah dihapus"

## ✅ Helper Functions

- [ ] Create `SoftDeleteHelper.php` dengan functions:
  - [ ] `softDelete()`
  - [ ] `restoreSoftDelete()`
  - [ ] `forceDelete()`
  - [ ] `isDeleted()`
  - [ ] `getDeletedRecords()`
  - [ ] `getAllRecords()`

- [ ] Load helper di `app/Config/Autoload.php` atau di controller yang membutuhkan:
  ```php
  helper('SoftDelete');
  ```

## ✅ Controllers

- [ ] Create or Update `Admin\ManageUsers.php` dengan methods:
  - [ ] `index()` - list active users
  - [ ] `trash()` - list deleted users
  - [ ] `delete()` - soft delete
  - [ ] `restore()` - restore dari soft delete
  - [ ] `destroy()` - hard delete

- [ ] Create similar controllers untuk `ManageMembers`, `ManageTransaksi` dll

## ✅ Views

- [ ] Create or Update views untuk list active items
- [ ] Create views untuk list deleted items (trash)
- [ ] Add restore button di trash view
- [ ] Add hard delete button di trash view dengan confirmation

## ✅ Routes

- [ ] Add routes untuk CRUD operations
- [ ] Add routes untuk trash/deleted items
- [ ] Add routes untuk restore
- [ ] Add routes untuk hard delete

Example:
```php
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('users', 'Admin\ManageUsers::index');
    $routes->get('users/trash', 'Admin\ManageUsers::trash');
    $routes->delete('users/delete/(:num)', 'Admin\ManageUsers::delete/$1');
    $routes->patch('users/restore/(:num)', 'Admin\ManageUsers::restore/$1');
    $routes->delete('users/destroy/(:num)', 'Admin\ManageUsers::destroy/$1');
});
```

## ✅ Testing

- [ ] Test soft delete berfungsi (data tidak hilang di DB)
- [ ] Test data yang soft delete tidak muncul di active list
- [ ] Test data yang soft delete tidak bisa login
- [ ] Test restore berfungsi
- [ ] Test hard delete berfungsi (data benar-benar hilang)
- [ ] Test query dengan join tetap exclude soft deleted

## ✅ Documentation

- [ ] Create `SOFT_DELETE_DOCUMENTATION.md`
- [ ] Document setiap model method
- [ ] Document usage examples
- [ ] Document best practices

## ✅ Advanced Features (Optional)

- [ ] Add audit log untuk setiap soft delete
- [ ] Add timestamp untuk `deleted_by` (siapa yang menghapus)
- [ ] Add soft delete for related records (cascade)
- [ ] Add bulk soft delete
- [ ] Add batch restore functionality

## 📋 Verification Checklist

Setelah semuanya di-setup, verify dengan:

1. **Login Test**
   - [ ] Login dengan user aktif - berhasil
   - [ ] Soft delete user - user tidak muncul di active list
   - [ ] Login dengan deleted user - gagal dengan message "akun sudah dihapus"
   - [ ] Restore user - user bisa login lagi

2. **Database Test**
   - [ ] Query tanpa model - `SELECT * FROM users WHERE deleted_at IS NULL`
   - [ ] Verify deleted_at terisi saat soft delete
   - [ ] Verify deleted_at NULL saat restore

3. **View Test**
   - [ ] Deleted items tidak muncul di normal list
   - [ ] Deleted items muncul di trash view
   - [ ] Restore button di trash berfungsi
   - [ ] Hard delete button dengan confirmation

4. **Model Test**
   ```php
   helper('SoftDelete');
   
   $userModel = new UserModel();
   
   // Test soft delete
   $userModel->delete(1);
   $activeUser = $userModel->find(1); // Should be null
   $deletedUser = $userModel->findByIdWithDeleted(1); // Should have deleted_at
   
   // Test restore
   $userModel->restore(1);
   $restoredUser = $userModel->find(1); // Should return user
   
   // Test hard delete
   $userModel->forceDelete(1);
   $user = $userModel->findByIdWithDeleted(1); // Should be null
   ```

## 🔧 Troubleshooting

### Issue: Soft deleted user masih bisa login
**Solution**: 
- Pastikan Auth controller menggunakan UserModel, bukan raw query
- Check `BaseModel` sudah di-extend
- Verify `useSoftDeletes = true` di BaseModel

### Issue: Restore tidak berfungsi
**Solution**:
- Pastikan use method `restore()` dari BaseModel
- Jangan gunakan `update()` langsung
- Check kolom `deleted_at` bisa accept NULL

### Issue: Join query tidak bekerja
**Solution**:
- Add WHERE clause untuk exclude soft delete di related table
- Atau gunakan `withDeleted()` jika ingin include

### Issue: Model tidak menemukan soft deleted record
**Solution**:
- Use `findByIdWithDeleted()` untuk include deleted
- Atau use `withDeleted()->find()`

---

**Estimasi Waktu Implementasi**: 2-3 jam  
**Kesulitan**: Medium  
**Last Updated**: February 4, 2026
