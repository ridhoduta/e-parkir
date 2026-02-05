<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Data Kendaraan</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>

<a href="<?= base_url('admin/kendaraan/create') ?>" class="btn btn-primary mb-3">
    Tambah Kendaraan
</a>

<table class="table table-bordered">
    <tr class="table-light">
        <th>No</th>
        <th>Plat Nomor</th>
        <th>Tipe</th>
        <th>Pemilik</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($kendaraans as $i => $k) : ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><span class="badge bg-dark"><?= esc($k['plat_nomor']) ?></span></td>
            <td><?= esc($k['nama_tipe']) ?></td>
            <td>
                <?php if ($k['member_nama']) : ?>
                    <strong><?= esc($k['member_nama']) ?></strong> <span class="badge bg-primary">Member</span>
                <?php else : ?>
                    <?= esc($k['pemilik']) ?>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= base_url('admin/kendaraan/edit/'.$k['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= base_url('admin/kendaraan/delete/'.$k['id']) ?>"
                   onclick="return confirm('Hapus data kendaraan ini?')"
                   class="btn btn-danger btn-sm">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?= $this->endSection() ?>
