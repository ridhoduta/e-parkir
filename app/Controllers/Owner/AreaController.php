<?php

namespace App\Controllers\Owner;

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

        if (session()->get('role_id') != 1) {
            return redirect()->to('/login');
        }

        $areas = $this->areaModel->findAll();
        return view('owner/areas/index', compact('areas'));
    }

    public function show($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 1) {
            return redirect()->to('/login');
        }

        $area = $this->areaModel->find($id);
        
        if (!$area) {
            return redirect()->to('owner/areas')->with('error', 'Area tidak ditemukan');
        }

        return view('owner/areas/show', compact('area'));
    }
}
