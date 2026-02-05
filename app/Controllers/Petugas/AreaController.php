<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\AreaModel;

class AreaController extends BaseController
{
    protected $areaModel;

    public function __construct()
    {
        $this->areaModel = new AreaModel();
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
        return view('petugas/areas/index', compact('areas'));
    }

    public function show($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        $area = $this->areaModel->find($id);
        
        if (!$area) {
            return redirect()->to('petugas/areas')->with('error', 'Area tidak ditemukan');
        }

        return view('petugas/areas/show', compact('area'));
    }
}
