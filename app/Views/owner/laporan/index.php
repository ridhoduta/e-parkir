<?= $this->extend('owner/layout/template') ?>
<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col">
        <h2><i class="fas fa-chart-bar me-2"></i>Laporan Parkir</h2>
    </div>
    <div class="col text-end">
        <a href="<?= base_url('owner/report/export-pdf?tanggal_mulai=' . $tanggal_mulai . '&tanggal_akhir=' . $tanggal_akhir) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-print me-1"></i>Print PDF
        </a>
        <a href="<?= base_url('owner/report/export-excel?tanggal_mulai=' . $tanggal_mulai . '&tanggal_akhir=' . $tanggal_akhir) ?>" class="btn btn-sm btn-outline-success">
            <i class="fas fa-file-excel me-1"></i>Export Excel
        </a>
    </div>
</div>

<!-- Filter Tanggal -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Filter Laporan</h5>
        <form method="get" action="<?= base_url('owner/report') ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="<?= $tanggal_mulai ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control" value="<?= $tanggal_akhir ?>" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-1"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Laporan Transaksi Harian -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Laporan Transaksi Harian</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="p-3 border rounded bg-light">
                    <small class="text-muted">Total Transaksi Hari Ini</small>
                    <h3 class="mb-0"><?= $laporanHarian['total_transaksi'] ?> Transaksi</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded bg-light">
                    <small class="text-muted">Total Pendapatan Hari Ini</small>
                    <h3 class="mb-0">Rp <?= number_format($laporanHarian['total_pendapatan'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>

        <!-- Breakdown per Tipe Kendaraan -->
        <div class="mb-4">
            <h6><i class="fas fa-car me-2"></i>Breakdown Per Tipe Kendaraan</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tipe Kendaraan</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporanHarian['by_tipe'])) : ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($laporanHarian['by_tipe'] as $tipe => $data) : ?>
                                <tr>
                                    <td><?= $tipe ?></td>
                                    <td class="text-end"><?= $data['count'] ?></td>
                                    <td class="text-end">Rp <?= number_format($data['revenue'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Breakdown per Metode Pembayaran -->
        <div class="mb-4">
            <h6><i class="fas fa-credit-card me-2"></i>Breakdown Per Metode Pembayaran</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Metode Pembayaran</th>
                            <th class="text-end">Jumlah Transaksi</th>
                            <th class="text-end">Jumlah Uang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporanHarian['by_metode'])) : ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($laporanHarian['by_metode'] as $metode => $data) : ?>
                                <tr>
                                    <td><?= ucfirst($metode) ?></td>
                                    <td class="text-end"><?= $data['count'] ?></td>
                                    <td class="text-end">Rp <?= number_format($data['amount'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Laporan Rentang Tanggal -->
<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Laporan Rentang Tanggal</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-3">Periode: <strong><?= $tanggal_mulai ?></strong> s/d <strong><?= $tanggal_akhir ?></strong></p>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="p-3 border rounded bg-light">
                    <small class="text-muted">Total Transaksi</small>
                    <h3 class="mb-0"><?= $laporanRentang['total_transaksi'] ?></h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded bg-light">
                    <small class="text-muted">Total Pendapatan</small>
                    <h3 class="mb-0">Rp <?= number_format($laporanRentang['total_pendapatan'], 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>

        <!-- Trend per Tanggal -->
        <div class="mb-4">
            <h6><i class="fas fa-chart-line me-2"></i>Trend Per Tanggal</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th class="text-end">Jumlah Transaksi</th>
                            <th class="text-end">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporanRentang['by_tanggal'])) : ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($laporanRentang['by_tanggal'] as $tgl => $data) : ?>
                                <tr>
                                    <td><?= date('d-m-Y', strtotime($tgl)) ?></td>
                                    <td class="text-end"><?= $data['count'] ?></td>
                                    <td class="text-end">Rp <?= number_format($data['revenue'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Breakdown per Tipe Kendaraan -->
        <div class="mb-4">
            <h6><i class="fas fa-car me-2"></i>Breakdown Per Tipe Kendaraan</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tipe Kendaraan</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporanRentang['by_tipe'])) : ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($laporanRentang['by_tipe'] as $tipe => $data) : ?>
                                <tr>
                                    <td><?= $tipe ?></td>
                                    <td class="text-end"><?= $data['count'] ?></td>
                                    <td class="text-end">Rp <?= number_format($data['revenue'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Breakdown per Metode Pembayaran -->
        <div class="mb-4">
            <h6><i class="fas fa-credit-card me-2"></i>Breakdown Per Metode Pembayaran</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Metode Pembayaran</th>
                            <th class="text-end">Jumlah Transaksi</th>
                            <th class="text-end">Jumlah Uang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporanRentang['by_metode'])) : ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($laporanRentang['by_metode'] as $metode => $data) : ?>
                                <tr>
                                    <td><?= ucfirst($metode) ?></td>
                                    <td class="text-end"><?= $data['count'] ?></td>
                                    <td class="text-end">Rp <?= number_format($data['amount'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Laporan Occupancy -->
<div class="card mb-4">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0"><i class="fas fa-parking me-2"></i>Laporan Occupancy (Ketersediaan Slot)</h5>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="p-3 border rounded bg-light">
                    <small class="text-muted">Total Kapasitas</small>
                    <h3 class="mb-0"><?= $laporanOccupancy['total_kapasitas'] ?> Slot</h3>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 border rounded bg-light">
                    <small class="text-muted">Sedang Dipakai</small>
                    <h3 class="mb-0"><?= $laporanOccupancy['total_dipakai'] ?> Slot (<?= $laporanOccupancy['total_persentase'] ?>%)</h3>
                </div>
            </div>
        </div>

        <!-- Progress Bar Keseluruhan -->
        <div class="mb-4">
            <h6><i class="fas fa-chart-pie me-2"></i>Penggunaan Kapasitas Keseluruhan</h6>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar" role="progressbar" 
                     style="width: <?= $laporanOccupancy['total_persentase'] ?>%;" 
                     aria-valuenow="<?= $laporanOccupancy['total_dipakai'] ?>" 
                     aria-valuemin="0" 
                     aria-valuemax="<?= $laporanOccupancy['total_kapasitas'] ?>">
                    <?= $laporanOccupancy['total_persentase'] ?>%
                </div>
            </div>
        </div>

        <!-- Per Area -->
        <div class="mb-4">
            <h6><i class="fas fa-list me-2"></i>Occupancy Per Area</h6>
            <div class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Area</th>
                            <th class="text-end">Kapasitas Total</th>
                            <th class="text-end">Sedang Parkir</th>
                            <th class="text-end">Sisa Slot</th>
                            <th class="text-end">Persentase</th>
                            <th>Progress</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($laporanOccupancy['by_area'] as $area) : ?>
                            <tr>
                                <td><?= $area['nama_area'] ?></td>
                                <td class="text-end"><?= $area['kapasitas_total'] ?></td>
                                <td class="text-end"><strong><?= $area['sedang_parkir'] ?></strong></td>
                                <td class="text-end"><?= $area['kapasitas_sisa'] ?></td>
                                <td class="text-end"><?= $area['persentase_penggunaan'] ?>%</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar <?= $area['persentase_penggunaan'] >= 80 ? 'bg-danger' : ($area['persentase_penggunaan'] >= 50 ? 'bg-warning' : 'bg-success') ?>" 
                                             style="width: <?= $area['persentase_penggunaan'] ?>%;">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style media="print">
    .btn, form { display: none; }
    body { font-size: 11pt; }
    .card { page-break-inside: avoid; }
</style>

<?= $this->endSection() ?>
