<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Profil Saya</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success">
        <?= session('success') ?>
    </div>
<?php endif; ?>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger">
        <?= implode('<br>', session('errors')) ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/profile/update') ?>">

    <div class="mb-3">
        <label>Username</label>
        <input
            type="text"
            name="username"
            class="form-control"
            value="<?= old('username', $user['username']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label>Nama</label>
        <input
            type="text"
            name="nama"
            class="form-control"
            value="<?= old('nama', $user['nama']) ?>"
            required
        >
    </div>

    <div class="mb-3">
        <label>Password Baru <small>(kosongkan jika tidak diubah)</small></label>
        <input
            type="password"
            name="password"
            class="form-control"
        >
    </div>

    <button class="btn btn-primary">Update Profil</button>

</form>

<?= $this->endSection() ?>
