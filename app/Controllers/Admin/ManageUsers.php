<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;

class ManageUsers extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * List semua user (hanya yang aktif)
     */
    public function index()
    {
        $this->checkLogin();

        $data = [
            'title' => 'Kelola User',
            'activeUsers' => $this->userModel->findAll(),
            'deletedUsers' => $this->userModel->findOnlyDeleted(),
        ];

        return view('admin/users/index', $data);
    }

    /**
     * List user yang sudah dihapus
     */
    public function trash()
    {
        $this->checkLogin();

        $data = [
            'title' => 'User Terhapus',
            'deletedUsers' => $this->userModel->findOnlyDeleted(),
        ];

        return view('admin/users/trash', $data);
    }

    /**
     * Soft delete user
     */
    public function delete($id)
    {
        $this->checkLogin();

        if (!$this->userModel->find($id)) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $this->userModel->delete($id);

        return redirect()->back()->with('success', 'User berhasil dihapus');
    }

    /**
     * Restore user yang sudah di-soft delete
     */
    public function restore($id)
    {
        $this->checkLogin();

        // Cari user termasuk yang deleted
        $user = $this->userModel->findByIdWithDeleted($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $this->userModel->restore($id);

        return redirect()->back()->with('success', 'User berhasil dikembalikan');
    }

    /**
     * Permanent delete (hard delete)
     */
    public function destroy($id)
    {
        $this->checkLogin();

        // Cari user termasuk yang deleted
        $user = $this->userModel->findByIdWithDeleted($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $this->userModel->forceDelete($id);

        return redirect()->back()->with('success', 'User berhasil dihapus permanen dari database');
    }

    /**
     * Show user detail
     */
    public function show($id)
    {
        $this->checkLogin();

        $user = $this->userModel->find($id);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User tidak ditemukan');
        }

        $data = [
            'title' => 'Detail User',
            'user' => $user,
        ];

        return view('admin/users/show', $data);
    }
}
