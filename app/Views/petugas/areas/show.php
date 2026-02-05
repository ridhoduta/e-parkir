<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col">
        <a href="<?= base_url('petugas/areas') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Area
        </a>
    </div>
</div>

<?php if (isset($area) && $area) : ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i><?= esc($area['nama_area']) ?>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Informasi Area</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>ID Area</strong></td>
                                    <td>: <?= $area['id'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Area</strong></td>
                                    <td>: <?= esc($area['nama_area']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Kapasitas Total</strong></td>
                                    <td>: <span class="badge bg-info"><?= $area['kapasitas'] ?> slot</span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Timestamp</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Dibuat</strong></td>
                                    <td>: <?= $area['created_at'] ?? '-' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Diperbarui</strong></td>
                                    <td>: <?= $area['updated_at'] ?? '-' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Area ini memiliki kapasitas <strong><?= $area['kapasitas'] ?> slot parkir</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i>Area tidak ditemukan
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
