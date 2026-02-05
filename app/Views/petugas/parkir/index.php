<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<h3>Manajemen Parkir</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle me-2"></i><?= session('success') ?>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-3">
        <a href="<?= base_url('petugas/parkir/masuk') ?>" class="btn btn-primary btn-lg w-100">
            <i class="fas fa-arrow-right me-2"></i>Kendaraan Masuk
        </a>
    </div>
    <div class="col-md-3">
        <a href="<?= base_url('petugas/parkir/keluar') ?>" class="btn btn-warning btn-lg w-100">
            <i class="fas fa-arrow-left me-2"></i>Kendaraan Keluar
        </a>
    </div>
</div>

<h5 class="mt-4">Riwayat Transaksi</h5>

<table class="table table-bordered table-hover">
    <tr>
        <th>No</th>
        <th>Nomor Tiket</th>
        <th>Plat Nomor</th>
        <th>Area</th>
        <th>Waktu Masuk</th>
        <th>Waktu Keluar</th>
        <th>Durasi</th>
        <th>Tarif</th>
        <th>Status</th>
    </tr>
    <?php if (count($transaksi) > 0) : ?>
        <?php foreach ($transaksi as $i => $t) : ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $t['nomor_tiket'] ?></td>
                <td><strong><?= $t['plat_nomor'] ?></strong></td>
                <td><?= $t['area_nama'] ?></td>
                <td><?= date('d-m-Y H:i', strtotime($t['waktu_masuk'])) ?></td>
                <td><?= $t['waktu_keluar'] ? date('d-m-Y H:i', strtotime($t['waktu_keluar'])) : '-' ?></td>
                <td><?= $t['durasi_menit'] ? $t['durasi_menit'] . ' menit' : '-' ?></td>
                <td><?= $t['tarif'] ? 'Rp ' . number_format($t['tarif'], 0, ',', '.') : '-' ?></td>
                <td>
                    <?php
                        if ($t['status'] == 'masuk') {
                            echo '<span class="badge bg-info">Masuk</span>';
                        } elseif ($t['status'] == 'keluar') {
                            echo '<span class="badge bg-warning text-dark">Keluar</span>';
                        } elseif ($t['status'] == 'selesai') {
                            echo '<span class="badge bg-success">Selesai</span>';
                        }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="9" class="text-center text-muted">Tidak ada transaksi</td>
        </tr>
    <?php endif; ?>
</table>

<?= $this->endSection() ?>
