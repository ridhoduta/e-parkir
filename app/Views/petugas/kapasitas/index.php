<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<h3>Kapasitas Parkir</h3>
<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Area</th>
        <th>Slot Terpakai</th>
        <th>Slot Tersisa</th>
    </tr>
    <?php foreach ($areas as $i => $area) : ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td><?= esc($area['nama_area']) ?></td>
            <td><span class="badge bg-warning text-dark"><?= $area['used_slots'] ?? 0 ?></span></td>
            <td><span class="badge bg-info"><?= $area['available_slots'] ?? 0 ?> slot</span></td>
        </tr>
    <?php endforeach; ?>
</table>

<?= $this->endSection() ?>
