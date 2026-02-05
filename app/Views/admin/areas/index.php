<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Area Parkir</h3>

<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>

<a href="<?= base_url('admin/areas/create') ?>" class="btn btn-primary mb-3">
    Tambah Area
</a>

<table class="table table-hover table-bordered align-middle">
    <thead class="table-light">
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Kode</th>
            <th>Nama Area</th>
            <th>Lokasi</th>
            <th>Kapasitas Total</th>
            <th>Rincian Slot</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($areas as $i => $area) : ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td>
                    <?php if ($area['foto']) : ?>
                        <img src="<?= base_url('uploads/areas/' . $area['foto']) ?>" 
                             alt="<?= $area['nama_area'] ?>" 
                             class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                    <?php else : ?>
                        <div class="text-muted small">No Image</div>
                    <?php endif; ?>
                </td>
                <td><span class="badge bg- Mocha" style="background-color: #4b2e1e;"><?= esc($area['kode_area']) ?></span></td>
                <td class="fw-bold"><?= esc($area['nama_area']) ?></td>
                <td><?= esc($area['lokasi'] ?? '-') ?></td>
                <td><span class="badge bg- Mocha" style="background-color: #6f4e37;"><?= $area['kapasitas'] ?> Slot</span></td>
                <td>
                    <ul class="list-unstyled mb-0 small">
                        <?php foreach ($area['rincian_kapasitas'] as $r) : ?>
                            <li><i class="fas fa-check-circle me-1"></i> <?= $r['nama_tipe'] ?>: <?= $r['kapasitas'] ?></li>
                        <?php endforeach; ?>
                        <?php if (empty($area['rincian_kapasitas'])) : ?>
                            <li class="text-muted">No details</li>
                        <?php endif; ?>
                    </ul>
                </td>
                <td>
                    <a href="<?= base_url('admin/areas/edit/' . $area['id']) ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?= base_url('admin/areas/delete/' . $area['id']) ?>"
                        onclick="return confirm('Hapus area ini?')"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>