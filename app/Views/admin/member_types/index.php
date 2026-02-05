<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Tipe Member</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>

<a href="<?= base_url('admin/member_types/create') ?>" class="btn btn-primary mb-3">Tambah Tipe</a>

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Diskon (%)</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($types as $i => $t) : ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($t['nama']) ?></td>
            <td><?= $t['discount_percent'] ?></td>
            <td>
                <a href="<?= base_url('admin/member_types/edit/'.$t['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?= base_url('admin/member_types/delete/'.$t['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus tipe?')">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?= $this->endSection() ?>
