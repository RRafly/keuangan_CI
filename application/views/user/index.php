<!DOCTYPE html>
<html>

<head>
    <title>Keuangan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f8ff;
            font-family: 'Segoe UI', sans-serif;
        }

        .card {
            border-radius: 15px;
        }
    </style>
</head>

<body>
    <div class="container pb-4">
        <div class="d-flex justify-content-end">
            <div class="card p-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                        </svg>
                    </div>

                    <div>
                        <h6 class="mb-1"><?= $this->session->userdata('user_name') ?></h6>
                        <p class="mb-1 text-muted small"><?= $this->session->userdata('user_email') ?></p>
                        <span class="badge bg-<?= $this->session->userdata('role') == 'admin' ? 'primary' : 'secondary' ?>">
                            <?= ucfirst($this->session->userdata('role')) ?>
                        </span>
                        <a data-bs-toggle="modal"
                            data-bs-target="#updateAccountModal"
                            class="text-primary">
                            Pengaturan Akun
                        </a>
                    </div>
                    <div class="ms-3">
                        <a href="<?= site_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="text-center mb-4">Dashboard Catatan Keuangan</h2>

        <!-- Ringkasan -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white p-3">
                    <h5>Total Uang Masuk</h5>
                    <h3>Rp. <?= number_format($total_income, 0, '.', '.') ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white p-3">
                    <h5>Total Uang Keluar</h5>
                    <h3>Rp. <?= number_format($total_expenses, 0, '.', '.') ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark p-3">
                    <h5>Sisa Uang</h5>
                    <h3>Rp. <?= number_format($total_balance, 0, '.', '.') ?></h3>
                </div>
            </div>
        </div>

        <!-- Form Tambah Transaksi -->
        <div class="card p-4 mb-4">
            <h5>Tambah Transaksi</h5>
            <form action="<?= site_url('tambah') ?>" method="post">
                <div class="mb-2">
                    <select class="form-select" name="type" required>
                        <option value="masuk">Uang Masuk</option>
                        <option value="keluar">Uang Keluar</option>
                    </select>
                </div>
                <div class="mb-2">
                    <input name="amount" type="number" class="form-control" placeholder="Jumlah" required>
                </div>
                <div class="mb-2">
                    <input name="date" type="date" class="form-control" required>
                </div>
                <div class="mb-2">
                    <textarea name="description" class="form-control" placeholder="Deskripsi" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>

        <!-- Daftar Transaksi -->
        <div class="card p-4">
            <h5>Transaksi Saya</h5>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <th>Jenis Transaksi</th>
                        <th>Jumlah</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksi as $t): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($t->date)) ?></td>
                            <td>
                                <span class="badge bg-<?= $t->type == 'masuk' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($t->type) ?>
                                </span>
                            </td>
                            <td>Rp <?= number_format($t->amount, 0, ',', '.') ?></td>
                            <td><?= $t->description ?></td>
                            <td>
                                <!-- Button trigger modal -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTransactionModal" onclick='editTransactionModal(<?= json_encode($t) ?>)'>
                                    Edit
                                </button>
                                <a href="<?= site_url('hapus/' . $t->id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Edit Transaksi -->
    <div class="modal fade" id="editTransactionModal">
        <div class="modal-dialog">
            <form action="<?= site_url('update') ?>" method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <select name="type" id="typeInput" class="form-select" required>
                                <option value="masuk" <?= $t->type == 'masuk' ? 'selected' : '' ?>>Uang Masuk</option>
                                <option value="keluar" <?= $t->type == 'keluar' ? 'selected' : '' ?>>Uang Keluar</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <input type="number" id="amountInput" name="amount" value="<?= $t->amount ?>" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <input type="date" id="dateInput" name="date" value="<?= $t->date ?>" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="description" id="descriptionInput" class="form-control" required><?= $t->description ?></textarea>
                        </div>
                        <input type="hidden" name="id" id="idInput">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

      <!-- Modal Pengaturan Akun -->
      <div class="modal fade" id="updateAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="<?= site_url('auth/update_user') ?>" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pengaturan Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $this->session->userdata('user_id') ?>">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="<?= $this->session->userdata('user_name') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $this->session->userdata('user_email') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ganti Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti">
                    </div>
                    <div class="mb-3">
                        <a href="<?= site_url('auth/hapus_user/'. $this->session->userdata('user_id'))?>" class="btn btn-outline-danger" onclick="confirm('Yakin?')" >Hapus Akun</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function editTransactionModal(data) {
            document.getElementById('typeInput').value = data.type;
            document.getElementById('amountInput').value = data.amount;
            document.getElementById('dateInput').value = data.date;
            document.getElementById('descriptionInput').value = data.description;
            document.getElementById('idInput').value = data.id;
        }
    </script>

</body>

</html>