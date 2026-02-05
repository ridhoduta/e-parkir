<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Tambah Area Parkir</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/areas/store') ?>" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-bold">Kode Area (Unique)</label>
                <input type="text" name="kode_area" class="form-control" placeholder="Contoh: BASE1" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Area</label>
                <input type="text" name="nama_area" class="form-control" placeholder="Contoh: Basement 1" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Lokasi / Detail</label>
                <input type="text" name="lokasi" class="form-control" placeholder="Misal: Lantai 1 Sayap Kanan">
            </div>
            <hr>
            <label class="form-label fw-bold mb-3">Rincian Kapasitas per Tipe Kendaraan</label>
            <?php foreach ($tipe_kendaraans as $tipe) : ?>
                <div class="input-group mb-2">
                    <span class="input-group-text" style="width: 120px;"><?= $tipe['nama_tipe'] ?></span>
                    <input type="number" name="kapasitas_tipe[<?= $tipe['id'] ?>]" class="form-control" placeholder="Jumlah Slot" min="0" value="0">
                </div>
            <?php endforeach; ?>
            <div class="form-text small text- Mocha">Total kapasitas akan dihitung otomatis dari rincian di atas.</div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-bold">Foto Area</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <div class="form-text small">Max 2MB (JPG, PNG)</div>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <button class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Area</button>
        <a href="<?= base_url('admin/areas') ?>" class="btn btn-secondary">Kembali</a>
    </div>
</form>

<?= $this->endSection() ?>
