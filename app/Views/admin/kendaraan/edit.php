<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Edit Kendaraan</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/kendaraan/update/'.$kendaraan['id']) ?>">
    <div class="mb-3">
        <label>Plat Nomor</label>
        <input type="text"
               name="plat_nomor"
               value="<?= old('plat_nomor', $kendaraan['plat_nomor']) ?>"
               class="form-control"
               required>
    </div>

    <div class="mb-3">
        <label>Pemilik</label>
        <input type="text" name="pemilik" value="<?= esc($kendaraan['pemilik']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tipe Kendaraan</label>
        <select name="tipe_kendaraan_id" class="form-control" required>
            <?php foreach ($tipe_kendaraans as $tipe) : ?>
                <option value="<?= $tipe['id'] ?>"
                    <?= $tipe['id'] == $kendaraan['tipe_kendaraan_id'] ? 'selected' : '' ?>>
                    <?= esc($tipe['nama_tipe']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
    <a href="<?= base_url('admin/kendaraan') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection() ?>
