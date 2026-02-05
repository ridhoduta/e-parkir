<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberTypeModel;

class MemberTypeController extends BaseController
{
    protected $typeModel;

    public function __construct()
    {
        $this->typeModel = new MemberTypeModel();
    }

    public function index()
    {
        $types = $this->typeModel->findAll();
        return view('admin/member_types/index', compact('types'));
    }

    public function create()
    {
        return view('admin/member_types/create');
    }

    public function store()
    {
        $rules = [
            'nama' => 'required',
            'discount_percent' => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->typeModel->insert([
            'nama' => $this->request->getPost('nama'),
            'discount_percent' => $this->request->getPost('discount_percent'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/member_types')->with('success', 'Tipe member berhasil ditambahkan');
    }

    public function edit($id)
    {
        $type = $this->typeModel->find($id);
        if (! $type) return redirect()->to('/admin/member_types')->with('error', 'Tipe tidak ditemukan');
        return view('admin/member_types/edit', compact('type'));
    }

    public function update($id)
    {
        $rules = [
            'nama' => 'required',
            'discount_percent' => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->typeModel->update($id, [
            'nama' => $this->request->getPost('nama'),
            'discount_percent' => $this->request->getPost('discount_percent'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/member_types')->with('success', 'Tipe member diperbarui');
    }

    public function delete($id)
    {
        $this->typeModel->delete($id);
        return redirect()->to('/admin/member_types')->with('success', 'Tipe member dihapus');
    }
}
