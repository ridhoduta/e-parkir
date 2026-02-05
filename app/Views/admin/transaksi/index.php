<?= $this->extend('admin/layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="fas fa-history me-2"></i> Riwayat Transaksi Parkir</h5>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form action="<?= base_url('admin/transaksi') ?>" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="<?= $tanggal_mulai ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="<?= $tanggal_akhir ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Plat Nomor</label>
                <input type="text" name="plat_nomor" class="form-control form-control-sm" placeholder="Cari Plat..." value="<?= $plat_nomor ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No Tiket</th>
                <th>Plat Nomor</th>
                <th>Area (Slot)</th>
                <th>Tipe</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Tarif</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transaksi as $t) : ?>
                <tr>
                    <td><small class="fw-bold"><?= $t['nomor_tiket'] ?></small></td>
                    <td><span class="badge bg-secondary"><?= $t['plat_nomor'] ?></span></td>
                    <td>
                        <?= $t['nama_area'] ?> 
                        <?php if($t['slot_number']): ?>
                            <span class="badge bg-info text-dark">Slot: <?= $t['slot_number'] ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $t['nama_tipe'] ?></td>
                    <td><small><?= date('d/m/y H:i', strtotime($t['waktu_masuk'])) ?></small></td>
                    <td>
                        <small>
                            <?= $t['waktu_keluar'] ? date('d/m/y H:i', strtotime($t['waktu_keluar'])) : '-' ?>
                        </small>
                    </td>
                    <td class="fw-bold text-success">
                        <?= $t['tarif'] ? 'Rp ' . number_format($t['tarif'], 0, ',', '.') : '-' ?>
                    </td>
                    <td>
                        <?php if ($t['status'] == 'selesai') : ?>
                            <span class="badge bg-success">Selesai</span>
                        <?php elseif ($t['status'] == 'keluar') : ?>
                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                        <?php else : ?>
                            <span class="badge bg-primary">Parkir</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/transaksi/delete/' . $t['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($transaksi)) : ?>
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">Data transaksi tidak ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
