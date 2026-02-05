<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
        }

        /* SIDEBAR */
        .sidebar {
            background-color: #6f4e37;
        }

        .sidebar h5 {
            color: #fff;
            letter-spacing: 1px;
        }

        .sidebar .nav-link {
            color: #f5eee6;
            border-radius: 8px;
            padding: 8px 12px;
        }

        .sidebar .nav-link:hover {
            background-color: #5a3e2b;
            color: #fff;
        }

        .sidebar .nav-link.text-danger:hover {
            background-color: #8b0000;
            color: #fff;
        }

        /* MAIN */
        main h4 {
            color: #4b2e1e;
            font-weight: 600;
        }

        .badge-mocha {
            background-color: #6f4e37;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 12px;
            background-color: #f4ede4;
        }

        .card-body {
            color: #4b2e1e;
        }

        hr {
            border-color: #c2a48a;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php if (session('role_id') == 2) : ?>
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse min-vh-100">
            <div class="position-sticky p-3">

                <h5 class="text-center mb-4">
                    <i class="fas fa-parking"></i> ADMIN PARKIR
                </h5>

                <ul class="nav flex-column">

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/users') ?>">
                            <i class="fas fa-users me-2"></i> Manajemen User
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/profile') ?>">
                            <i class="fas fa-user me-2"></i> Profil
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/areas') ?>">
                            <i class="fas fa-map-marker-alt me-2"></i> Area Parkir
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/tipe_kendaraan') ?>">
                            <i class="fas fa-car me-2"></i> Tipe Kendaraan
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/tarif_parkir') ?>">
                            <i class="fas fa-money-bill-wave me-2"></i> Tarif Parkir
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/members') ?>">
                            <i class="fas fa-id-card me-2"></i> Members
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/member_types') ?>">
                            <i class="fas fa-id-card me-2"></i> Tipe Member
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/kendaraan') ?>">
                            <i class="fas fa-car-side me-2"></i> Data Kendaraan
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/transaksi') ?>">
                            <i class="fas fa-history me-2"></i> Riwayat Transaksi
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/report') ?>">
                            <i class="fas fa-chart-bar me-2"></i> Laporan
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/logs') ?>">
                            <i class="fas fa-list me-2"></i> Log Aktivitas
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('admin/backup') ?>">
                            <i class="fas fa-database me-2"></i> Backup & Restore
                        </a>
                    </li>

                    <hr>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?= base_url('/logout') ?>"
                           onclick="return confirm('Yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>

                </ul>
            </div>
        </nav>
        <?php endif; ?>

        <!-- MAIN CONTENT -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            <!-- TOP BAR -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Halaman Admin</h4>
                <span class="badge badge-mocha">
                    <?= session('nama') ?>
                </span>
            </div>

            <!-- CONTENT -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <?= $this->renderSection('content'); ?>
                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>