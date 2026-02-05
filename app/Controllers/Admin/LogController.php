<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LogModel;

class LogController extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new LogModel();
    }

    public function index()
    {
        if (!session('logged_in') || session('role_id') != 2) {
            return redirect()->to('/login');
        }

        $logs = $this->logModel->select('log_aktivitass.*, users.nama as user_nama')
            ->join('users', 'users.id = log_aktivitass.user_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        $data = [
            'logs' => $logs,
            'pager' => $this->logModel->pager,
            'title' => 'Log Aktivitas'
        ];

        return view('admin/logs/index', $data);
    }
}
