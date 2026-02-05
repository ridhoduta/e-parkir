<?php

namespace App\Models;

use CodeIgniter\Model;

class AreaKapasitasModel extends Model
{
    protected $table            = 'area_kapasitas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'area_id',
        'tipe_kendaraan_id',
        'kapasitas'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get capacity details for an area joined with vehicle type name
     */
    public function getByArea($area_id)
    {
        return $this->select('area_kapasitas.*, tipe_kendaraans.nama_tipe')
            ->join('tipe_kendaraans', 'tipe_kendaraans.id = area_kapasitas.tipe_kendaraan_id')
            ->where('area_id', $area_id)
            ->findAll();
    }
}
