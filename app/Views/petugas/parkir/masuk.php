<?= $this->extend('petugas/layout/template') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col">
        <a href="<?= base_url('petugas/parkir') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<h3>Input Kendaraan Masuk</h3>

<?php if (session('errors')) : ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i><?= implode('<br>', session('errors')) ?>
    </div>
<?php endif; ?>

<?php if (session('error')) : ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form method="post" action="<?= base_url('petugas/parkir/store-masuk') ?>" class="col-md-6">

    <div class="mb-3">
        <label>Plat Nomor <span class="text-danger">*</span></label>
        <input type="text" id="plat_input" name="plat_nomor" class="form-control" placeholder="Masukkan atau pilih plat nomor" value="<?= old('plat_nomor') ?>" required>
        <small class="form-text text-muted">Ketik plat atau pilih dari daftar yang tersedia</small>
        <div id="plat_suggestions" class="list-group mt-2" style="display:none; max-height: 200px; overflow-y: auto;"></div>
    </div>

    <!-- Alert untuk kendaraan yang sedang parkir -->
    <div id="double_entry_alert" class="alert alert-danger alert-dismissible fade show mb-3" style="display:none;" role="alert">
        <h5 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Peringatan: Kendaraan Masih Parkir!</h5>
        <div id="double_entry_content"></div>
        <hr>
        <p class="mb-0"><small><strong>Tindakan:</strong> Silakan lakukan proses keluar untuk kendaraan ini terlebih dahulu sebelum melakukan entry baru.</small></p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div id="vehicle_info" class="mb-3" style="display:none;">
        <label><strong>Informasi Kendaraan</strong></label>
        <div id="vehicle_desc" class="p-3 border rounded bg-light"></div>
    </div>

    <div class="mb-3">
        <label>Tipe Kendaraan <span class="text-danger">*</span></label>
        <select name="tipe_kendaraan_id" id="tipe_select" class="form-control" required>
            <option value="">-- Pilih Tipe Kendaraan --</option>
            <?php foreach ($tipeKendaraan as $tipe) : ?>
                <option value="<?= $tipe['id'] ?>"><?= $tipe['nama_tipe'] ?></option>
            <?php endforeach; ?>
        </select>
        <small id="tipe_note" class="form-text text-success" style="display:none;">
            <i class="fas fa-check-circle"></i> Tipe kendaraan otomatis terisi dari data kendaraan
        </small>
    </div>

    <div class="mb-3">
        <label>Pilih Area <span class="text-danger">*</span></label>
        <select name="area_id" id="area_select" class="form-control" required>
            <option value="">-- Pilih Area --</option>
            <?php foreach ($areas as $area) : ?>
                <option value="<?= $area['id'] ?>"><?= $area['nama_area'] ?></option>
            <?php endforeach; ?>
        </select>
        <div id="capacity_info" class="mt-2" style="display:none;">
            <span class="badge bg-info" id="capacity_badge">Kapasitas Tersedia: 0</span>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Catat Masuk & Generate Tiket
    </button>

</form>

<script>
    const vehicles = <?= json_encode($vehicles) ?>;
    const members = <?= json_encode($membersByPlat) ?>;
    const tipeKendaraanList = <?= json_encode($tipeKendaraan) ?>;
    const capacities = <?= json_encode($capacities) ?>;

    document.addEventListener('DOMContentLoaded', function(){
        const platInput = document.getElementById('plat_input');
        const suggestions = document.getElementById('plat_suggestions');
        const vehicleInfo = document.getElementById('vehicle_info');
        const vehicleDesc = document.getElementById('vehicle_desc');
        const tipeSelect = document.getElementById('tipe_select');
        const areaSelect = document.getElementById('area_select');
        const capacityInfo = document.getElementById('capacity_info');
        const capacityBadge = document.getElementById('capacity_badge');
        const tipeNote = document.getElementById('tipe_note');
        const doubleEntryAlert = document.getElementById('double_entry_alert');
        const doubleEntryContent = document.getElementById('double_entry_content');

        // Function to update capacity info
        function updateCapacity() {
            const areaId = areaSelect.value;
            const tipeId = tipeSelect.value;

            if (areaId && tipeId) {
                const cap = capacities.find(c => c.area_id == areaId && c.tipe_kendaraan_id == tipeId);
                if (cap) {
                    capacityBadge.innerText = `Kapasitas Tersedia: ${cap.kapasitas}`;
                    if (cap.kapasitas <= 0) {
                        capacityBadge.className = 'badge bg-danger';
                        capacityBadge.innerText = 'Kapasitas Penuh (0)';
                    } else if (cap.kapasitas <= 3) {
                        capacityBadge.className = 'badge bg-warning text-dark';
                    } else {
                        capacityBadge.className = 'badge bg-info';
                    }
                    capacityInfo.style.display = 'block';
                } else {
                    capacityBadge.className = 'badge bg-secondary';
                    capacityBadge.innerText = 'Kapasitas tidak terdefinisi untuk tipe ini';
                    capacityInfo.style.display = 'block';
                }
            } else {
                capacityInfo.style.display = 'none';
            }
        }

        tipeSelect.addEventListener('change', updateCapacity);
        areaSelect.addEventListener('change', updateCapacity);

        // Buat mapping untuk memudahkan lookup
        const vehicleMap = {};
        vehicles.forEach(v => {
            vehicleMap[v.plat_nomor.toUpperCase()] = v;
        });

        // Handle input plat nomor
        platInput.addEventListener('input', function(){
            const value = this.value.toUpperCase().trim();
            
            if(!value || value.length < 2){
                suggestions.style.display = 'none';
                return;
            }

            // Filter yang cocok
            const matches = vehicles.filter(v => 
                v.plat_nomor.toUpperCase().includes(value)
            );

            if(matches.length > 0){
                let html = '';
                matches.forEach(v => {
                    html += `<button type="button" class="list-group-item list-group-item-action" data-plat="${v.plat_nomor}">
                        <div><strong>${v.plat_nomor}</strong> — ${v.tipe_nama}</div>
                    </button>`;
                });
                suggestions.innerHTML = html;
                suggestions.style.display = 'block';

                // Handle klik saran
                suggestions.querySelectorAll('button').forEach(btn => {
                    btn.addEventListener('click', function(e){
                        e.preventDefault();
                        const selectedPlat = this.getAttribute('data-plat');
                        platInput.value = selectedPlat;
                        suggestions.style.display = 'none';
                        loadVehicleData(selectedPlat);
                    });
                });
            } else {
                suggestions.innerHTML = '<div class="list-group-item text-muted">Tidak ada kendaraan yang cocok</div>';
                suggestions.style.display = 'block';
            }
        });

        // Sembunyikan saran saat klik di tempat lain
        document.addEventListener('click', function(e){
            if(e.target !== platInput && !suggestions.contains(e.target)){
                suggestions.style.display = 'none';
            }
        });

        // Handle blur - check data jika diisi manual
        platInput.addEventListener('blur', function(){
            if(this.value.trim()){
                loadVehicleData(this.value.toUpperCase().trim());
            }
        });

        // Function untuk load data kendaraan
        function loadVehicleData(plat){
            plat = plat.toUpperCase();
            const vehicle = vehicleMap[plat];

            // Check double entry via AJAX
            checkDoubleEntry(plat);

            if(vehicle){
                // Data ada di sistem - auto fill
                showVehicleInfo(plat, vehicle, true);
                tipeSelect.value = vehicle.tipe_kendaraan_id;
                tipeNote.style.display = 'block';
            } else {
                // Data tidak ada - user input manual
                showVehicleInfo(plat, null, false);
                tipeSelect.value = '';
                tipeNote.style.display = 'none';
            }
        }

        // Function untuk check apakah kendaraan masih parkir
        function checkDoubleEntry(plat){
            fetch('<?= base_url('petugas/parkir/check-plat') ?>?plat_nomor=' + encodeURIComponent(plat))
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'warning'){
                        // Tampilkan peringatan
                        let content = `<div class="alert alert-danger mb-0">
                            <p><strong>Plat:</strong> ${data.detail.plat_nomor}</p>
                            <p><strong>Area Parkir:</strong> ${data.detail.area}</p>
                            <p><strong>Waktu Masuk:</strong> ${data.detail.waktu_masuk}</p>
                            <p><strong>Nomor Tiket:</strong> ${data.detail.nomor_tiket}</p>
                        </div>`;
                        doubleEntryContent.innerHTML = content;
                        doubleEntryAlert.style.display = 'block';
                    } else {
                        doubleEntryAlert.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Function untuk tampilkan info kendaraan
        function showVehicleInfo(plat, vehicle, isRegistered){
            let html = `<div class="mb-2">
                <div><strong>Plat:</strong> ${plat}</div>`;
            
            if(isRegistered){
                html += `<div class="text-success">
                    <i class="fas fa-check-circle"></i> 
                    <strong>Terdaftar di Sistem</strong>
                </div>
                <div><strong>Tipe:</strong> ${vehicle.tipe_nama}</div>`;
            } else {
                html += `<div class="text-warning">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Data Baru - Silakan Pilih Tipe Kendaraan</strong>
                </div>`;
            }

            // Check member
            const member = members[plat];
            if(member){
                html += `<div class="text-success mt-2">
                    <i class="fas fa-user-check"></i> 
                    <strong>Member: ${member.nama}</strong>
                    <br/>Tipe: ${member.tipe_member}
                    <br/>Diskon: ${member.discount_percent}%
                </div>`;
            } else {
                html += `<div class="text-muted mt-2">
                    <i class="fas fa-user"></i> Bukan member aktif
                </div>`;
            }

            html += '</div>';
            vehicleDesc.innerHTML = html;
            vehicleInfo.style.display = 'block';
        }

        // Pre-fill jika ada nilai sebelumnya
        if(platInput.value){
            loadVehicleData(platInput.value);
        }
    });
</script>

<?= $this->endSection() ?>
