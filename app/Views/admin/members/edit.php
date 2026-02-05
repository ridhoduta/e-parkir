<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Edit Member</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/members/update/'.$member['id']) ?>">
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= old('nama', $member['nama']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Plat Nomor</label>
        <select name="plat_nomor" class="form-control" required disabled>
            <option value="">-- Pilih Kendaraan --</option>
            <?php foreach ($kendaraans as $k) : ?>
                <option value="<?= $k['plat_nomor'] ?>" <?= (old('plat_nomor') ?: $member['plat_nomor']) == $k['plat_nomor'] ? 'selected' : '' ?>>
                    <?= esc($k['plat_nomor']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Tipe Member</label>
        <select name="tipe_member_id" class="form-control" required>
            <option value="">-- Pilih --</option>
            <?php foreach ($types as $t) : ?>
                <option value="<?= $t['id'] ?>" <?= (old('tipe_member_id') ? old('tipe_member_id') == $t['id'] : ($member['tipe_member'] == $t['nama'])) ? 'selected' : '' ?>>
                    <?= esc($t['nama']) ?> (<?= $t['discount_percent'] ?>%)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" value="<?= old('tanggal_mulai', $member['tanggal_mulai']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="form-control" value="<?= old('tanggal_akhir', $member['tanggal_akhir']) ?>" required>
    </div>
    <!-- discount_percent akan diatur otomatis berdasarkan tipe -->

    <button class="btn btn-primary">Update</button>
    <a href="<?= base_url('admin/members') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection() ?>
