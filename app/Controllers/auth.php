<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();

        // Cari user berdasarkan username (otomatis exclude soft delete)
        $user = $userModel->select('users.*, roles.nama_role')
                          ->join('roles', 'roles.id = users.role_id')
                          ->where('users.username', $username)
                          ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Username tidak ditemukan atau akun sudah dihapus');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah');
        }

        // SIMPAN KE SESSION
        session()->set([
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'nama'       => $user['nama'],
            'email'      => $user['email'],
            'role_id'    => $user['role_id'],
            'nama_role'  => $user['nama_role'],
            'logged_in'  => true,
        ]);

        // Redirect
        helper('log');
        \save_log('LOGIN', 'User ' . $user['username'] . ' berhasil login');

        if ($user['role_id'] == 1) {
            return redirect()->to('/owner/dashboard');
        } elseif ($user['role_id'] == 2) {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/petugas/dashboard');
        }
    }

    public function logout()
    {
        helper('log');
        \save_log('LOGOUT', 'User ' . session('username') . ' logout');
        
        session()->destroy();
        return redirect()->to('/login');
    }
}
