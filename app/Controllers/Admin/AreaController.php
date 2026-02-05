<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AreaModel;
use App\Models\AreaKapasitasModel;
use App\Models\TipeKendaraanModel;

class AreaController extends BaseController
{
    protected $areaModel;
    protected $areaKapasitasModel;
    protected $tipeModel;

    public function __construct()
    {
        $this->areaModel          = new AreaModel();
        $this->areaKapasitasModel = new AreaKapasitasModel();
        $this->tipeModel          = new TipeKendaraanModel();
    }

    public function index()
    {
        $areas = $this->areaModel->findAll();
        foreach ($areas as $key => $area) {
            $areas[$key]['rincian_kapasitas'] = $this->areaKapasitasModel->getByArea($area['id']);
        }
        return view('admin/areas/index', compact('areas'));
    }

    public function create()
    {
        $tipe_kendaraans = $this->tipeModel->findAll();
        return view('admin/areas/create', compact('tipe_kendaraans'));
    }

    public function store()
    {
        if (!$this->validate([
            'kode_area' => 'required|is_unique[areas.kode_area]',
            'nama_area' => 'required|is_unique[areas.nama_area]',
            'foto'      => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Calculate total capacity from per-type inputs
        $kapasitas_per_tipe = $this->request->getPost('kapasitas_tipe') ?? [];
        $total_kapasitas = array_sum($kapasitas_per_tipe);

        $data = [
            'kode_area' => strtoupper($this->request->getPost('kode_area')),
            'nama_area' => $this->request->getPost('nama_area'),
            'lokasi'    => $this->request->getPost('lokasi'),
            'kapasitas' => $total_kapasitas,
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/areas', $newName);
            $data['foto'] = $newName;
        }

        $area_id = $this->areaModel->insert($data);

        // Save detailed capacity
        foreach ($kapasitas_per_tipe as $tipe_id => $kap) {
            if ($kap > 0) {
                $this->areaKapasitasModel->insert([
                    'area_id'           => $area_id,
                    'tipe_kendaraan_id' => $tipe_id,
                    'kapasitas'         => $kap
                ]);
            }
        }

        helper('log');
        \save_log('CREATE_AREA', 'Menambah area: ' . $data['nama_area'] . ' (' . $data['kode_area'] . ')');

        return redirect()->to('/admin/areas')
            ->with('success', 'Area parkir berhasil ditambahkan');
    }

    public function edit($id)
    {
        $area = $this->areaModel->find($id);
        $tipe_kendaraans = $this->tipeModel->findAll();
        
        $current_kapasitas = [];
        $kapasitas_data = $this->areaKapasitasModel->where('area_id', $id)->findAll();
        foreach ($kapasitas_data as $kd) {
            $current_kapasitas[$kd['tipe_kendaraan_id']] = $kd['kapasitas'];
        }

        return view('admin/areas/edit', compact('area', 'tipe_kendaraans', 'current_kapasitas'));
    }

    public function update($id)
    {
        if (!$this->validate([
            'kode_area' => "required|is_unique[areas.kode_area,id,{$id}]",
            'nama_area' => "required|is_unique[areas.nama_area,id,{$id}]",
            'foto'      => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Calculate total capacity
        $kapasitas_per_tipe = $this->request->getPost('kapasitas_tipe') ?? [];
        $total_kapasitas = array_sum($kapasitas_per_tipe);

        $data = [
            'kode_area' => strtoupper($this->request->getPost('kode_area')),
            'nama_area' => $this->request->getPost('nama_area'),
            'lokasi'    => $this->request->getPost('lokasi'),
            'kapasitas' => $total_kapasitas,
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/areas', $newName);
            $data['foto'] = $newName;
        }

        $this->areaModel->update($id, $data);

        // Update detailed capacity (delete and re-insert for simplicity or update existing)
        $this->areaKapasitasModel->where('area_id', $id)->delete();
        foreach ($kapasitas_per_tipe as $tipe_id => $kap) {
            if ($kap > 0) {
                $this->areaKapasitasModel->insert([
                    'area_id'           => $id,
                    'tipe_kendaraan_id' => $tipe_id,
                    'kapasitas'         => $kap
                ]);
            }
        }

        helper('log');
        \save_log('UPDATE_AREA', 'Memperbarui area ID: ' . $id);

        return redirect()->to('/admin/areas')
            ->with('success', 'Area parkir berhasil diperbarui');
    }

    public function delete($id)
    {
        $area = $this->areaModel->find($id);
        $this->areaModel->delete($id);

        helper('log');
        \save_log('DELETE_AREA', 'Menghapus area: ' . ($area['nama_area'] ?? $id));

        return redirect()->to('/admin/areas')
            ->with('success', 'Area parkir berhasil dihapus');
    }
}
