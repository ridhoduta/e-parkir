-- ============================================
-- SOFT DELETE SETUP SQL SCRIPT
-- ============================================
-- Jalankan script ini untuk menambahkan kolom deleted_at ke tabel-tabel yang ada

-- Tambahkan kolom deleted_at ke tabel users
ALTER TABLE users 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- Tambahkan kolom deleted_at ke tabel members
ALTER TABLE members 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- Tambahkan kolom deleted_at ke tabel transaksi_parkirs
ALTER TABLE transaksi_parkirs 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- Tambahkan kolom deleted_at ke tabel roles (opsional)
ALTER TABLE roles 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- Tambahkan kolom deleted_at ke tabel areas (opsional)
ALTER TABLE areas 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- Tambahkan kolom deleted_at ke tabel tipe_kendaraan (opsional)
ALTER TABLE tipe_kendaraan 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- Tambahkan kolom deleted_at ke tabel tarif_parkir (opsional)
ALTER TABLE tarif_parkir 
ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL AFTER updated_at;

-- ============================================
-- VERIFICATION QUERIES
-- ============================================

-- Cek struktur tabel users
DESCRIBE users;

-- Cek berapa banyak deleted_at column di database
SELECT 
    TABLE_NAME, 
    COLUMN_NAME, 
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE COLUMN_NAME = 'deleted_at'
AND TABLE_SCHEMA = DATABASE();

-- ============================================
-- SOFT DELETE QUERIES EXAMPLES
-- ============================================

-- 1. SELECT hanya active records (exclude soft delete)
SELECT * FROM users WHERE deleted_at IS NULL;

-- 2. SELECT hanya deleted records
SELECT * FROM users WHERE deleted_at IS NOT NULL;

-- 3. SELECT semua termasuk deleted
SELECT * FROM users;

-- 4. SELECT dengan join dan exclude soft delete
SELECT 
    u.id, u.username, u.email, 
    r.nama_role
FROM users u
JOIN roles r ON r.id = u.role_id
WHERE u.deleted_at IS NULL;

-- 5. SOFT DELETE (jangan gunakan DELETE, gunakan UPDATE)
UPDATE users SET deleted_at = NOW() WHERE id = 1;

-- 6. RESTORE (undo soft delete)
UPDATE users SET deleted_at = NULL WHERE id = 1;

-- 7. HARD DELETE (permanent delete dari database)
DELETE FROM users WHERE id = 1;

-- 8. Bulk soft delete
UPDATE users SET deleted_at = NOW() WHERE role_id = 3;

-- 9. Bulk restore
UPDATE users SET deleted_at = NULL WHERE role_id = 3 AND deleted_at IS NOT NULL;

-- 10. Count active records
SELECT COUNT(*) as total_active FROM users WHERE deleted_at IS NULL;

-- 11. Count deleted records
SELECT COUNT(*) as total_deleted FROM users WHERE deleted_at IS NOT NULL;

-- 12. Get deletion statistics
SELECT 
    DATE(deleted_at) as tanggal_hapus,
    COUNT(*) as jumlah_dihapus
FROM users
WHERE deleted_at IS NOT NULL
GROUP BY DATE(deleted_at)
ORDER BY tanggal_hapus DESC;
