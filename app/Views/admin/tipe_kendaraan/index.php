<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Tipe Kendaraan</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>

<?php if (session('error')) : ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>

<a href="<?= base_url('admin/tipe_kendaraan/create') ?>" class="btn btn-primary mb-3">
    Tambah Tipe Kendaraan
</a>

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Nama Tipe</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($tipe_kendaraans as $i => $tipe) : ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($tipe['nama_tipe']) ?></td>
            <td>
                <a href="<?= base_url('admin/tipe_kendaraan/edit/'.$tipe['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= base_url('admin/tipe_kendaraan/delete/'.$tipe['id']) ?>"
                   onclick="return confirm('Hapus tipe kendaraan ini?')"
                   class="btn btn-danger btn-sm">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?= $this->endSection() ?>
