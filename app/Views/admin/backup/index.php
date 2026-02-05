<?= $this->extend('admin/layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="fas fa-database me-2"></i> Backup & Restore Database</h5>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-download me-1"></i> Backup Database
            </div>
            <div class="card-body">
                <p class="text-muted small">Unduh salinan basis data saat ini dalam format SQL. Ini berguna untuk mencadangkan data secara berkala atau sebelum melakukan perubahan besar.</p>
                <a href="<?= base_url('admin/backup/download') ?>" class="btn btn-primary">
                    <i class="fas fa-file-export me-1"></i> Unduh SQL Backup
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-upload me-1"></i> Restore Database
            </div>
            <div class="card-body">
                <?php if (session('success')) : ?>
                    <div class="alert alert-success"><?= session('success') ?></div>
                <?php endif; ?>
                <?php if (session('error')) : ?>
                    <div class="alert alert-danger"><?= session('error') ?></div>
                <?php endif; ?>

                <p class="text-muted small">Unggah file SQL untuk memulihkan basis data. <br><strong>Peringatan:</strong> Ini akan menimpa data yang ada!</p>
                <form action="<?= base_url('admin/backup/restore') ?>" method="POST" enctype="multipart/form-data">
                    <div class="input-group mb-3">
                        <input type="file" name="backup_file" class="form-control" accept=".sql" required>
                        <button class="btn btn-warning" type="submit">Restore Data</button>
                    </div>
                    <span class="badge bg-danger">Aksi ini tidak dapat dibatalkan (Irreversible)</span>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
