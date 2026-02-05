<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TarifParkirModel;
use App\Models\TipeKendaraanModel;
use App\Models\TarifBertingkatModel;

class TarifParkirController extends BaseController
{
    protected $tarifModel;
    protected $tipeModel;
    protected $bertingkatModel;

    public function __construct()
    {
        $this->tarifModel = new TarifParkirModel();
        $this->tipeModel  = new TipeKendaraanModel();
        $this->bertingkatModel = new TarifBertingkatModel();
    }

    public function index()
    {
        $tarifs = $this->tarifModel
            ->select('tarif_parkirs.*, tipe_kendaraans.nama_tipe')
            ->join('tipe_kendaraans', 'tipe_kendaraans.id = tarif_parkirs.tipe_kendaraan_id')
            ->findAll();

        foreach ($tarifs as &$t) {
            $t['tiers'] = $this->bertingkatModel->where('tipe_kendaraan_id', $t['tipe_kendaraan_id'])->orderBy('jam_mulai', 'ASC')->findAll();
        }

        return view('admin/tarif_parkir/index', compact('tarifs'));
    }

    public function create()
    {
        $tipe_kendaraans = $this->tipeModel->findAll();
        return view('admin/tarif_parkir/create', compact('tipe_kendaraans'));
    }

    public function store()
    {
        $rules = [
            'tipe_kendaraan_id' => [
                'rules'  => 'required|is_unique[tarif_parkirs.tipe_kendaraan_id,]',
                'errors' => [
                    'required'  => 'Tipe kendaraan wajib dipilih',
                    'is_unique' => 'Tarif untuk tipe kendaraan ini sudah ada',
                ]
            ],
            'tarif' => [
                'rules'  => 'required|numeric',
                'errors' => [
                    'required' => 'Tarif wajib diisi',
                    'numeric'  => 'Tarif harus berupa angka',
                ]
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $tipe_kendaraan_id = $this->request->getPost('tipe_kendaraan_id');
        
        $this->tarifModel->insert([
            'tipe_kendaraan_id' => $tipe_kendaraan_id,
            'tarif'             => $this->request->getPost('tarif'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        // Simpan tarif bertingkat
        $jam_mulai = $this->request->getPost('jam_mulai');
        $jam_selesai = $this->request->getPost('jam_selesai');
        $tarif_tier = $this->request->getPost('tarif_tier');

        if ($jam_mulai) {
            foreach ($jam_mulai as $key => $val) {
                if (isset($tarif_tier[$key])) {
                    $this->bertingkatModel->insert([
                        'tipe_kendaraan_id' => $tipe_kendaraan_id,
                        'jam_mulai'         => $val,
                        'jam_selesai'       => $jam_selesai[$key] ?: null,
                        'tarif'             => $tarif_tier[$key],
                    ]);
                }
            }
        }

        return redirect()->to('/admin/tarif_parkir')
            ->with('success', 'Tarif parkir berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tarif = $this->tarifModel->find($id);
        $tipe_kendaraans = $this->tipeModel->findAll();
        $tiers = $this->bertingkatModel->where('tipe_kendaraan_id', $tarif['tipe_kendaraan_id'])->orderBy('jam_mulai', 'ASC')->findAll();

        return view('admin/tarif_parkir/edit', compact('tarif', 'tipe_kendaraans', 'tiers'));
    }

    public function update($id)
    {
        $rules = [
            'tipe_kendaraan_id' => [
                'rules'  => "required|is_unique[tarif_parkirs.tipe_kendaraan_id,id,{$id}]",
                'errors' => [
                    'required'  => 'Tipe kendaraan wajib dipilih',
                    'is_unique' => 'Tarif untuk tipe kendaraan ini sudah ada',
                ]
            ],
            'tarif' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $tipe_kendaraan_id = $this->request->getPost('tipe_kendaraan_id');

        $this->tarifModel->update($id, [
            'tipe_kendaraan_id' => $tipe_kendaraan_id,
            'tarif'             => $this->request->getPost('tarif'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        // Update tarif bertingkat: Hapus yang lama, simpan yang baru (sederhana)
        $this->bertingkatModel->where('tipe_kendaraan_id', $tipe_kendaraan_id)->delete();

        $jam_mulai = $this->request->getPost('jam_mulai');
        $jam_selesai = $this->request->getPost('jam_selesai');
        $tarif_tier = $this->request->getPost('tarif_tier');

        if ($jam_mulai) {
            foreach ($jam_mulai as $key => $val) {
                if (isset($tarif_tier[$key])) {
                    $this->bertingkatModel->insert([
                        'tipe_kendaraan_id' => $tipe_kendaraan_id,
                        'jam_mulai'         => $val,
                        'jam_selesai'       => $jam_selesai[$key] ?: null,
                        'tarif'             => $tarif_tier[$key],
                    ]);
                }
            }
        }

        return redirect()->to('/admin/tarif_parkir')
            ->with('success', 'Tarif parkir berhasil diperbarui');
    }

    public function delete($id)
    {
        $tarif = $this->tarifModel->find($id);
        if ($tarif) {
            $this->bertingkatModel->where('tipe_kendaraan_id', $tarif['tipe_kendaraan_id'])->delete();
            $this->tarifModel->delete($id);
        }
        return redirect()->to('/admin/tarif_parkir')
            ->with('success', 'Tarif parkir berhasil dihapus');
    }
}
