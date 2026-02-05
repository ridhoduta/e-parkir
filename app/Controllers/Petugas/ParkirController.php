<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\TransaksiParkirModel;
use App\Models\AreaModel;
use App\Models\TipeKendaraanModel;
use App\Models\TarifParkirModel;
use App\Models\MemberModel;
use App\Models\KendaraanModel;
use App\Models\AreaKapasitasModel;

class ParkirController extends BaseController
{
    protected $transaksiModel;
    protected $areaModel;
    protected $tipeKendaraanModel;
    protected $tarifModel;
    protected $memberModel;
    protected $kendaraanModel;
    protected $areaKapasitasModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiParkirModel();
        $this->areaModel = new AreaModel();
        $this->tipeKendaraanModel = new TipeKendaraanModel();
        $this->tarifModel = new TarifParkirModel();
        $this->memberModel = new MemberModel();
        $this->kendaraanModel = new KendaraanModel();
        $this->areaKapasitasModel = new AreaKapasitasModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        $transaksi = $this->transaksiModel->findAll();
        
        // Enrich data dengan nama area dan tipe kendaraan
        foreach ($transaksi as &$t) {
            $area = $this->areaModel->find($t['area_id']);
            $t['area_nama'] = $area['nama_area'] ?? '-';
            
            $tipe = $this->tipeKendaraanModel->find($t['tipe_kendaraan_id']);
            $t['tipe_nama'] = $tipe['nama_tipe'] ?? '-';
        }
        
        return view('petugas/parkir/index', compact('transaksi'));
    }

    // KENDARAAN MASUK
    public function masuk()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        $areas = $this->areaModel->findAll();
        $tipeKendaraan = $this->tipeKendaraanModel->findAll();

        // kendaraan list from admin
        $kendaraans = $this->kendaraanModel->findAll();

        // build tipe mapping
        $tipeMap = [];
        foreach ($tipeKendaraan as $t) {
            $tipeMap[$t['id']] = $t['nama_tipe'];
        }

        // prepare vehicles with tipe name
        $vehicles = [];
        foreach ($kendaraans as $k) {
            $vehicles[] = [
                'plat_nomor' => $k['plat_nomor'],
                'tipe_kendaraan_id' => $k['tipe_kendaraan_id'],
                'tipe_nama' => $tipeMap[$k['tipe_kendaraan_id']] ?? '-',
            ];
        }

        // active members map for today
        $today = date('Y-m-d');
        $members = $this->memberModel->where('tanggal_mulai <=', $today)
                                      ->where('tanggal_akhir >=', $today)
                                      ->findAll();
        $membersByPlat = [];
        foreach ($members as $m) {
            $membersByPlat[strtoupper($m['plat_nomor'])] = $m;
        }

        // capacities from area_kapasitas
        $capacities = $this->areaKapasitasModel->findAll();

        return view('petugas/parkir/masuk', compact('areas', 'tipeKendaraan', 'vehicles', 'membersByPlat', 'capacities'));
    }

    public function storeMasuk()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        if (!$this->validate([
            'plat_nomor' => 'required',
            'area_id' => 'required|numeric',
            'tipe_kendaraan_id' => 'required|numeric',
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Check apakah kendaraan ini sudah parkir
        $platNomor = strtoupper($this->request->getPost('plat_nomor'));
        $aktivParkir = $this->transaksiModel
            ->where('plat_nomor', $platNomor)
            ->where('status', 'masuk')
            ->where('waktu_keluar IS NULL', null, false)
            ->first();

        if ($aktivParkir) {
            $area = $this->areaModel->find($aktivParkir['area_id']);
            $waktuMasuk = date('d-m-Y H:i:s', strtotime($aktivParkir['waktu_masuk']));
            return redirect()->back()->withInput()->with('error', 
                'Peringatan! Kendaraan dengan plat <strong>' . $platNomor . '</strong> masih parkir di <strong>' . $area['nama_area'] . '</strong> sejak ' . $waktuMasuk . '. Silakan lakukan proses keluar terlebih dahulu.'
            );
        }

        // Check apakah kendaraan sudah terdaftar
        $dataKendaraan = $this->kendaraanModel->where('plat_nomor', $platNomor)->first();

        if (!$dataKendaraan) {
            // Auto-register kendaraan baru
            $this->kendaraanModel->insert([
                'plat_nomor'        => $platNomor,
                'tipe_kendaraan_id' => $this->request->getPost('tipe_kendaraan_id'),
                'pemilik'           => 'Guest',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);
        }

        $areaId = $this->request->getPost('area_id');
        $tipeId = $this->request->getPost('tipe_kendaraan_id');

        $db = \Config\Database::connect();

        // Mulai transaksi: cek dan kurangi kapasitas secara atomic di area_kapasitas
        $db->transStart();

        // update atomic: hanya decrement jika kapasitas > 0 untuk tipe kendaraan tertentu di area tersebut
        $builder = $db->table('area_kapasitas');
        $builder->set('kapasitas', 'kapasitas - 1', false)
                ->where('area_id', $areaId)
                ->where('tipe_kendaraan_id', $tipeId)
                ->where('kapasitas >', 0)
                ->update();

        $affected = $db->affectedRows();
        if ($affected <= 0) {
            // tidak ada slot tersisa untuk tipe kendaraan ini di area ini
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Kapasitas area untuk tipe kendaraan ini sudah penuh');
        }

        $nomor_tiket = $this->transaksiModel->generateNomorTiket();
        $waktu_masuk = date('Y-m-d H:i:s');

        $insertData = [
            'nomor_tiket'       => $nomor_tiket,
            'plat_nomor'        => $platNomor,
            'area_id'           => $areaId,
            'tipe_kendaraan_id' => $this->request->getPost('tipe_kendaraan_id'),
            'waktu_masuk'       => $waktu_masuk,
            'status'            => 'masuk',
            'petugas_id'        => session()->get('user_id'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        $this->transaksiModel->insert($insertData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            // rollback otomatis jika gagal
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi. Coba lagi.');
        }

        helper('log');
        \save_log('PARKIR_MASUK', 'Kendaraan ' . $platNomor . ' masuk ke ' . $areaId);

        return redirect()->to('/petugas/parkir/tiket/' . $nomor_tiket)
            ->with('success', 'Kendaraan masuk berhasil dicatat');
    }

    // LIHAT TIKET MASUK
    public function tiket($nomor_tiket)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        $transaksi = $this->transaksiModel->where('nomor_tiket', $nomor_tiket)->first();

        if (!$transaksi) {
            return redirect()->to('/petugas/parkir')->with('error', 'Tiket tidak ditemukan');
        }

        $area = $this->areaModel->find($transaksi['area_id']);
        $tipeKendaraan = $this->tipeKendaraanModel->find($transaksi['tipe_kendaraan_id']);

        return view('petugas/parkir/tiket', compact('transaksi', 'area', 'tipeKendaraan'));
    }

    // KENDARAAN KELUAR
    public function keluar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        // list tiket aktif (belum selesai / belum memiliki waktu_keluar)
        $active = $this->transaksiModel
                       ->where('status !=', 'selesai')
                       ->where('waktu_keluar IS NULL', null, false)
                       ->findAll();

        // enrich with tipe nama
        $tipeKendaraan = $this->tipeKendaraanModel->findAll();
        $tipeMap = [];
        foreach ($tipeKendaraan as $t) {
            $tipeMap[$t['id']] = $t['nama_tipe'];
        }

        $tickets = [];
        foreach ($active as $a) {
            $tickets[] = [
                'id' => $a['id'],
                'nomor_tiket' => $a['nomor_tiket'],
                'plat_nomor' => $a['plat_nomor'],
                'tipe_nama' => $tipeMap[$a['tipe_kendaraan_id']] ?? '-',
                'waktu_masuk' => $a['waktu_masuk'],
            ];
        }

        return view('petugas/parkir/keluar', compact('tickets'));
    }

    public function prosesKeluar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        if (!$this->validate([
            'nomor_tiket' => 'required',
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $nomor = $this->request->getPost('nomor_tiket');
        $transaksi = $this->transaksiModel
                    ->where('nomor_tiket', $nomor)
                    ->where('status !=', 'selesai')
                    ->where('waktu_keluar IS NULL', null, false)
                    ->first();

        if (!$transaksi) {
            return redirect()->back()->withInput()
                ->with('error', 'Tiket tidak ditemukan atau sudah diproses');
        }

        // Hitung tarif
        $waktu_keluar = date('Y-m-d H:i:s');
        $tarif_info = $this->transaksiModel->hitungTarif(
            $transaksi['waktu_masuk'],
            $waktu_keluar,
            $transaksi['tipe_kendaraan_id']
        );

        // Periksa apakah plat terdaftar sebagai member yang aktif
        $plat = strtoupper($transaksi['plat_nomor']);
        $tanggal = date('Y-m-d', strtotime($waktu_keluar));
        $member = $this->memberModel->where('plat_nomor', $plat)
                                    ->where('tanggal_mulai <=', $tanggal)
                                    ->where('tanggal_akhir >=', $tanggal)
                                    ->first();

        $tarif_awal = $tarif_info['total_tarif'];
        $discount_percent = null;
        $discount_amount = null;
        $total_after_discount = $tarif_awal;

        if ($member) {
            $discount_percent = (float)$member['discount_percent'];
            $discount_amount = round($tarif_awal * $discount_percent / 100, 2);
            $total_after_discount = max(0, $tarif_awal - $discount_amount);
        }

        $this->transaksiModel->update($transaksi['id'], [
            'waktu_keluar' => $waktu_keluar,
            'durasi_menit' => $tarif_info['durasi_menit'],
            'tarif_awal' => $tarif_awal,
            'tarif' => $total_after_discount,
            'member_id' => $member ? $member['id'] : null,
            'discount_percent' => $discount_percent,
            'discount_amount' => $discount_amount,
            'status' => 'keluar',
        ]);

        helper('log');
        \save_log('PARKIR_KELUAR', 'Kendaraan ' . $plat . ' keluar, tarif: ' . $total_after_discount);

        return redirect()->to('/petugas/parkir/pembayaran/' . $transaksi['id'])
            ->with('success', 'Kendaraan keluar berhasil dicatat');
    }

    // PEMBAYARAN
    public function pembayaran($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        $transaksi = $this->transaksiModel->find($id);

        if (!$transaksi) {
            return redirect()->to('/petugas/parkir')->with('error', 'Transaksi tidak ditemukan');
        }

        $area = $this->areaModel->find($transaksi['area_id']);
        $tipeKendaraan = $this->tipeKendaraanModel->find($transaksi['tipe_kendaraan_id']);
        $member = null;
        if (!empty($transaksi['member_id'])) {
            $member = $this->memberModel->find($transaksi['member_id']);
        }

        return view('petugas/parkir/pembayaran', compact('transaksi', 'area', 'tipeKendaraan', 'member'));
    }

    public function prosesPembayaran($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        if (!$this->validate([
            'metode_pembayaran' => 'required|in_list[tunai,kartu,e-wallet]',
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();

        // gunakan transaksi untuk update status transaksi dan increment kapasitas area secara atomic
        $db->transStart();

        $this->transaksiModel->update($id, [
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'status' => 'selesai',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // tambahkan kembali 1 slot pada area_kapasitas terkait
        $transaksi = $this->transaksiModel->find($id);
        if ($transaksi && isset($transaksi['area_id']) && isset($transaksi['tipe_kendaraan_id'])) {
            $builder = $db->table('area_kapasitas');
            $builder->set('kapasitas', 'kapasitas + 1', false)
                    ->where('area_id', $transaksi['area_id'])
                    ->where('tipe_kendaraan_id', $transaksi['tipe_kendaraan_id'])
                    ->update();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran, coba lagi.');
        }

        helper('log');
        \save_log('PEMBAYARAN_PARKIR', 'Pembayaran transaksi ID: ' . $id . ' via ' . $this->request->getPost('metode_pembayaran'));

        return redirect()->to('/petugas/parkir/struk/' . $id)
            ->with('success', 'Pembayaran berhasil');
    }

    // CETAK STRUK
    public function struk($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        $transaksi = $this->transaksiModel->find($id);

        if (!$transaksi) {
            return redirect()->to('/petugas/parkir')->with('error', 'Transaksi tidak ditemukan');
        }

        $area = $this->areaModel->find($transaksi['area_id']);
        $tipeKendaraan = $this->tipeKendaraanModel->find($transaksi['tipe_kendaraan_id']);

        $member = null;
        if (!empty($transaksi['member_id'])) {
            $member = $this->memberModel->find($transaksi['member_id']);
        }

        return view('petugas/parkir/struk', compact('transaksi', 'area', 'tipeKendaraan', 'member'));
    }

    /**
     * API endpoint untuk check kendaraan yang sedang parkir
     */
    public function checkPlatNomor()
    {
        if (!session()->get('logged_in') || session()->get('role_id') != 3) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $plat_nomor = strtoupper($this->request->getGet('plat_nomor'));

        if (!$plat_nomor) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Plat nomor tidak boleh kosong']);
        }

        // Check apakah kendaraan ini sedang parkir
        $aktivParkir = $this->transaksiModel
            ->where('plat_nomor', $plat_nomor)
            ->where('status', 'masuk')
            ->where('waktu_keluar IS NULL', null, false)
            ->first();

        if ($aktivParkir) {
            $area = $this->areaModel->find($aktivParkir['area_id']);
            $waktuMasuk = date('d-m-Y H:i:s', strtotime($aktivParkir['waktu_masuk']));
            
            return $this->response->setJSON([
                'status' => 'warning',
                'message' => 'Kendaraan dengan plat ' . $plat_nomor . ' masih parkir!',
                'detail' => [
                    'plat_nomor' => $plat_nomor,
                    'area' => $area['nama_area'],
                    'waktu_masuk' => $waktuMasuk,
                    'nomor_tiket' => $aktivParkir['nomor_tiket'],
                ],
            ]);
        }

        return $this->response->setJSON(['status' => 'ok', 'message' => 'Plat nomor available']);
    }
}
