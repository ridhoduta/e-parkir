<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col">
        <a href="<?= base_url('petugas/parkir') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<h3>Tiket Masuk Kendaraan</h3>

<div class="row">
    <div class="col-md-8">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fas fa-ticket-alt me-2"></i>Tiket Parkir
                </h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <?php 
                        $qrData = "Ticket: " . $transaksi['nomor_tiket'] . "\n" . 
                                  "Plate: " . $transaksi['plat_nomor'] . "\n" . 
                                  "Time: " . date('Y-m-d H:i', strtotime($transaksi['waktu_masuk']));
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
                    ?>
                    <img src="<?= $qrUrl ?>" alt="QR Code Ticket" class="img-thumbnail mb-3 shadow-sm">
                    <h2 class="text-success mt-2"><strong><?= $transaksi['nomor_tiket'] ?></strong></h2>
                </div>

                <table class="table">
                    <tr>
                        <td><strong>Plat Nomor</strong></td>
                        <td>: <strong><?= $transaksi['plat_nomor'] ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Area</strong></td>
                        <td>: <?= $area['nama_area'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Tipe Kendaraan</strong></td>
                        <td>: <?= $tipeKendaraan['nama_tipe'] ?></td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Masuk</strong></td>
                        <td>: <?= date('d-m-Y H:i:s', strtotime($transaksi['waktu_masuk'])) ?></td>
                    </tr>
                </table>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Simpan atau catat nomor tiket ini. Gunakan untuk proses kendaraan keluar.
                </div>

                <a href="<?= base_url('petugas/parkir/tiket-pdf/' . $transaksi['nomor_tiket']) ?>" target="_blank" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Cetak Tiket
                </a>
                <a href="<?= base_url('petugas/parkir') ?>" class="btn btn-secondary">
                    <i class="fas fa-check me-2"></i>Selesai
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
