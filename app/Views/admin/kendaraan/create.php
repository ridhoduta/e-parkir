<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Tambah Kendaraan</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/kendaraan/store') ?>">
    <div class="mb-3">
        <label>Plat Nomor</label>
        <input type="text" name="plat_nomor" class="form-control" placeholder="B 1234 ABC" required>
    </div>

    <div class="mb-3">
        <label>Nama Pemilik</label>
        <input type="text" name="pemilik" class="form-control" placeholder="Nama Lengkap Pemilik" required>
    </div>

    <div class="mb-3">
        <label>Tipe Kendaraan</label>
        <select name="tipe_kendaraan_id" class="form-control" required>
            <option value="">-- Pilih --</option>
            <?php foreach ($tipe_kendaraans as $tipe) : ?>
                <option value="<?= $tipe['id'] ?>"><?= esc($tipe['nama_tipe']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('admin/kendaraan') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection() ?>
