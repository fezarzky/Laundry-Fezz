<?php
// Session sudah dimulai di routes/web.php
include __DIR__ . '/../../routes/config.php';

// Redirect ke login jika belum login
if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

$db = new Database();
$userData = $db->username($_SESSION['username']);
$user = $userData[0];

// Cek akses admin
if ($user['akses_id'] != 1) {
    header("Location: ../../index.php");
    exit();
}

$users = $db->tampil_data();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User - Laundry Fezz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">
                <i class="fas fa-tshirt me-2"></i>Laundry Fezz
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../../index.php">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="../../logout.php">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-users-cog me-2"></i>Data User
                                </h4>
                                <p class="card-text text-muted">Kelola semua pengguna sistem</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>' . $_SESSION['success'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>' . $_SESSION['error'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>';
            unset($_SESSION['error']);
        }
        ?>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="userTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach($users as $u): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($u['username']) ?></td>
                                <td><?= htmlspecialchars($u['nama_pelanggan']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['no_hp']) ?></td>
                                <td>
                                    <span class="badge <?= $u['akses_id'] == 1 ? 'bg-danger' : 'bg-primary' ?>">
                                        <?= $u['akses_id'] == 1 ? 'Administrator' : 'Pelanggan' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info me-1" onclick="viewUser('<?= $u['username'] ?>')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editRole('<?= $u['username'] ?>', <?= $u['akses_id'] ?>)">
                                        <i class="fas fa-user-cog"></i>
                                    </button>
                                    <?php if ($u['username'] != 'admin' && $u['username'] != $_SESSION['username']): ?>
                                    <button class="btn btn-sm btn-danger" onclick="hapusUser('<?= $u['username'] ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Role -->
    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-cog me-2"></i>Edit Role User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="process.php" method="POST">
                    <input type="hidden" name="action" value="edit_role">
                    <input type="hidden" name="username" id="edit_username">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="akses_id" class="form-label">Role</label>
                            <select class="form-select" name="akses_id" id="edit_akses_id" required>
                                <option value="1">Administrator</option>
                                <option value="2">Pelanggan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal View User -->
    <div class="modal fade" id="viewUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Detail User
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="userDetailContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                }
            });
        });

        function viewUser(username) {
            // Load user details via AJAX
            $.post('get_user_detail.php', {username: username}, function(data) {
                $('#userDetailContent').html(data);
                $('#viewUserModal').modal('show');
            });
        }

        function editRole(username, currentRole) {
            $('#edit_username').val(username);
            $('#edit_akses_id').val(currentRole);
            $('#editRoleModal').modal('show');
        }

        function hapusUser(username) {
            if (confirm('Apakah Anda yakin ingin menghapus user ini? Semua data terkait akan terhapus!')) {
                window.location.href = 'process.php?action=hapus&username=' + username;
            }
        }
    </script>
</body>
</html>
