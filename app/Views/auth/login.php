<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
        }

        .card {
            border: none;
            border-radius: 12px;
            background-color: #f4ede4; /* mocha soft */
        }

        .card-header {
            background-color: #6f4e37; /* mocha */
            color: #fff;
            border-radius: 12px 12px 0 0;
            font-size: 1.1rem;
        }

        label {
            color: #4b2e1e;
            font-weight: 500;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #c2a48a;
        }

        .form-control:focus {
            border-color: #6f4e37;
            box-shadow: 0 0 0 0.2rem rgba(111, 78, 55, 0.25);
        }

        .btn-mocha {
            background-color: #6f4e37;
            border: none;
            color: #fff;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-mocha:hover {
            background-color: #5a3e2b;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <strong>Login Sistem Parkir</strong>
                    </div>
                    <div class="card-body">

                        <?php if (session()->getFlashdata('error')) : ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('login') ?>" method="post">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button class="btn btn-mocha w-100" type="submit">
                                Login
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>