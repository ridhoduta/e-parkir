<?php

if (!function_exists('save_log')) {
    /**
     * Simpan aktivitas user ke database
     * 
     * @param string $aktivitas Nama aktivitas (misal: LOGIN, DELETE_USER)
     * @param string $deskripsi Detail aktivitas
     * @return bool
     */
    function save_log($aktivitas, $deskripsi = '')
    {
        $logModel = new \App\Models\LogModel();
        
        $data = [
            'user_id'    => session('user_id'),
            'aktivitas'  => $aktivitas,
            'deskripsi'  => $deskripsi,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        return $logModel->insert($data);
    }
}
