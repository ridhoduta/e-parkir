<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Parkir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            background-color: #333;
            color: white;
            padding: 10px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .summary-box {
            display: inline-block;
            width: 48%;
            margin-right: 2%;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PARKIR</h1>
        <p>Periode: <?= $tanggal_mulai ?> s/d <?= $tanggal_akhir ?></p>
        <p>Tanggal Print: <?= date('d-m-Y H:i:s') ?></p>
    </div>

    <!-- Laporan Harian -->
    <div class="section">
        <div class="section-title">LAPORAN TRANSAKSI HARIAN</div>
        
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

        <h4>Breakdown Per Metode Pembayaran</h4>
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
    </div>

    <!-- Laporan Rentang Tanggal -->
    <div class="section">
        <div class="section-title">LAPORAN RENTANG TANGGAL</div>

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
    </div>

    <!-- Laporan Occupancy -->
    <div class="section">
        <div class="section-title">LAPORAN OCCUPANCY</div>

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
                        <td class="text-right"><?= $area['sedang_parkir'] ?></td>
                        <td class="text-right"><?= $area['kapasitas_sisa'] ?></td>
                        <td class="text-right"><?= $area['persentase_penggunaan'] ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Document ini di-generate secara otomatis oleh sistem parkir</p>
    </div>
</body>
</html>
