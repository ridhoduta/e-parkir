<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Daftar Member</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>

<a href="<?= base_url('admin/members/create') ?>" class="btn btn-primary mb-3">Tambah Member</a>

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Plat Nomor</th>
        <th>Tipe</th>
        <th>Periode</th>
        <th>Diskon (%)</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($members as $i => $m) : ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($m['nama']) ?></td>
            <td><?= esc($m['plat_nomor']) ?></td>
            <td><?= esc($m['tipe_member']) ?></td>
            <td><?= $m['tanggal_mulai'] ?> - <?= $m['tanggal_akhir'] ?></td>
            <td><?= $m['discount_percent'] ?></td>
            <td>
                <a href="<?= base_url('admin/members/edit/'.$m['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?= base_url('admin/members/delete/'.$m['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus member?')">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?= $this->endSection() ?>
