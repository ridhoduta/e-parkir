<?= $this->extend('admin/layout/template') ?>
<?= $this->section('content') ?>

<h3>Manajemen User</h3>
<a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary mb-3">Tambah User</a>

<table class="table table-bordered">
    <tr>
        <th>Username</th>
        <th>Nama</th>
        <th>Role</th>
        <th>Aksi</th>
    </tr>

    <?php foreach ($users as $u) : ?>
    <tr>
        <td><?= $u['username'] ?></td>
        <td><?= $u['nama'] ?></td>
        <td><?= $u['nama_role'] ?></td>
        <td>
            <a href="<?= base_url('admin/users/edit/'.$u['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="<?= base_url('admin/users/delete/'.$u['id']) ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Hapus user ini?')">Hapus</a>
        </td>
    </tr>
    <?php endforeach ?>
</table>

<?= $this->endSection() ?>
