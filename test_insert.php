<?php

// Test insert tipe kendaraan
require 'vendor/autoload.php';

use App\Models\TipeKendaraanModel;

$model = new TipeKendaraanModel();

try {
    // Test insert
    $data = [
        'nama_tipe'  => 'Motor Test',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    
    echo "Attempting to insert: " . json_encode($data) . "\n";
    $result = $model->insert($data);
    echo "Insert result: " . ($result ? "SUCCESS (ID: $result)" : "FAILED") . "\n";
    
    // Get all
    $all = $model->findAll();
    echo "All records: " . json_encode($all) . "\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";
}
