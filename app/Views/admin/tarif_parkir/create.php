<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Tambah Tarif Parkir</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/tarif_parkir/store') ?>">
    <div class="mb-3">
        <label>Tipe Kendaraan</label>
        <select name="tipe_kendaraan_id" class="form-control" required>
            <option value="">-- Pilih --</option>
            <?php foreach ($tipe_kendaraans as $tipe) : ?>
                <option value="<?= $tipe['id'] ?>"><?= esc($tipe['nama_tipe']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Tarif Dasar (Flat)</label>
        <input type="number" name="tarif" class="form-control" value="0" required>
        <small class="text-muted">Gunakan 0 jika ingin menggunakan tarif bertingkat sepenuhnya, atau isi sebagai tarif default.</small>
    </div>

    <hr>
    <h5>Tarif Bertingkat (Opsional)</h5>
    <div id="tier-container">
        <div class="row mb-2 tier-row">
            <div class="col-md-3">
                <label class="small">Rentang Jam</label>
                <input type="number" name="jam_mulai[]" class="form-control" placeholder="0">
            </div>
            <div class="col-md-3">
                <label class="small">Sampai Jam</label>
                <input type="number" name="jam_selesai[]" class="form-control" placeholder="1 (Kosong = s/d Selesai)">
            </div>
            <div class="col-md-4">
                <label class="small">Tarif (Rp)</label>
                <input type="number" name="tarif_tier[]" class="form-control" placeholder="5000">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-tier">Hapus</button>
            </div>
        </div>
    </div>
    <button type="button" id="add-tier" class="btn btn-info btn-sm mb-3">Tambah Baris Waktu</button>

    <div class="mt-4">
        <button class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('admin/tarif_parkir') ?>" class="btn btn-secondary">Kembali</a>
    </div>
</form>

<script>
    document.getElementById('add-tier').addEventListener('click', function() {
        const container = document.getElementById('tier-container');
        const row = document.createElement('div');
        row.className = 'row mb-2 tier-row';
        row.innerHTML = `
            <div class="col-md-3">
                <input type="number" name="jam_mulai[]" class="form-control" placeholder="0">
            </div>
            <div class="col-md-3">
                <input type="number" name="jam_selesai[]" class="form-control" placeholder="1">
            </div>
            <div class="col-md-4">
                <input type="number" name="tarif_tier[]" class="form-control" placeholder="5000">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-tier">Hapus</button>
            </div>
        `;
        container.appendChild(row);
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-tier')) {
            e.target.closest('.tier-row').remove();
        }
    });
</script>

<?= $this->endSection() ?>
