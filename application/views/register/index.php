<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card" style="width: 25rem;">
            <div class="card-body">
                <h3 class="text-center mb-2">Aplikasi Catatan Keuangan</h3>
                <h5 class="card-title text-center">Register</h5>

                <?php if ($this->session->flashdata('register_error')): ?>
                    <div class="alert alert-danger">
                        <?php echo $this->session->flashdata('register_error'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('register_success')): ?>
                    <div class="alert alert-success">
                        <?php echo $this->session->flashdata('register_success'); ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo site_url('auth/register'); ?>" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name'); ?>" required>
                        <?php echo form_error('name'); ?>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>" required>
                        <?php echo form_error('email'); ?>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <?php echo form_error('password'); ?>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <?php echo form_error('confirm_password'); ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                    <p class="mt-3 text-center">Sudah punya akun? <a href="<?php echo site_url('auth'); ?>">Login di sini</a></p>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
