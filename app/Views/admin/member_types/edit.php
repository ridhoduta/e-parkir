<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Edit Tipe Member</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/member_types/update/'.$type['id']) ?>">
    <div class="mb-3">
        <label>Nama Tipe</label>
        <input type="text" name="nama" class="form-control" value="<?= old('nama', $type['nama']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Diskon (%)</label>
        <input type="number" step="0.01" name="discount_percent" class="form-control" value="<?= old('discount_percent', $type['discount_percent']) ?>" required>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="<?= base_url('admin/member_types') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection() ?>
