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
$jenis_kelamin = $db->tampil_data_jenis_kelamin();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Laundry Fezz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../resources/css/app.css" rel="stylesheet">
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
                                    <i class="fas fa-user-edit me-2"></i>Profile Pengguna
                                </h4>
                                <p class="card-text text-muted">Kelola informasi profile Anda</p>
                            </div>
                            <div class="col-auto">
                                <div class="avatar-circle bg-primary text-white">
                                    <i class="fas fa-user fa-2x"></i>
                                </div>
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

        <div class="row">
            <!-- Profile Information -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi Profile
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="process.php" method="POST">
                            <input type="hidden" name="action" value="update_profile">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           value="<?= htmlspecialchars($user['username']) ?>" readonly>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="kode_pelanggan" class="form-label">Kode Pelanggan</label>
                                    <input type="text" class="form-control" id="kode_pelanggan" 
                                           value="<?= htmlspecialchars($user['kode_pelanggan']) ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_pelanggan" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" 
                                           value="<?= htmlspecialchars($user['nama_pelanggan']) ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <?php foreach($jenis_kelamin as $jk): ?>
                                        <option value="<?= $jk['kode_jk'] ?>" 
                                                <?= $user['jenis_kelamin'] == $jk['kode_jk'] ? 'selected' : '' ?>>
                                            <?= $jk['jenis_kelamin'] ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="no_hp" class="form-label">No. HP</label>
                                    <input type="tel" class="form-control" id="no_hp" name="no_hp" 
                                           value="<?= htmlspecialchars($user['no_hp']) ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($user['alamat']) ?></textarea>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-lock me-2"></i>Ubah Password
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="process.php" method="POST">
                            <input type="hidden" name="action" value="change_password">
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Password Lama</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" 
                                           name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePasswordVisibility('current_password', 'toggleIcon1')">
                                        <i class="fas fa-eye" id="toggleIcon1"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" 
                                           name="new_password" required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePasswordVisibility('new_password', 'toggleIcon2')">
                                        <i class="fas fa-eye" id="toggleIcon2"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" required>
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePasswordVisibility('confirm_password', 'toggleIcon3')">
                                        <i class="fas fa-eye" id="toggleIcon3"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key me-2"></i>Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Account Info -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info me-2"></i>Informasi Akun
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Status:</div>
                            <div class="col-6">
                                <span class="badge bg-success">
                                    <?= $user['akses_id'] == 1 ? 'Administrator' : 'Pelanggan' ?>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6 text-muted">Member Since:</div>
                            <div class="col-6">
                                <?= isset($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : '-' ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-muted">Total Transaksi:</div>
                            <div class="col-6">
                                <?php
                                if ($user['akses_id'] == 2) {
                                    $transaksi = $db->tampil_transaksi_pelanggan($user['kode_pelanggan']);
                                    echo count($transaksi);
                                } else {
                                    echo '-';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../resources/js/app.js"></script>
    
    <style>
        .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</body>
</html>
