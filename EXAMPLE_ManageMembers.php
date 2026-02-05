<?php

namespace App\Controllers\Admin;

use App\Models\MemberModel;

/**
 * CONTOH IMPLEMENTASI SOFT DELETE UNTUK MEMBER
 * Copy dan paste ke: app/Controllers/Admin/ManageMembers.php
 */
class ManageMembers extends BaseController
{
    protected $memberModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
    }

    /**
     * List semua member aktif
     */
    public function index()
    {
        $this->checkLogin();

        $data = [
            'title' => 'Kelola Member',
            'members' => $this->memberModel->findAll(),
            'totalDeleted' => count($this->memberModel->findOnlyDeleted()),
        ];

        return view('admin/members/index', $data);
    }

    /**
     * List member yang sudah dihapus
     */
    public function trash()
    {
        $this->checkLogin();

        $data = [
            'title' => 'Member Terhapus',
            'members' => $this->memberModel->findOnlyDeleted(),
        ];

        return view('admin/members/trash', $data);
    }

    /**
     * Soft delete member
     */
    public function delete($id)
    {
        $this->checkLogin();

        if (!$this->memberModel->find($id)) {
            return redirect()->back()->with('error', 'Member tidak ditemukan');
        }

        $this->memberModel->delete($id);
        return redirect()->back()->with('success', 'Member berhasil dihapus');
    }

    /**
     * Restore member
     */
    public function restore($id)
    {
        $this->checkLogin();

        $member = $this->memberModel->findByIdWithDeleted($id);
        if (!$member) {
            return redirect()->back()->with('error', 'Member tidak ditemukan');
        }

        $this->memberModel->restore($id);
        return redirect()->back()->with('success', 'Member berhasil dikembalikan');
    }

    /**
     * Permanent delete
     */
    public function destroy($id)
    {
        $this->checkLogin();

        $member = $this->memberModel->findByIdWithDeleted($id);
        if (!$member) {
            return redirect()->back()->with('error', 'Member tidak ditemukan');
        }

        $this->memberModel->forceDelete($id);
        return redirect()->back()->with('success', 'Member berhasil dihapus permanen');
    }
}
