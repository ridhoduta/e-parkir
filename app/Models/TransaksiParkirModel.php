<?php

namespace App\Models;

use App\Models\TarifParkirModel;

class TransaksiParkirModel extends BaseModel
{
    protected $table = 'transaksi_parkirs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nomor_tiket',
        'plat_nomor',
        'area_id',
        'slot_number',
        'tipe_kendaraan_id',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_menit',
        'tarif',
        'tarif_awal',
        'member_id',
        'discount_percent',
        'discount_amount',
        'metode_pembayaran',
        'status',
        'petugas_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $useTimestamps = false;

    public function generateNomorTiket()
    {
        $date = date('Ymd');
        $lastTicket = $this->where('DATE(created_at)', date('Y-m-d'))
                          ->orderBy('id', 'DESC')
                          ->first();
        
        if ($lastTicket) {
            $number = (int)substr($lastTicket['nomor_tiket'], -4) + 1;
        } else {
            $number = 1;
        }
        
        return 'TKT-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function cariByPlat($platNomor)
    {
        return $this->where('plat_nomor', $platNomor)
                   ->where('status !=', 'selesai')
                   ->where('waktu_keluar IS NULL', null, false)
                   ->first();
    }

    public function hitungTarif($waktuMasuk, $waktuKeluar, $tipeKendaraanId)
    {
        $datetime1 = strtotime($waktuMasuk);
        $datetime2 = strtotime($waktuKeluar);
        $durasi_menit = round(($datetime2 - $datetime1) / 60);
        $jam = (int) ceil($durasi_menit / 60);
        if ($jam < 1) {
            $jam = 1; // minimal 1 jam
        }

        // 1. Cek Tarif Bertingkat
        $bertingkatModel = new \App\Models\TarifBertingkatModel();
        $tiers = $bertingkatModel->where('tipe_kendaraan_id', $tipeKendaraanId)
                                 ->orderBy('jam_mulai', 'DESC')
                                 ->findAll();

        $total_tarif = 0;
        $foundTier = false;

        if ($tiers) {
            foreach ($tiers as $tier) {
                // Cari tier yang cocok dengan jumlah jam
                // Jika jam_selesai null, artinya itu tier terakhir (misal 5 jam keatas)
                if ($jam >= $tier['jam_mulai']) {
                    if ($tier['jam_selesai'] === null || $jam <= $tier['jam_selesai']) {
                        $total_tarif = $tier['tarif'];
                        $foundTier = true;
                        break;
                    }
                }
            }
        }

        // 2. Fallback ke Tarif Flat jika tidak ada tier yang cocok
        if (!$foundTier) {
            $tarifModel = new \App\Models\TarifParkirModel();
            $tarif = $tarifModel->where('tipe_kendaraan_id', $tipeKendaraanId)->first();

            if ($tarif) {
                // Hitung jumlah jam (dibulatkan ke atas) dan kalikan tarif per jam.
                $total_tarif = $jam * $tarif['tarif'];
            }
        }

        return [
            'durasi_menit' => $durasi_menit,
            'total_tarif' => $total_tarif,
        ];
    }
}
