<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role_id') != 3) {
            return redirect()->to('/login');
        }

        return view('petugas/dashboard');
    }
}
