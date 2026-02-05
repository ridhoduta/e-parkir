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
                <p class="text-muted small">Unggah file SQL untuk memulihkan basis data. <br><strong>Peringatan:</strong> Ini akan menimpa data yang ada!</p>
                <form action="#" method="POST" enctype="multipart/form-data">
                    <div class="input-group mb-3">
                        <input type="file" class="form-control" disabled>
                        <button class="btn btn-warning" type="submit" disabled>Restore</button>
                    </div>
                    <span class="badge bg-secondary">Fitur Restore membutuhkan akses root MySQL (Coming Soon)</span>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
