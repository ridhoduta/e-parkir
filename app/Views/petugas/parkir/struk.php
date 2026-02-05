<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col">
        <a href="<?= base_url('petugas/parkir') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print me-2"></i>Cetak Struk
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-5" style="border: 2px dashed #333; background-color: #f9f9f9;">
                <div class="text-center">
                    <h3 class="mb-3">
                        <i class="fas fa-parking"></i><br>STRUK PARKIR
                    </h3>
                    <hr>
                </div>

                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Nomor Tiket</strong></td>
                        <td>: <?= $transaksi['nomor_tiket'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
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
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Masuk</strong></td>
                        <td>: <?= date('d-m-Y H:i', strtotime($transaksi['waktu_masuk'])) ?></td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Keluar</strong></td>
                        <td>: <?= date('d-m-Y H:i', strtotime($transaksi['waktu_keluar'])) ?></td>
                    </tr>
                    <?php if (!empty($member)) : ?>
                    <tr>
                        <td><strong>Member</strong></td>
                        <td>: <strong><?= esc($member['nama']) ?> (<?= esc($member['tipe_member']) ?>)</strong></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Durasi</strong></td>
                        <td>: <strong><?= $transaksi['durasi_menit'] ?> menit</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
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

                    <tr>
                        <td><strong>Tarif</strong></td>
                        <td>: <strong class="text-danger">Rp <?= number_format($transaksi['tarif'], 0, ',', '.') ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Metode Bayar</strong></td>
                        <td>: <?= ucfirst($transaksi['metode_pembayaran']) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><hr></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <small>Terima Kasih!</small><br>
                            <small><?= date('d-m-Y H:i:s') ?></small>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<style media="print">
    body {
        margin: 0;
        padding: 0;
    }
    .btn, .row:first-child {
        display: none !important;
    }
</style>

<?= $this->endSection() ?>
