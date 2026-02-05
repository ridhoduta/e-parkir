<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TipeKendaraanModel;

class TipeKendaraanController extends BaseController
{
    protected $tipeModel;

    public function __construct()
    {
        $this->tipeModel = new TipeKendaraanModel();
    }

    public function index()
    {
        $data['tipe_kendaraans'] = $this->tipeModel->findAll();
        return view('admin/tipe_kendaraan/index', $data);
    }

    public function create()
    {
        return view('admin/tipe_kendaraan/create');
    }

    public function store()
    {
        if (!$this->validate([
            'nama_tipe' => 'required|is_unique[tipe_kendaraans.nama_tipe]',
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->tipeModel->insert([
            'nama_tipe'  => $this->request->getPost('nama_tipe'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/tipe_kendaraan')
            ->with('success', 'Tipe kendaraan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tipe = $this->tipeModel->find($id);
        return view('admin/tipe_kendaraan/edit', compact('tipe'));
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_tipe' => "required|is_unique[tipe_kendaraans.nama_tipe,id,{$id}]",
        ])) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->tipeModel->update($id, [
            'nama_tipe'  => $this->request->getPost('nama_tipe'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/tipe_kendaraan')
            ->with('success', 'Tipe kendaraan berhasil diperbarui');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        // Check dependencies
        $checkTables = [
            'kendaraans'         => 'Data Kendaraan',
            'tarif_parkirs'      => 'Tarif Parkir',
            'area_kapasitas'     => 'Kapasitas Area',
            'tarif_bertingkat'   => 'Tarif Bertingkat',
            'transaksi_parkirs'  => 'Transaksi Parkir'
        ];

        foreach ($checkTables as $table => $label) {
            $count = $db->table($table)->where('tipe_kendaraan_id', $id)->countAllResults();
            if ($count > 0) {
                return redirect()->back()->with('error', 
                    "Tidak dapat menghapus tipe kendaraan ini karena masih terhubung dengan data <strong>$label</strong>."
                );
            }
        }

        $this->tipeModel->delete($id); // soft delete
        return redirect()->to('/admin/tipe_kendaraan')
            ->with('success', 'Tipe kendaraan berhasil dihapus');
    }
}
