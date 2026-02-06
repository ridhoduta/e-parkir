<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir - <?= $transaksi['nomor_tiket'] ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border: 2px dashed #333;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .header h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header .icon {
            font-size: 30px;
            margin-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted #ddd;
        }
        .info-row strong {
            font-weight: bold;
        }
        .separator {
            border-top: 2px solid #333;
            margin: 15px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .total-row .amount {
            color: #d9534f;
            font-size: 18px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #333;
            font-size: 12px;
        }
        .btn-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn:hover {
            opacity: 0.9;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                max-width: 100%;
                padding: 20px;
            }
            .btn-container {
                display: none;
            }
        }
        @page {
            size: A5;
            margin: 10mm;
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</head>
<body>
    <div class="btn-container">
        <button onclick="window.print()" class="btn">
            🖨️ Cetak Struk
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            ✖ Tutup
        </button>
    </div>

    <div class="container">
        <div class="header">
            <div class="icon">🅿️</div>
            <h2>STRUK PARKIR</h2>
            <p>Nomor Tiket: <?= $transaksi['nomor_tiket'] ?></p>
        </div>

        <div class="info-row">
            <span>Plat Nomor</span>
            <strong><?= strtoupper($transaksi['plat_nomor']) ?></strong>
        </div>

        <div class="info-row">
            <span>Area</span>
            <span><?= $area['nama_area'] ?></span>
        </div>

        <div class="info-row">
            <span>Tipe Kendaraan</span>
            <span><?= $tipeKendaraan['nama_tipe'] ?></span>
        </div>

        <div class="separator"></div>

        <div class="info-row">
            <span>Waktu Masuk</span>
            <span><?= date('d/m/Y H:i', strtotime($transaksi['waktu_masuk'])) ?></span>
        </div>

        <div class="info-row">
            <span>Waktu Keluar</span>
            <span><?= date('d/m/Y H:i', strtotime($transaksi['waktu_keluar'])) ?></span>
        </div>

        <div class="info-row">
            <span>Durasi</span>
            <strong><?= $transaksi['durasi_menit'] ?> menit</strong>
        </div>

        <?php if (!empty($member)) : ?>
        <div class="separator"></div>
        <div class="info-row">
            <span>Member</span>
            <strong><?= esc($member['nama']) ?> (<?= esc($member['tipe_member']) ?>)</strong>
        </div>
        <?php endif; ?>

        <div class="separator"></div>

        <?php if (!empty($transaksi['tarif_awal'])) : ?>
        <div class="info-row">
            <span>Tarif Awal</span>
            <span>Rp <?= number_format($transaksi['tarif_awal'], 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>

        <?php if (!empty($transaksi['discount_percent'])) : ?>
        <div class="info-row">
            <span>Diskon (<?= $transaksi['discount_percent'] ?>%)</span>
            <span>- Rp <?= number_format($transaksi['discount_amount'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <?php endif; ?>

        <div class="total-row">
            <span>TOTAL BAYAR</span>
            <span class="amount">Rp <?= number_format($transaksi['tarif'], 0, ',', '.') ?></span>
        </div>

        <div class="info-row">
            <span>Metode Bayar</span>
            <span><?= ucfirst($transaksi['metode_pembayaran']) ?></span>
        </div>

        <div class="footer">
            <p>Terima Kasih!</p>
            <p><?= date('d-m-Y H:i:s') ?></p>
        </div>
    </div>
</body>
</html>
