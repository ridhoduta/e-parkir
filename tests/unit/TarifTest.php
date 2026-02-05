<?php

namespace App\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Models\TransaksiParkirModel;
use App\Models\TarifBertingkatModel;
use App\Models\TarifParkirModel;

class TarifTest extends CIUnitTestCase
{
    protected $transaksiModel;
    protected $bertingkatModel;
    protected $tarifModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->transaksiModel = new TransaksiParkirModel();
        $this->bertingkatModel = new TarifBertingkatModel();
        $this->tarifModel = new TarifParkirModel();
    }

    public function testHitungTarifBertingkat()
    {
        // Mock data for Tipe Kendaraan ID 99 (Motor Test)
        $tipeId = 99;

        // Cleanup
        $this->bertingkatModel->where('tipe_kendaraan_id', $tipeId)->delete(true);
        $this->tarifModel->where('tipe_kendaraan_id', $tipeId)->delete(true);

        // Add Tiers: 0-1h: 5000, 1-2h: 8000, 2+: 15000
        $this->bertingkatModel->insert(['tipe_kendaraan_id' => $tipeId, 'jam_mulai' => 0, 'jam_selesai' => 1, 'tarif' => 5000]);
        $this->bertingkatModel->insert(['tipe_kendaraan_id' => $tipeId, 'jam_mulai' => 1, 'jam_selesai' => 2, 'tarif' => 8000]);
        $this->bertingkatModel->insert(['tipe_kendaraan_id' => $tipeId, 'jam_mulai' => 2, 'jam_selesai' => null, 'tarif' => 15000]);

        // Scenario 1: 30 minutes (0.5h -> 1h)
        $result = $this->transaksiModel->hitungTarif('2026-02-05 10:00:00', '2026-02-05 10:30:00', $tipeId);
        $this->assertEquals(5000, $result['total_tarif']);

        // Scenario 2: 1.5 hours (2h)
        $result = $this->transaksiModel->hitungTarif('2026-02-05 10:00:00', '2026-02-05 11:30:00', $tipeId);
        $this->assertEquals(8000, $result['total_tarif']);

        // Scenario 3: 3 hours
        $result = $this->transaksiModel->hitungTarif('2026-02-05 10:00:00', '2026-02-05 13:00:00', $tipeId);
        $this->assertEquals(15000, $result['total_tarif']);

        // Scenario 4: 10 hours
        $result = $this->transaksiModel->hitungTarif('2026-02-05 10:00:00', '2026-02-05 20:00:00', $tipeId);
        $this->assertEquals(15000, $result['total_tarif']);

        // Cleanup
        $this->bertingkatModel->where('tipe_kendaraan_id', $tipeId)->delete(true);
    }

    public function testHitungTarifFallback()
    {
        $tipeId = 88; // Test Fallback
        $this->bertingkatModel->where('tipe_kendaraan_id', $tipeId)->delete(true);
        $this->tarifModel->where('tipe_kendaraan_id', $tipeId)->delete(true);

        // Add Flat Rate: 2000 per hour
        $this->tarifModel->insert(['tipe_kendaraan_id' => $tipeId, 'tarif' => 2000]);

        // Scenario: 3 hours
        $result = $this->transaksiModel->hitungTarif('2026-02-05 10:00:00', '2026-02-05 13:00:00', $tipeId);
        $this->assertEquals(6000, $result['total_tarif']);

        // Cleanup
        $this->tarifModel->where('tipe_kendaraan_id', $tipeId)->delete(true);
    }
}
