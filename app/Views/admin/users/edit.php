<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Edit User</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger">
        <?= implode('<br>', session('errors')) ?>
    </div>
<?php endif; ?>


<form method="post" action="<?= base_url('admin/users/update/' . $user['id']) ?>">
    
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
        >
    </div>

    <div class="mb-3">
        <label>Password <small>(kosongkan jika tidak diubah)</small></label>
        <input 
            type="password" 
            name="password" 
            class="form-control"
        >
    </div>

    <div class="mb-3">
        <label>Role</label>
        <select name="role_id" class="form-control">
            <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Owner</option>
            <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Admin</option>
            <option value="3" <?= $user['role_id'] == 3 ? 'selected' : '' ?>>Petugas</option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Kembali</a>

</form>

<?= $this->endSection() ?>
