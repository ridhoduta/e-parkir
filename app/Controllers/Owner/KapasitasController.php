<?php

namespace App\Controllers\Owner;

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

        if (session()->get('role_id') != 1) {
            return redirect()->to('/login');
        }

        $areas = $this->areaModel->findAll();

        foreach ($areas as &$area) {
            $used = $this->transaksiModel
                         ->where('area_id', $area['id'])
                         ->where('status !=', 'selesai')
                         ->countAllResults();
            $area['used_slots'] = $used;
            $area['available_slots'] = isset($area['kapasitas']) ? (int)$area['kapasitas'] : 0;
        }

        return view('owner/kapasitas/index', compact('areas'));
    }
}
