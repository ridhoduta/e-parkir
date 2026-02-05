<?= $this->extend('owner/layout/template') ?>
<?= $this->section('content') ?>

<h3>Kapasitas Parkir</h3>

<div class="row">
    <?php if (count($areas) > 0) : ?>
        <?php foreach ($areas as $area) : ?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-map-marker-alt me-2"></i><?= esc($area['nama_area']) ?>
                        </h5>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                <span class="ps-2">Kapasitas: <?= $area['kapasitas'] ?> slot</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>Tidak ada data area parkir
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
