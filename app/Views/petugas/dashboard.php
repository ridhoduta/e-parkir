<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<h1>Dashboard <?= session('nama_role') ?></h1>
<p>Selamat datang, <?= session('nama') ?></p>

<?= $this->endSection() ?>