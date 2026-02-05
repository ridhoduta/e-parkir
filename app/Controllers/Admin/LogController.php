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

        $logs = $this->logModel->findWithUser();

        $data = [
            'logs' => $logs,
            'title' => 'Log Aktivitas'
        ];

        return view('admin/logs/index', $data);
    }
}
