<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Edit Area Parkir</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/areas/update/'.$area['id']) ?>" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-bold">Kode Area (Unique)</label>
                <input type="text" name="kode_area" value="<?= old('kode_area', $area['kode_area']) ?>" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Area</label>
                <input type="text" name="nama_area"
                       value="<?= old('nama_area', $area['nama_area']) ?>"
                       class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Lokasi / Detail</label>
                <input type="text" name="lokasi"
                       value="<?= old('lokasi', $area['lokasi']) ?>"
                       class="form-control">
            </div>
            <hr>
            <label class="form-label fw-bold mb-3">Rincian Kapasitas per Tipe Kendaraan</label>
            <?php foreach ($tipe_kendaraans as $tipe) : ?>
                <div class="input-group mb-2">
                    <span class="input-group-text" style="width: 120px;"><?= $tipe['nama_tipe'] ?></span>
                    <input type="number" name="kapasitas_tipe[<?= $tipe['id'] ?>]" 
                           value="<?= old('kapasitas_tipe.'.$tipe['id'], $current_kapasitas[$tipe['id']] ?? 0) ?>" 
                           class="form-control" placeholder="Jumlah Slot" min="0">
                </div>
            <?php endforeach; ?>
            <div class="form-text small text- Mocha">Total kapasitas: <span class="fw-bold"><?= $area['kapasitas'] ?> Slot</span></div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-bold">Foto Area</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <div class="form-text small mb-2">Pilih file baru untuk mengganti foto lama</div>
                
                <?php if ($area['foto']) : ?>
                    <div class="mt-2 text-center border p-2 rounded bg-white">
                        <label class="small text-muted d-block mb-2">Foto Saat Ini:</label>
                        <img src="<?= base_url('uploads/areas/' . $area['foto']) ?>" 
                             class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-sync-alt me-1"></i> Update Area</button>
        <a href="<?= base_url('admin/areas') ?>" class="btn btn-secondary">Kembali</a>
    </div>
</form>

<?= $this->endSection() ?>
