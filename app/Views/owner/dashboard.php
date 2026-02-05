<?= $this->extend('owner/layout/template') ?>
<?= $this->section('content') ?>

<h1>Dashboard Owner</h1>
<p>Selamat datang, <?= session('nama') ?></p>

<?= $this->endSection() ?>
