<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\MemberTypeModel;
use App\Models\KendaraanModel;

class MemberController extends BaseController
{
    protected $memberModel;
    protected $typeModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->typeModel = new MemberTypeModel();
    }

    public function index()
    {
        $members = $this->memberModel->findAll();
        return view('admin/members/index', compact('members'));
    }

    public function create()
    {
        $types = $this->typeModel->findAll();
        
        // Hanya ambil kendaraan yang BELUM menjadi member
        $db = \Config\Database::connect();
        $subQuery = $db->table('members')->select('plat_nomor')->getCompiledSelect();
        
        $kendaraans = (new KendaraanModel())
            ->select('kendaraans.*, tipe_kendaraans.nama_tipe as tipe_nama')
            ->join('tipe_kendaraans', 'tipe_kendaraans.id = kendaraans.tipe_kendaraan_id')
            ->where("plat_nomor NOT IN ($subQuery)", null, false)
            ->orderBy('plat_nomor', 'ASC')
            ->findAll();
            
        return view('admin/members/create', compact('types', 'kendaraans'));
    }

    public function store()
    {
        $rules = [
            'nama' => 'required',
            'plat_nomor' => 'required',
            'tipe_member_id' => 'required|numeric',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_akhir' => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $type = $this->typeModel->find($this->request->getPost('tipe_member_id'));

        $this->memberModel->insert([
            'nama' => $this->request->getPost('nama'),
            'plat_nomor' => strtoupper($this->request->getPost('plat_nomor')),
            'tipe_member' => $type ? $type['nama'] : '',
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_akhir' => $this->request->getPost('tanggal_akhir'),
            'discount_percent' => $type ? $type['discount_percent'] : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/members')->with('success', 'Member berhasil ditambahkan');
    }

    public function edit($id)
    {
        $member = $this->memberModel->find($id);
        if (! $member) {
            return redirect()->to('/admin/members')->with('error', 'Member tidak ditemukan');
        }
        $types = $this->typeModel->findAll();
        $kendaraans = (new KendaraanModel())
            ->select('kendaraans.*, tipe_kendaraans.nama_tipe as tipe_nama')
            ->join('tipe_kendaraans', 'tipe_kendaraans.id = kendaraans.tipe_kendaraan_id')
            ->orderBy('plat_nomor', 'ASC')
            ->findAll();
        return view('admin/members/edit', compact('member','types', 'kendaraans'));
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required',
            'plat_nomor' => 'required',
            'tipe_member_id' => 'required|numeric',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_akhir' => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $type = $this->typeModel->find($this->request->getPost('tipe_member_id'));

        $this->memberModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'plat_nomor' => strtoupper($this->request->getPost('plat_nomor')),
            'tipe_member' => $type ? $type['nama'] : $this->request->getPost('tipe_member'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_akhir' => $this->request->getPost('tanggal_akhir'),
            'discount_percent' => $type ? $type['discount_percent'] : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/members')->with('success', 'Member berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->memberModel->delete($id);
        return redirect()->to('/admin/members')->with('success', 'Member dihapus');
    }
}
