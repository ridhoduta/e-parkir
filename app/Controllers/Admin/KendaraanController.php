<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KendaraanModel;
use App\Models\TipeKendaraanModel;

class KendaraanController extends BaseController
{
    protected $kendaraanModel;
    protected $tipeModel;

    public function __construct()
    {
        $this->kendaraanModel = new KendaraanModel();
        $this->tipeModel      = new TipeKendaraanModel();
    }

    public function index()
    {
        $kendaraans = $this->kendaraanModel
            ->select('kendaraans.*, tipe_kendaraans.nama_tipe, members.nama as member_nama')
            ->join('tipe_kendaraans', 'tipe_kendaraans.id = kendaraans.tipe_kendaraan_id')
            ->join('members', 'members.plat_nomor = kendaraans.plat_nomor', 'left')
            ->findAll();

        return view('admin/kendaraan/index', compact('kendaraans'));
    }

    public function create()
    {
        $tipe_kendaraans = $this->tipeModel->findAll();
        return view('admin/kendaraan/create', compact('tipe_kendaraans'));
    }

    public function store()
    {
        $rules = [
            'plat_nomor' => [
                'rules'  => 'required|is_unique[kendaraans.plat_nomor]',
                'errors' => [
                    'required'  => 'Plat nomor wajib diisi',
                    'is_unique' => 'Plat nomor sudah terdaftar',
                ]
            ],
            'tipe_kendaraan_id' => 'required',
            'pemilik'           => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->kendaraanModel->insert([
            'plat_nomor'         => strtoupper($this->request->getPost('plat_nomor')),
            'tipe_kendaraan_id'  => $this->request->getPost('tipe_kendaraan_id'),
            'pemilik'            => $this->request->getPost('pemilik'),
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ]);

        helper('log');
        \save_log('CREATE_KENDARAAN', 'Menambah kendaraan: ' . strtoupper($this->request->getPost('plat_nomor')));

        return redirect()->to('/admin/kendaraan')
            ->with('success', 'Data kendaraan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kendaraan = $this->kendaraanModel->find($id);
        $tipe_kendaraans = $this->tipeModel->findAll();

        return view('admin/kendaraan/edit', compact('kendaraan', 'tipe_kendaraans'));
    }

    public function update($id)
    {
        $rules = [
            'plat_nomor' => [
                'rules'  => "required|is_unique[kendaraans.plat_nomor,id,{$id}]",
                'errors' => [
                    'required'  => 'Plat nomor wajib diisi',
                    'is_unique' => 'Plat nomor sudah terdaftar',
                ]
            ],
            'tipe_kendaraan_id' => 'required',
            'pemilik'           => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->kendaraanModel->update($id, [
            'plat_nomor'         => strtoupper($this->request->getPost('plat_nomor')),
            'tipe_kendaraan_id'  => $this->request->getPost('tipe_kendaraan_id'),
            'pemilik'            => $this->request->getPost('pemilik'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ]);

        helper('log');
        \save_log('UPDATE_KENDARAAN', 'Memperbarui kendaraan ID: ' . $id);

        return redirect()->to('/admin/kendaraan')
            ->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function delete($id)
    {
        $kendaraan = $this->kendaraanModel->find($id);
        $this->kendaraanModel->delete($id); // soft delete

        helper('log');
        \save_log('DELETE_KENDARAAN', 'Menghapus kendaraan: ' . ($kendaraan['plat_nomor'] ?? $id));

        return redirect()->to('/admin/kendaraan')
            ->with('success', 'Data kendaraan berhasil dihapus');
    }
}
