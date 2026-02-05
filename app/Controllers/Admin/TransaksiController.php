<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransaksiParkirModel;
use App\Models\AreaModel;
use App\Models\TipeKendaraanModel;

class TransaksiController extends BaseController
{
    protected $transaksiModel;
    protected $areaModel;
    protected $tipeKendaraanModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiParkirModel();
        $this->areaModel = new AreaModel();
        $this->tipeKendaraanModel = new TipeKendaraanModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role_id') != 2) {
            return redirect()->to('/login');
        }

        $tanggal_mulai = $this->request->getGet('tanggal_mulai');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        $plat_nomor = $this->request->getGet('plat_nomor');

        $query = $this->transaksiModel->select('transaksi_parkirs.*, areas.nama_area, tipe_kendaraans.nama_tipe, users.nama as petugas_nama')
            ->join('areas', 'areas.id = transaksi_parkirs.area_id', 'left')
            ->join('tipe_kendaraans', 'tipe_kendaraans.id = transaksi_parkirs.tipe_kendaraan_id', 'left')
            ->join('users', 'users.id = transaksi_parkirs.petugas_id', 'left');

        if ($tanggal_mulai) {
            $query->where('DATE(transaksi_parkirs.created_at) >=', $tanggal_mulai);
        }
        if ($tanggal_akhir) {
            $query->where('DATE(transaksi_parkirs.created_at) <=', $tanggal_akhir);
        }
        if ($plat_nomor) {
            $query->like('transaksi_parkirs.plat_nomor', $plat_nomor);
        }

        $transaksi = $query->orderBy('transaksi_parkirs.created_at', 'DESC')->findAll();

        $data = [
            'transaksi' => $transaksi,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
            'plat_nomor' => $plat_nomor,
            'title' => 'Riwayat Transaksi'
        ];

        return view('admin/transaksi/index', $data);
    }

    public function delete($id)
    {
        if (!session('logged_in') || session('role_id') != 2) {
            return redirect()->to('/login');
        }

        $this->transaksiModel->delete($id);
        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus (soft delete)');
    }
}
