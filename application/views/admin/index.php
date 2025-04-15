<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Keuangan Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
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
                        <a href="<?= site_url('auth/logout') ?>" class="btn btn-sm btn-outline-danger" onclick="confirm('Yakin?')">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center mb-0">Dashboard  Catatan Keuangan Admin</h1>

        </div>

        <div class="card p-3 mb-2 bg-info">
            <div class="d-flex justify-content-end w-100 ">
                <div>
                    <button class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#addUserModal">Tambah User Baru</a>
                </div>
            </div>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <?php endif; ?>

        <!-- Users List -->
        <ul class="list-group">
            <?php foreach ($users as $user): ?>
                <li class="list-group-item mb-2 list-group-item-primary">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-1">Nama: <?= $user->name ?><small class="text-muted"> | (Email: <?= $user->email ?>)</small></h5>
                                <h5>
                                    <span class="badge bg-primary">Level: <?= ucfirst($user->role) ?></span>
                                    <span class="badge bg-success">Pemasukan: Rp. <?= number_format($user->total_income, 0, '.', '.') ?></span>
                                    <span class="badge bg-danger">Pengeluaran: Rp. <?= number_format($user->total_expenses, 0, '.', '.')  ?></span>
                                    <span class="badge bg-primary">Sisa Kas: Rp. <?= number_format($user->total_balance, 0, '.', '.') ?></span>
                                </h5>
                            </div>
                            <div>
                                <button class="btn btn-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addTransactionModal"
                                    onclick="setModalAddTransaction(<?= $user->id ?>)">
                                    Tambah Transaksi
                                </button>
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editUserModal"
                                    onclick='setModalEditUser(<?= json_encode($user) ?>)'>
                                    Edit User
                                </button>
                                <a href="<?= site_url('auth/hapus_user/' . $user->id) ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Yakin ingin menghapus user ini? Semua transaksi akan ikut terhapus!')">
                                    Hapus User
                                </a>
                            </div>
                        </div>
                        <ul class="list-group">
                            <?php foreach ($user->transactions as $transaction): ?>
                                <li class="list-group-item">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <p class="mb-1"><strong>Jenis Kas:</strong>
                                                    <span class="badge bg-<?= $transaction->type == 'masuk' ? 'success' : 'danger' ?>">
                                                        <?= ucfirst($transaction->type) ?>
                                                    </span>
                                                </p>
                                                <p class="mb-1"><strong>Jumlah:</strong> Rp <?= number_format($transaction->amount, 0, ',', '.') ?></p>
                                                <p class="mb-1"><strong>Tanggal Transaksi:</strong> <?= date('d M Y', strtotime($transaction->date)) ?></p>
                                                <p class="mb-0"><strong>Deskripsi:</strong> <?= $transaction->description ?></p>
                                            </div>
                                            <div>
                                                <button class="btn btn-sm btn-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editTransactionModal"
                                                    onclick="setTransactionEditModal(<?= $transaction->id ?>, '<?= $transaction->type ?>', <?= $transaction->amount ?>, '<?= $transaction->date ?>', '<?= addslashes($transaction->description) ?>')">
                                                    Edit
                                                </button>
                                                <a href="<?= site_url('admin/hapus_transaksi/' . $transaction->id) ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Hapus transaksi ini?')">
                                                    Hapus
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Modal Tambah Transaksi -->
        <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTransactionModalLabel">Tambah Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="<?= site_url('admin/tambah_transaksi') ?>">
                        <div class="modal-body">
                            <input type="hidden" name="user_id" id="transactionUserId">
                            <div class="mb-3">
                                <label for="jenis" class="form-label">Jenis Transaksi</label>
                                <select id="jenis" class="form-select" name="type" required>
                                    <option value="masuk">Uang Masuk</option>
                                    <option value="keluar">Uang Keluar</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" id="jumlah" class="form-control" name="amount" placeholder="Jumlah Transaksi" required>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" id="tanggal" class="form-control" name="date" required>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea id="deskripsi" class="form-control" name="description" placeholder="Deskripsi Transaksi" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Transaksi -->
        <div class="modal fade" id="editTransactionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="<?= site_url('admin/update_transaksi') ?>">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editTransactionId">
                            <div class="mb-3">
                                <label class="form-label">Jenis Transaksi</label>
                                <select class="form-select" name="type" id="editTransactionType" required>
                                    <option value="masuk">Uang Masuk</option>
                                    <option value="keluar">Uang Keluar</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" class="form-control" name="amount" id="editTransactionAmount" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="date" id="editTransactionDate" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" id="editTransactionDescription" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal edit user -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= site_url('admin/update_user') ?>">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUserId">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name" id="editUserName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editUserEmail" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" id="editUserRole" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reset Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mereset">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal tambah user  -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="<?= site_url('admin/tambah_user') ?>">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editUserId">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
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
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" id="editUserRole" required>
                                <option value="user" <?= $this->session->userdata('role') == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= $this->session->userdata('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
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
        function setModalAddTransaction(userId) {
            document.getElementById('transactionUserId').value = userId;
        }

        function setTransactionEditModal(id, type, amount, date, description) {
            document.getElementById('editTransactionId').value = id;
            document.getElementById('editTransactionType').value = type;
            document.getElementById('editTransactionAmount').value = amount;
            document.getElementById('editTransactionDate').value = date;
            document.getElementById('editTransactionDescription').value = description;
        }

        function setModalEditUser(user) {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserName').value = user.name;
            document.getElementById('editUserEmail').value = user.email;
            document.getElementById('editUserRole').value = user.role;
        }
    </script>
</body>

</html>