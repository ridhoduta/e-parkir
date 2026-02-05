<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Parkir</title>

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
            background-color: #6f4e37; /* mocha */
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
            background-color: #f4ede4; /* mocha soft */
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
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse min-vh-100">
            <div class="position-sticky p-3">

                <h5 class="text-center mb-4">
                    <i class="fas fa-crown"></i> OWNER PARKIR
                </h5>

                <ul class="nav flex-column">

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('owner/dashboard') ?>">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('owner/areas') ?>">
                            <i class="fas fa-map-marker-alt me-2"></i> Daftar Area
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('owner/kapasitas') ?>">
                            <i class="fas fa-chart-pie me-2"></i> Kapasitas Parkir
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('owner/profile') ?>">
                            <i class="fas fa-user me-2"></i> Profil
                        </a>
                    </li>

                    <li class="nav-item mb-1">
                        <a class="nav-link" href="<?= base_url('owner/report') ?>">
                            <i class="fas fa-chart-bar me-2"></i> Laporan
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

        <!-- MAIN CONTENT -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">

            <!-- TOP BAR -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Halaman Owner</h4>
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