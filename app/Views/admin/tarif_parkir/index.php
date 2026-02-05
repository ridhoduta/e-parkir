<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Tarif Parkir</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>

<a href="<?= base_url('admin/tarif_parkir/create') ?>" class="btn btn-primary mb-3">
    Tambah Tarif
</a>

<table class="table table-bordered">
    <thead>
        <tr class="table-light">
            <th>No</th>
            <th>Tipe Kendaraan</th>
            <th>Tarif Dasar</th>
            <th>Tarif Bertingkat (Durasi)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tarifs as $i => $t) : ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><strong><?= esc($t['nama_tipe']) ?></strong></td>
                <td>Rp <?= number_format($t['tarif']) ?></td>
                <td>
                    <?php if (!empty($t['tiers'])) : ?>
                        <ul class="mb-0 ps-3 small">
                            <?php foreach ($t['tiers'] as $tier) : ?>
                                <li>
                                    <?= $tier['jam_mulai'] ?> - <?= $tier['jam_selesai'] ?: '∞' ?> jam: 
                                    <strong>Rp <?= number_format($tier['tarif']) ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <span class="text-muted italic small">Hanya tarif dasar</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= base_url('admin/tarif_parkir/edit/'.$t['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= base_url('admin/tarif_parkir/delete/'.$t['id']) ?>"
                    onclick="return confirm('Hapus tarif ini?')"
                    class="btn btn-danger btn-sm">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
