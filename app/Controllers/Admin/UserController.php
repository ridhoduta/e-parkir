<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    private function authAdmin()
    {
        if (!session('logged_in') || session('role_id') != 2) {
            return redirect()->to('/login')->send();
        }
    }

    public function index()
    {
        $this->authAdmin();

        $users = $this->userModel
            ->select('users.*, roles.nama_role')
            ->join('roles', 'roles.id = users.role_id')
            ->findAll();

        return view('admin/users/index', compact('users'));
    }

    public function create()
    {
        $this->authAdmin();
        return view('admin/users/create');
    }

    public function store()
{
    $this->authAdmin();

    $rules = [
        'username' => [
            'rules'  => 'required|is_unique[users.username]',
            'errors' => [
                'required'  => 'Username wajib diisi',
                'is_unique' => 'Username sudah digunakan', // ✅ PESAN
            ]
        ],
        'password' => [
            'rules' => 'required|min_length[6]',
            'errors' => [
                'required' => 'Password wajib diisi',
                'min_length' => 'Password minimal 6 karakter',
            ]
        ],
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $this->userModel->insert([
        'username' => $this->request->getPost('username'),
        'nama'     => $this->request->getPost('nama'),
        'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        'role_id'  => $this->request->getPost('role_id'),
    ]);

    helper('log');
    \save_log('CREATE_USER', 'Menambahkan user baru: ' . $this->request->getPost('username'));

    return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan');
}


    public function edit($id)
    {
        $this->authAdmin();

        $user = $this->userModel->find($id);
        return view('admin/users/edit', compact('user'));
    }

    public function update($id)
{
    $this->authAdmin();

    $rules = [
        'username' => [
            'rules'  => "required|is_unique[users.username,id,{$id}]",
            'errors' => [
                'required'  => 'Username wajib diisi',
                'is_unique' => 'Username sudah digunakan', // ✅ PESAN
            ]
        ],
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $data = [
        'username' => $this->request->getPost('username'),
        'nama'     => $this->request->getPost('nama'),
        'role_id'  => $this->request->getPost('role_id'),
    ];

    if ($this->request->getPost('password')) {
        $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    }

    $this->userModel->update($id, $data);

    helper('log');
    \save_log('UPDATE_USER', 'Memperbarui data user ID: ' . $id);

    return redirect()->to('/admin/users')->with('success', 'User berhasil diperbarui');
}


    

    public function delete($id)
    {
        $this->authAdmin();
        $user = $this->userModel->find($id);
        $this->userModel->delete($id);

        helper('log');
        \save_log('DELETE_USER', 'Menghapus user: ' . ($user['username'] ?? $id));

        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}
