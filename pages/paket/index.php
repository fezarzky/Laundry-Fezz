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

$paket = $db->tampil_data_paket();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Paket - Laundry Fezz</title>
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
                                    <i class="fas fa-box me-2"></i>Data Paket Laundry
                                </h4>
                                <p class="card-text text-muted">Kelola paket layanan laundry</p>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                    <i class="fas fa-plus me-2"></i>Tambah Paket
                                </button>
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
                    <table id="paketTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Paket</th>
                                <th>Nama Paket</th>
                                <th>Harga per Kg</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach($paket as $p): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($p['kode_paket']) ?></td>
                                <td><?= htmlspecialchars($p['paket']) ?></td>
                                <td>Rp <?= number_format($p['harga_paket'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($p['deskripsi']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editPaket('<?= $p['kode_paket'] ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="hapusPaket('<?= $p['kode_paket'] ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Paket -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Tambah Paket Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="process.php" method="POST">
                    <input type="hidden" name="action" value="tambah">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_paket" class="form-label">Kode Paket</label>
                            <input type="text" class="form-control" name="kode_paket" 
                                   value="<?= 'PKT' . date('YmdHis') ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="paket" class="form-label">Nama Paket</label>
                            <input type="text" class="form-control" name="paket" required 
                                   placeholder="Contoh: Cuci Kering">
                        </div>
                        <div class="mb-3">
                            <label for="harga_paket" class="form-label">Harga per Kg</label>
                            <input type="number" class="form-control" name="harga_paket" required 
                                   placeholder="5000" min="1000">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3" required
                                      placeholder="Deskripsi layanan paket"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#paketTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                }
            });
        });

        function editPaket(kode) {
            // Implementasi edit paket
            alert('Fitur edit paket akan segera tersedia');
        }

        function hapusPaket(kode) {
            if (confirm('Apakah Anda yakin ingin menghapus paket ini?')) {
                window.location.href = 'process.php?action=hapus&kode_paket=' + kode;
            }
        }
    </script>
</body>
</html>
