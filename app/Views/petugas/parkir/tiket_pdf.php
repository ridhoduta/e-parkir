<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Parkir - <?= $transaksi['nomor_tiket'] ?></title>
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
            max-width: 350px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border: 1px solid #000;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .header h2 {
            font-size: 18px;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 12px;
        }
        .qr-section {
            text-align: center;
            margin: 15px 0;
        }
        .qr-section img {
            width: 120px;
            height: 120px;
            border: 1px solid #ddd;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }
        .info-row strong {
            font-weight: bold;
        }
        .separator {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 11px;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 13px;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .container {
                border: none;
                max-width: 100%;
                padding: 10px;
            }
            .no-print {
                display: none;
            }
        }
        @page {
            size: 80mm 150mm;
            margin: 0;
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
    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ Cetak Tiket</button>
        <button onclick="window.close()" class="btn" style="background-color: #666;">✖ Tutup</button>
    </div>

    <div class="container">
        <div class="header">
            <h2>TIKET PARKIR</h2>
            <p>E-Parkir Digital</p>
        </div>

        <div class="qr-section">
            <?php 
                $qrData = "Ticket: " . $transaksi['nomor_tiket'] . "\n" . 
                          "Plate: " . $transaksi['plat_nomor'] . "\n" . 
                          "Time: " . date('Y-m-d H:i', strtotime($transaksi['waktu_masuk']));
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
            ?>
            <img src="<?= $qrUrl ?>" alt="QR Code">
            <h3 style="margin-top: 10px;"><?= $transaksi['nomor_tiket'] ?></h3>
        </div>

        <div class="separator"></div>

        <div class="info-row">
            <span>Plat Nomor</span>
            <strong><?= $transaksi['plat_nomor'] ?></strong>
        </div>
        <div class="info-row">
            <span>Area</span>
            <span><?= $area['nama_area'] ?></span>
        </div>
        <div class="info-row">
            <span>Tipe</span>
            <span><?= $tipeKendaraan['nama_tipe'] ?></span>
        </div>
        <div class="info-row">
            <span>Masuk</span>
            <span><?= date('d/m/Y H:i', strtotime($transaksi['waktu_masuk'])) ?></span>
        </div>

        <div class="footer">
            <p>Simpan tiket ini untuk keluar</p>
            <p><?= date('d-m-Y H:i:s') ?></p>
        </div>
    </div>
</body>
</html>
