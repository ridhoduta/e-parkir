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
            background-color: #333;
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
            border: 2px solid #333;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .summary-box strong {
            color: #333;
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
            color: #333;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-top: 30px;
            text-transform: uppercase;
        }
        .no-print {
            margin-bottom: 20px;
            text-align: right;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
        }
        .btn-print {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
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
        <button onclick="window.print()" class="btn-print">Print Laporan</button>
    </div>

    <div class="header">
        <h1>LAPORAN PARKIR</h1>
        <p>Periode: <?= $tanggal_mulai ?> s/d <?= $tanggal_akhir ?></p>
        <p>Tanggal Print: <?= date('d-m-Y H:i:s') ?></p>
    </div>

    <!-- Section: Harian -->
    <h3>Laporan Transaksi Harian</h3>
    <div class="summary-box">
        <div><strong>Total Transaksi:</strong> <?= $laporanHarian['total_transaksi'] ?></div>
    </div>
    <div class="summary-box">
        <div><strong>Total Pendapatan:</strong> Rp <?= number_format($laporanHarian['total_pendapatan'], 0, ',', '.') ?></div>
    </div>

    <h4>Breakdown Per Tipe Kendaraan</h4>
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

    <!-- Section: Rentang Tanggal -->
    <div style="page-break-before: always;"></div>
    <h3>Laporan Rentang Tanggal</h3>
    <div class="summary-box">
        <div><strong>Total Transaksi:</strong> <?= $laporanRentang['total_transaksi'] ?></div>
    </div>
    <div class="summary-box">
        <div><strong>Total Pendapatan:</strong> Rp <?= number_format($laporanRentang['total_pendapatan'], 0, ',', '.') ?></div>
    </div>

    <h4>Trend Per Tanggal</h4>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-right">Jumlah Transaksi</th>
                <th class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($laporanRentang['by_tanggal'])) : ?>
                <tr><td colspan="3" class="text-center">Tidak ada data</td></tr>
            <?php else : ?>
                <?php foreach ($laporanRentang['by_tanggal'] as $tgl => $data) : ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($tgl)) ?></td>
                        <td class="text-right"><?= $data['count'] ?></td>
                        <td class="text-right">Rp <?= number_format($data['revenue'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Section: Occupancy -->
    <div style="page-break-before: always;"></div>
    <h3>Laporan Occupancy</h3>
    <div class="summary-box">
        <div><strong>Total Kapasitas:</strong> <?= $laporanOccupancy['total_kapasitas'] ?> Slot</div>
    </div>
    <div class="summary-box">
        <div><strong>Sedang Dipakai:</strong> <?= $laporanOccupancy['total_dipakai'] ?> Slot (<?= $laporanOccupancy['total_persentase'] ?>%)</div>
    </div>

    <h4>Occupancy Per Area</h4>
    <table>
        <thead>
            <tr>
                <th>Nama Area</th>
                <th class="text-right">Kapasitas Total</th>
                <th class="text-right">Sedang Parkir</th>
                <th class="text-right">Sisa Slot</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($laporanOccupancy['by_area'] as $area) : ?>
                <tr>
                    <td><?= $area['nama_area'] ?></td>
                    <td class="text-right"><?= $area['kapasitas_total'] ?></td>
                    <td class="text-right"><strong><?= $area['sedang_parkir'] ?></strong></td>
                    <td class="text-right"><?= $area['kapasitas_sisa'] ?></td>
                    <td class="text-right"><?= $area['persentase_penggunaan'] ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini di-generate secara otomatis oleh Sistem Parkir</p>
    </div>
</body>
</html>
