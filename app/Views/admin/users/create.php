<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>
<?php if (session('errors')) : ?>
    <div class="alert alert-danger">
        <?= implode('<br>', session('errors')) ?>
    </div>
<?php endif; ?>

<h3>Tambah User</h3>

<form method="post" action="<?= base_url('admin/users/store') ?>">
    <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
    <input type="text" name="nama" class="form-control mb-2" placeholder="Nama">
    <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

    <select name="role_id" class="form-control mb-2">
        <option value="1">Owner</option>
        <option value="2">Admin</option>
        <option value="3">Petugas</option>
    </select>
    
    <button class="btn btn-success">Simpan</button>
</form>

<?= $this->endSection() ?>
