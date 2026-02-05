<?= $this->extend('admin/layout/template') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><i class="fas fa-list me-2"></i> Log Aktivitas Sistem</h5>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle table-sm">
        <thead class="table-light">
            <tr>
                <th>Waktu</th>
                <th>User</th>
                <th>Aktivitas</th>
                <th>Deskripsi</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log) : ?>
                <tr>
                    <td><small><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></small></td>
                    <td>
                        <span class="badge bg- Mocha" style="background-color: #6f4e37;">
                            <?= $log['user_nama'] ?? 'System' ?>
                        </span>
                    </td>
                    <td><span class="badge bg-secondary"><?= $log['aktivitas'] ?></span></td>
                    <td><small><?= $log['deskripsi'] ?></small></td>
                    <td><code><?= $log['ip_address'] ?></code></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($logs)) : ?>
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada log aktivitas</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4 d-flex justify-content-center">
    <?= $pager->links('default', 'bootstrap_full') ?>
</div>

<?= $this->endSection() ?>
