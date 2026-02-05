<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col">
        <a href="<?= base_url('petugas/parkir') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<h3>Verifikasi Pembayaran</h3>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>Detail Parkir
                </h4>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <?php 
                        $qrData = "Ticket: " . $transaksi['nomor_tiket'] . "\n" . 
                                  "Plate: " . $transaksi['plat_nomor'] . "\n" . 
                                  "Status: " . ucfirst($transaksi['status']);
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=" . urlencode($qrData);
                    ?>
                    <img src="<?= $qrUrl ?>" alt="QR Code Receipt" class="img-thumbnail shadow-sm">
                </div>
                <table class="table">
                    <tr>
                        <td><strong>Nomor Tiket</strong></td>
                        <td>: <?= $transaksi['nomor_tiket'] ?></td>
                    </tr>
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
                    <tr>
                        <td><strong>Waktu Keluar</strong></td>
                        <td>: <?= date('d-m-Y H:i:s', strtotime($transaksi['waktu_keluar'])) ?></td>
                    </tr>
                    <?php if (!empty($member)) : ?>
                    <tr>
                        <td><strong>Member</strong></td>
                        <td>: <strong><?= esc($member['nama']) ?> (<?= esc($member['tipe_member']) ?>)</strong></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Durasi Parkir</strong></td>
                        <td>: <strong><?= $transaksi['durasi_menit'] ?> menit</strong></td>
                    </tr>
                    <?php if (!empty($transaksi['tarif_awal'])) : ?>
                    <tr>
                        <td><strong>Tarif Awal</strong></td>
                        <td>: Rp <?= number_format($transaksi['tarif_awal'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if (!empty($transaksi['discount_percent'])) : ?>
                    <tr>
                        <td><strong>Diskon (<?= $transaksi['discount_percent'] ?>%)</strong></td>
                        <td>: - Rp <?= number_format($transaksi['discount_amount'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                    <?php endif; ?>

                    <tr class="table-warning">
                        <td><strong>Total Tarif</strong></td>
                        <td>: <strong class="text-danger">Rp <?= number_format($transaksi['tarif'], 0, ',', '.') ?></strong></td>
                    </tr>
                </table>

                <form method="post" action="<?= base_url('petugas/parkir/proses-pembayaran/' . $transaksi['id']) ?>" class="mt-4">
                    <div class="mb-3">
                        <label><strong>Metode Pembayaran</strong></label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="tunai" value="tunai" required>
                                <label class="form-check-label" for="tunai">
                                    <i class="fas fa-money-bill me-2"></i>Tunai
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="kartu" value="kartu">
                                <label class="form-check-label" for="kartu">
                                    <i class="fas fa-credit-card me-2"></i>Kartu
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="metode_pembayaran" id="ewallet" value="e-wallet">
                                <label class="form-check-label" for="ewallet">
                                    <i class="fas fa-mobile-alt me-2"></i>E-Wallet
                                </label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check me-2"></i>Konfirmasi Pembayaran & Cetak Struk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
