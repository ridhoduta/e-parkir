<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col">
        <a href="<?= base_url('petugas/parkir') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<h3>Input Kendaraan Keluar</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i><?= implode('<br>', session('errors')) ?>
    </div>
<?php endif; ?>

<?php if (session('error')) : ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i><?= session('error') ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('petugas/parkir/proses-keluar') ?>" class="col-md-6">

    <div class="mb-3">
        <label>Pilih Nomor Tiket</label>
        <select name="nomor_tiket" id="ticket_select" class="form-control form-control-lg" required autofocus>
            <option value="">-- Pilih Nomor Tiket --</option>
            <?php foreach ($tickets as $t) : ?>
                <option value="<?= esc($t['nomor_tiket']) ?>"><?= esc($t['nomor_tiket']) ?> — <?= esc($t['plat_nomor']) ?> (<?= esc($t['tipe_nama']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="ticket_info" class="mb-3" style="display:none;">
        <label><strong>Informasi Tiket</strong></label>
        <div id="ticket_desc" class="p-2 border rounded bg-light"></div>
    </div>

    <button type="submit" class="btn btn-warning btn-lg">
        <i class="fas fa-search me-2"></i>Cari & Proses Keluar
    </button>

</form>

<script>
    const tickets = <?= json_encode(array_column($tickets, null, 'nomor_tiket')) ?>;

    document.addEventListener('DOMContentLoaded', function(){
        const sel = document.getElementById('ticket_select');
        const info = document.getElementById('ticket_info');
        const desc = document.getElementById('ticket_desc');

        function renderTicket(k){
            if(!k){ info.style.display='none'; desc.innerHTML=''; return; }
            const t = tickets[k];
            if(!t){ info.style.display='none'; desc.innerHTML=''; return; }
            let html = '<div>Nomor Tiket: <strong>'+t.nomor_tiket+'</strong></div>';
            html += '<div>Plat: <strong>'+t.plat_nomor+'</strong></div>';
            html += '<div>Tipe: <strong>'+t.tipe_nama+'</strong></div>';
            html += '<div>Waktu Masuk: <strong>'+t.waktu_masuk+'</strong></div>';
            desc.innerHTML = html;
            info.style.display = 'block';
        }

        sel.addEventListener('change', function(){ renderTicket(this.value); });
        if(sel.value) renderTicket(sel.value);
    });
</script>

<?= $this->endSection() ?>
