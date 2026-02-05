<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cek role owner
        if (session()->get('role_id') != 1) {
            return redirect()->to('/login');
        }

        return view('owner/dashboard');
    }
}
