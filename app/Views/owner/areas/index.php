<?= $this->extend('owner/layout/template') ?>
<?= $this->section('content') ?>

<h3>Daftar Area Parkir</h3>

<table class="table table-bordered table-hover">
    <tr>
        <th>No</th>
        <th>Nama Area</th>
        <th>Kapasitas</th>
    </tr>
    <?php if (count($areas) > 0) : ?>
        <?php foreach ($areas as $i => $area) : ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= esc($area['nama_area']) ?></td>
                <td>
                    <span class="badge bg-info"><?= $area['kapasitas'] ?> slot</span>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="3" class="text-center text-muted">Tidak ada data area parkir</td>
        </tr>
    <?php endif; ?>
</table>

<?= $this->endSection() ?>
