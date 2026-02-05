<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Tambah Tipe Kendaraan</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/tipe_kendaraan/store') ?>">
    <div class="mb-3">
        <label>Nama Tipe</label>
        <input type="text" name="nama_tipe" class="form-control" required>
    </div>

    <button class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('admin/tipe_kendaraan') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection() ?>
