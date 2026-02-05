<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<h3>Profil Saya</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i><?= session('success') ?>
    </div>
<?php endif; ?>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i><?= implode('<br>', session('errors')) ?>
    </div>
<?php endif; ?>

<?php if (isset($user) && $user) : ?>
    <form method="post" action="<?= base_url('petugas/profile/update') ?>">

        <div class="row">
            <div class="col-md-6">
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
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= $user['email'] ?>"
                        disabled
                    >
                </div>

                <div class="mb-3">
                    <label>Password Baru <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password minimal 6 karakter"
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Profil
                </button>
                <a href="<?= base_url('petugas/dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

    </form>
<?php else : ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i>Profil tidak ditemukan
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
