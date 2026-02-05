<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Tambah Member</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger"><?= implode('<br>', session('errors')) ?></div>
<?php endif; ?>

<form method="post" action="<?= base_url('admin/members/store') ?>">
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= old('nama') ?>" required>
    </div>
    <div class="mb-3 position-relative">
        <label>Plat Nomor</label>
        <input type="text" id="plat_input" name="plat_nomor" class="form-control" autocomplete="off" placeholder="Ketik plat nomor..." value="<?= old('plat_nomor') ?>" required>
        <div id="plat_suggestions" class="list-group position-absolute w-100 z-index-100" style="display:none; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
        <small class="text-muted">Pilih kendaraan yang sudah terdaftar di sistem.</small>
    </div>

    <script>
        const kendaraans = <?= json_encode($kendaraans) ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            const platInput = document.getElementById('plat_input');
            const suggestions = document.getElementById('plat_suggestions');

            platInput.addEventListener('input', function() {
                const value = this.value.toUpperCase().trim();
                
                if (!value || value.length < 1) {
                    suggestions.style.display = 'none';
                    return;
                }

                const matches = kendaraans.filter(k => 
                    k.plat_nomor.toUpperCase().includes(value)
                );

                if (matches.length > 0) {
                    let html = '';
                    matches.forEach(k => {
                        html += `<button type="button" class="list-group-item list-group-item-action" data-plat="${k.plat_nomor}">
                            <strong>${k.plat_nomor}</strong> <span class="badge bg-secondary ms-2">${k.tipe_nama}</span>
                            <div class="small text-muted">${k.pemilik}</div>
                        </button>`;
                    });
                    suggestions.innerHTML = html;
                    suggestions.style.display = 'block';

                    suggestions.querySelectorAll('button').forEach(btn => {
                        btn.addEventListener('click', function() {
                            platInput.value = this.getAttribute('data-plat');
                            suggestions.style.display = 'none';
                        });
                    });
                } else {
                    suggestions.innerHTML = '<div class="list-group-item text-muted">Tidak ditemukan kendaraan yang belum member</div>';
                    suggestions.style.display = 'block';
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target !== platInput && !suggestions.contains(e.target)) {
                    suggestions.style.display = 'none';
                }
            });
        });
    </script>
    <div class="mb-3">
        <label>Tipe Member</label>
        <select name="tipe_member_id" class="form-control" required>
            <option value="">-- Pilih --</option>
            <?php foreach ($types as $t) : ?>
                <option value="<?= $t['id'] ?>" <?= old('tipe_member_id') == $t['id'] ? 'selected' : '' ?>>
                    <?= esc($t['nama']) ?> (<?= $t['discount_percent'] ?>%)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control" value="<?= old('tanggal_mulai') ?>" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" class="form-control" value="<?= old('tanggal_akhir') ?>" required>
    </div>
    <!-- discount_percent will be copied from selected type -->

    <button class="btn btn-primary">Simpan</button>
    <a href="<?= base_url('admin/members') ?>" class="btn btn-secondary">Kembali</a>
</form>

<?= $this->endSection() ?>
