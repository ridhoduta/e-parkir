<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\AreaModel;
use App\Models\TransaksiParkirModel;

class KapasitasController extends BaseController
{
    protected $areaModel;
    protected $transaksiModel;

    public function __construct()
    {
        $this->areaModel = new AreaModel();
        $this->transaksiModel = new TransaksiParkirModel();
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        $areas = $this->areaModel->findAll();

        // Hitung slot yang saat ini terpakai per area (hanya yang berstatus 'masuk')
        foreach ($areas as &$area) {
            $used = $this->transaksiModel
                         ->where('area_id', $area['id'])
                         ->where('status', 'masuk')
                         ->countAllResults();
            $area['used_slots'] = $used;
            // area[kapasitas] menyimpan slot available (yang sudah diupdate decrement saat masuk/increment saat keluar)
            $area['available_slots'] = isset($area['kapasitas']) ? (int)$area['kapasitas'] : 0;
        }

        return view('petugas/kapasitas/index', compact('areas'));
    }
}
