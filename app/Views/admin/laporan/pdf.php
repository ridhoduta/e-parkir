<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Parkir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #6f4e37;
            color: white;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary-box {
            display: inline-block;
            width: 48%;
            margin-right: 2%;
            border: 2px solid #6f4e37;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f4ede4;
        }
        .summary-box strong {
            color: #6f4e37;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        h3 {
            color: #6f4e37;
            border-bottom: 2px solid #6f4e37;
            padding-bottom: 5px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PARKIR</h1>
        <p>Tanggal: <?= $laporanHarian['tanggal'] ?></p>
        <p>Tanggal Print: <?= date('d-m-Y H:i:s') ?></p>
    </div>

    <!-- Summary Boxes -->
    <div class="summary-box">
        <div><strong>Total Transaksi:</strong> <?= $laporanHarian['total_transaksi'] ?></div>
    </div>
    <div class="summary-box">
        <div><strong>Total Pendapatan:</strong> Rp <?= number_format($laporanHarian['total_pendapatan'], 0, ',', '.') ?></div>
    </div>

    <!-- Breakdown Per Tipe Kendaraan -->
    <h3>Breakdown Per Tipe Kendaraan</h3>
    <table>
        <thead>
            <tr>
                <th>Tipe Kendaraan</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($laporanHarian['by_tipe'])) : ?>
                <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
            <?php else : ?>
                <?php foreach ($laporanHarian['by_tipe'] as $tipe => $data) : ?>
                    <tr>
                        <td><?= $tipe ?></td>
                        <td class="text-right"><?= $data['count'] ?></td>
                        <td class="text-right">Rp <?= number_format($data['revenue'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Breakdown Per Metode Pembayaran -->
    <h3>Breakdown Per Metode Pembayaran</h3>
    <table>
        <thead>
            <tr>
                <th>Metode Pembayaran</th>
                <th class="text-right">Jumlah Transaksi</th>
                <th class="text-right">Jumlah Uang</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($laporanHarian['by_metode'])) : ?>
                <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
            <?php else : ?>
                <?php foreach ($laporanHarian['by_metode'] as $metode => $data) : ?>
                    <tr>
                        <td><?= ucfirst($metode) ?></td>
                        <td class="text-right"><?= $data['count'] ?></td>
                        <td class="text-right">Rp <?= number_format($data['amount'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini di-generate secara otomatis oleh Sistem Parkir</p>
    </div>
</body>
</html>
