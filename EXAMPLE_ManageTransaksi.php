<?php

namespace App\Controllers\Admin;

use App\Models\TransaksiParkirModel;

/**
 * CONTOH IMPLEMENTASI SOFT DELETE UNTUK TRANSAKSI PARKIR
 * Copy dan paste ke: app/Controllers/Admin/ManageTransaksi.php
 */
class ManageTransaksi extends BaseController
{
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiParkirModel();
    }

    /**
     * List semua transaksi aktif
     */
    public function index()
    {
        $this->checkLogin();

        $data = [
            'title' => 'Kelola Transaksi Parkir',
            'transaksi' => $this->transaksiModel->findAll(),
            'totalDeleted' => count($this->transaksiModel->findOnlyDeleted()),
        ];

        return view('admin/transaksi/index', $data);
    }

    /**
     * List transaksi yang sudah dihapus
     */
    public function trash()
    {
        $this->checkLogin();

        $data = [
            'title' => 'Transaksi Terhapus',
            'transaksi' => $this->transaksiModel->findOnlyDeleted(),
        ];

        return view('admin/transaksi/trash', $data);
    }

    /**
     * Detail transaksi
     */
    public function show($id)
    {
        $this->checkLogin();

        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Transaksi',
            'transaksi' => $transaksi,
        ];

        return view('admin/transaksi/show', $data);
    }

    /**
     * Soft delete transaksi (jika ada kesalahan input)
     */
    public function delete($id)
    {
        $this->checkLogin();

        if (!$this->transaksiModel->find($id)) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        $this->transaksiModel->delete($id);
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus');
    }

    /**
     * Restore transaksi
     */
    public function restore($id)
    {
        $this->checkLogin();

        $transaksi = $this->transaksiModel->findByIdWithDeleted($id);
        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        $this->transaksiModel->restore($id);
        return redirect()->back()->with('success', 'Transaksi berhasil dikembalikan');
    }

    /**
     * Permanent delete transaksi
     */
    public function destroy($id)
    {
        $this->checkLogin();

        $transaksi = $this->transaksiModel->findByIdWithDeleted($id);
        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan');
        }

        $this->transaksiModel->forceDelete($id);
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus permanen dari database');
    }

    /**
     * Get transaksi summary dengan soft delete info
     */
    public function summary()
    {
        $this->checkLogin();

        $totalTransaksi = count($this->transaksiModel->findAllWithDeleted());
        $activeTransaksi = count($this->transaksiModel->findAll());
        $deletedTransaksi = count($this->transaksiModel->findOnlyDeleted());

        $data = [
            'title' => 'Ringkasan Transaksi',
            'totalTransaksi' => $totalTransaksi,
            'activeTransaksi' => $activeTransaksi,
            'deletedTransaksi' => $deletedTransaksi,
        ];

        return view('admin/transaksi/summary', $data);
    }
}
