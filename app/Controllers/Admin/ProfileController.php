<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $user = $this->userModel->find(session('user_id'));

        return view('admin/profile/index', compact('user'));
    }

    public function update()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        $id = session('user_id');

        $rules = [
            'username' => [
                'rules'  => "required|is_unique[users.username,id,{$id},deleted_at,NULL]",
                'errors' => [
                    'required'  => 'Username wajib diisi',
                    'is_unique' => 'Username sudah digunakan',
                ]
            ],
            'nama' => 'required',
        ];

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'nama'     => $this->request->getPost('nama'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            );
        }

        $this->userModel->update($id, $data);

        // Update session agar navbar & dashboard langsung berubah
        session()->set([
            'username' => $data['username'],
            'nama'     => $data['nama'],
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
}
