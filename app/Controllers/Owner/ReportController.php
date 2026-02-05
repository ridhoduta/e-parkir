<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\TransaksiParkirModel;
use App\Models\AreaModel;
use App\Models\TipeKendaraanModel;
use App\Models\KendaraanModel;

class ReportController extends BaseController
{
    protected $transaksiModel;
    protected $areaModel;
    protected $tipeKendaraanModel;
    protected $kendaraanModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiParkirModel();
        $this->areaModel = new AreaModel();
        $this->tipeKendaraanModel = new TipeKendaraanModel();
        $this->kendaraanModel = new KendaraanModel();
    }

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('role_id') != 1) {
            return redirect()->to('/login');
        }

        $tanggal_mulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-d');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');

        // Laporan Harian
        $laporanHarian = $this->getLaporanHarian();
        
        // Laporan Rentang Tanggal
        $laporanRentang = $this->getLaporanRentang($tanggal_mulai, $tanggal_akhir);
        
        // Laporan Occupancy
        $laporanOccupancy = $this->getLaporanOccupancy();

        return view('owner/laporan/index', compact(
            'laporanHarian',
            'laporanRentang',
            'laporanOccupancy',
            'tanggal_mulai',
            'tanggal_akhir'
        ));
    }

    /**
     * Laporan Transaksi Harian
     */
    private function getLaporanHarian()
    {
        $hari_ini = date('Y-m-d');
        
        $transaksi = $this->transaksiModel
            ->where("DATE(created_at)", $hari_ini)
            ->where('status', 'selesai')
            ->findAll();

        $total_transaksi = count($transaksi);
        $total_pendapatan = 0;
        $byTipe = [];
        $byMetode = [];

        foreach ($transaksi as $t) {
            $total_pendapatan += $t['tarif'] ?? 0;

            // Group by tipe kendaraan
            $tipe = $this->tipeKendaraanModel->find($t['tipe_kendaraan_id']);
            $tipe_nama = $tipe['nama_tipe'] ?? 'Unknown';
            
            if (!isset($byTipe[$tipe_nama])) {
                $byTipe[$tipe_nama] = ['count' => 0, 'revenue' => 0];
            }
            $byTipe[$tipe_nama]['count']++;
            $byTipe[$tipe_nama]['revenue'] += $t['tarif'] ?? 0;

            // Group by metode pembayaran
            $metode = $t['metode_pembayaran'] ?? 'Tidak Diketahui';
            if (!isset($byMetode[$metode])) {
                $byMetode[$metode] = ['count' => 0, 'amount' => 0];
            }
            $byMetode[$metode]['count']++;
            $byMetode[$metode]['amount'] += $t['tarif'] ?? 0;
        }

        return [
            'total_transaksi' => $total_transaksi,
            'total_pendapatan' => $total_pendapatan,
            'by_tipe' => $byTipe,
            'by_metode' => $byMetode,
            'tanggal' => $hari_ini,
        ];
    }

    /**
     * Laporan Rentang Tanggal
     */
    private function getLaporanRentang($tanggal_mulai, $tanggal_akhir)
    {
        $transaksi = $this->transaksiModel
            ->where("DATE(created_at) >=", $tanggal_mulai)
            ->where("DATE(created_at) <=", $tanggal_akhir)
            ->where('status', 'selesai')
            ->findAll();

        $total_transaksi = count($transaksi);
        $total_pendapatan = 0;
        $byTipe = [];
        $byMetode = [];
        $byTanggal = [];

        foreach ($transaksi as $t) {
            $total_pendapatan += $t['tarif'] ?? 0;

            // Group by tipe kendaraan
            $tipe = $this->tipeKendaraanModel->find($t['tipe_kendaraan_id']);
            $tipe_nama = $tipe['nama_tipe'] ?? 'Unknown';
            
            if (!isset($byTipe[$tipe_nama])) {
                $byTipe[$tipe_nama] = ['count' => 0, 'revenue' => 0];
            }
            $byTipe[$tipe_nama]['count']++;
            $byTipe[$tipe_nama]['revenue'] += $t['tarif'] ?? 0;

            // Group by metode pembayaran
            $metode = $t['metode_pembayaran'] ?? 'Tidak Diketahui';
            if (!isset($byMetode[$metode])) {
                $byMetode[$metode] = ['count' => 0, 'amount' => 0];
            }
            $byMetode[$metode]['count']++;
            $byMetode[$metode]['amount'] += $t['tarif'] ?? 0;

            // Group by tanggal
            $tgl = date('Y-m-d', strtotime($t['created_at']));
            if (!isset($byTanggal[$tgl])) {
                $byTanggal[$tgl] = ['count' => 0, 'revenue' => 0];
            }
            $byTanggal[$tgl]['count']++;
            $byTanggal[$tgl]['revenue'] += $t['tarif'] ?? 0;
        }

        ksort($byTanggal); // Sort by tanggal

        return [
            'total_transaksi' => $total_transaksi,
            'total_pendapatan' => $total_pendapatan,
            'by_tipe' => $byTipe,
            'by_metode' => $byMetode,
            'by_tanggal' => $byTanggal,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
        ];
    }

    /**
     * Laporan Occupancy
     */
    private function getLaporanOccupancy()
    {
        $areas = $this->areaModel->findAll();
        $tipeKendaraan = $this->tipeKendaraanModel->findAll();

        $occupancyAreas = [];
        $total_kapasitas = 0;
        $total_dipakai = 0;

        foreach ($areas as $area) {
            // Hitung berapa banyak kendaraan yang sedang parkir di area ini
            $parkingNow = $this->transaksiModel
                ->where('area_id', $area['id'])
                ->where('status', 'masuk')
                ->where('waktu_keluar IS NULL', null, false)
                ->countAllResults();

            $kapasitas_awal = $area['kapasitas']; // kapasitas saat ini (sisa)
            // Kapasitas awal = total kapasitas (ambil dari migration/config)
            // Untuk sementara, assume total_kapasitas = kapasitas_saat_ini + sedang_parkir
            $total_area_capacity = $kapasitas_awal + $parkingNow;
            
            $persentase = $total_area_capacity > 0 ? round(($parkingNow / $total_area_capacity) * 100, 2) : 0;

            $occupancyAreas[] = [
                'area_id' => $area['id'],
                'nama_area' => $area['nama_area'],
                'kapasitas_sisa' => $kapasitas_awal,
                'kapasitas_total' => $total_area_capacity,
                'sedang_parkir' => $parkingNow,
                'persentase_penggunaan' => $persentase,
            ];

            $total_kapasitas += $total_area_capacity;
            $total_dipakai += $parkingNow;
        }

        $total_persentase = $total_kapasitas > 0 ? round(($total_dipakai / $total_kapasitas) * 100, 2) : 0;

        return [
            'by_area' => $occupancyAreas,
            'total_kapasitas' => $total_kapasitas,
            'total_dipakai' => $total_dipakai,
            'total_persentase' => $total_persentase,
        ];
    }

    /**
     * Export to PDF
     */
    public function exportPdf()
    {
        if (!session()->get('logged_in') || session()->get('role_id') != 1) {
            return redirect()->to('/login');
        }

        $tanggal_mulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-d');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $tipe_laporan = $this->request->getGet('tipe') ?? 'harian';

        $laporanHarian = $this->getLaporanHarian();
        $laporanRentang = $this->getLaporanRentang($tanggal_mulai, $tanggal_akhir);
        $laporanOccupancy = $this->getLaporanOccupancy();

        $data = [
            'laporanHarian' => $laporanHarian,
            'laporanRentang' => $laporanRentang,
            'laporanOccupancy' => $laporanOccupancy,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
            'tipe_laporan' => $tipe_laporan,
        ];

        $html = view('owner/laporan/pdf', $data);
        return $html;
    }

    /**
     * Export to Excel
     */
    public function exportExcel()
    {
        if (!session()->get('logged_in') || session()->get('role_id') != 1) {
            return redirect()->to('/login');
        }

        $tanggal_mulai = $this->request->getGet('tanggal_mulai') ?? date('Y-m-d');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');

        $laporanHarian = $this->getLaporanHarian();
        $laporanRentang = $this->getLaporanRentang($tanggal_mulai, $tanggal_akhir);
        $laporanOccupancy = $this->getLaporanOccupancy();

        // Prepare CSV data
        $csv = "LAPORAN PARKIR\n";
        $csv .= "Periode: " . $tanggal_mulai . " s/d " . $tanggal_akhir . "\n";
        $csv .= "Tanggal Export: " . date('Y-m-d H:i:s') . "\n\n";

        // Laporan Harian
        $csv .= "LAPORAN TRANSAKSI HARIAN\n";
        $csv .= "Tanggal," . $laporanHarian['tanggal'] . "\n";
        $csv .= "Total Transaksi," . $laporanHarian['total_transaksi'] . "\n";
        $csv .= "Total Pendapatan,Rp " . number_format($laporanHarian['total_pendapatan'], 0, ',', '.') . "\n\n";

        $csv .= "Breakdown Per Tipe Kendaraan\n";
        $csv .= "Tipe,Jumlah,Pendapatan\n";
        foreach ($laporanHarian['by_tipe'] as $tipe => $data) {
            $csv .= $tipe . "," . $data['count'] . ",Rp " . number_format($data['revenue'], 0, ',', '.') . "\n";
        }

        $csv .= "\nBreakdown Per Metode Pembayaran\n";
        $csv .= "Metode,Jumlah,Jumlah Uang\n";
        foreach ($laporanHarian['by_metode'] as $metode => $data) {
            $csv .= $metode . "," . $data['count'] . ",Rp " . number_format($data['amount'], 0, ',', '.') . "\n";
        }

        // Set headers untuk download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Laporan_Parkir_' . date('Y-m-d') . '.csv"');
        echo $csv;
        exit;
    }
}
