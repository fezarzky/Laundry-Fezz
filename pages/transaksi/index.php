<?php
// Session sudah dimulai di routes/web.php
include __DIR__ . '/../../routes/config.php';

// Redirect ke login jika belum login
if (!isset($_SESSION['username'])) {
    header("Location: /login");
    exit();
}

$db = new Database();
$userData = $db->username($_SESSION['username']);
$user = $userData[0];

// Ambil data transaksi berdasarkan role
if ($user['akses_id'] == 1) { // Admin
    $transaksi = $db->tampil_transaksi();
} else { // Pelanggan
    $transaksi = $db->tampil_transaksi_pelanggan($user['kode_pelanggan']);
}

$paket = $db->ambil_data_paket();
$pelanggan = $db->ambil_data_pelanggan();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Laundry Fezz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-tshirt me-2"></i>Laundry Fezz
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">
                    <i class="fas fa-home me-1"></i>Dashboard
                </a>
                <a class="nav-link" href="/logout">
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
                                    <i class="fas fa-exchange-alt me-2"></i>Data Transaksi
                                </h4>
                                <p class="card-text text-muted">Kelola semua transaksi laundry</p>
                            </div>
                            <div class="col-auto">
                                <?php if ($user['akses_id'] == 1): // Admin ?>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                    <i class="fas fa-plus me-2"></i>Tambah Transaksi
                                </button>
                                <?php endif; ?>
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
                    <table id="transaksiTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <?php if ($user['akses_id'] == 1): ?>
                                <th>Pelanggan</th>
                                <?php endif; ?>
                                <th>Paket</th>
                                <th>Kilo</th>
                                <th>Harga/kg</th>
                                <th>Total</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Keluar</th>
                                <th>Status</th>
                                <?php if ($user['akses_id'] == 1): ?>
                                <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            foreach($transaksi as $t): 
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($t['kode_transaksi']) ?></td>
                                <?php if ($user['akses_id'] == 1): ?>
                                <td><?= htmlspecialchars($t['nama_pelanggan']) ?></td>
                                <?php endif; ?>
                                <td><?= htmlspecialchars($t['paket']) ?></td>
                                <td><?= htmlspecialchars($t['kilo']) ?> kg</td>
                                <td>Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($t['total'], 0, ',', '.') ?></td>
                                <td><?= date('d/m/Y', strtotime($t['tanggal_masuk'])) ?></td>
                                <td><?= $t['tanggal_keluar'] ? date('d/m/Y', strtotime($t['tanggal_keluar'])) : '-' ?></td>
                                <td>
                                    <span class="badge <?= $t['status'] == 'Selesai' ? 'bg-success' : ($t['status'] == 'Proses' ? 'bg-warning' : 'bg-info') ?>">
                                        <?= htmlspecialchars($t['status']) ?>
                                    </span>
                                </td>
                                <?php if ($user['akses_id'] == 1): ?>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editTransaksi('<?= $t['kode_transaksi'] ?>')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="hapusTransaksi('<?= $t['kode_transaksi'] ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Transaksi -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Tambah Transaksi Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/api/transaksi/process" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="tambah">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_transaksi" class="form-label">Kode Transaksi</label>
                                <input type="text" class="form-control" name="kode_transaksi" 
                                       value="<?= 'TRX' . date('YmdHis') ?>" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kode_pelanggan" class="form-label">Pelanggan</label>
                                <select class="form-select" name="kode_pelanggan" required>
                                    <option value="">Pilih Pelanggan</option>
                                    <?php foreach($pelanggan as $p): ?>
                                    <option value="<?= $p['kode_pelanggan'] ?>"><?= $p['nama_pelanggan'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_paket" class="form-label">Paket</label>
                                <select class="form-select" name="kode_paket" id="paket" onchange="hitungTotal()" required>
                                    <option value="">Pilih Paket</option>
                                    <?php foreach($paket as $p): ?>
                                    <option value="<?= $p['kode_paket'] ?>" data-harga="<?= $p['harga_paket'] ?>">
                                        <?= $p['paket'] ?> - Rp <?= number_format($p['harga_paket'], 0, ',', '.') ?>/kg
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kilo" class="form-label">Berat (kg)</label>
                                <input type="number" class="form-control" name="kilo" id="kilo" 
                                       step="0.1" min="0.1" onchange="hitungTotal()" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga per kg</label>
                                <input type="number" class="form-control" name="harga" id="harga" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total" class="form-label">Total</label>
                                <input type="number" class="form-control" name="total" id="total" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                                <input type="date" class="form-control" name="tanggal_masuk" 
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
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
            $('#transaksiTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                }
            });
        });

        function hitungTotal() {
            const paketSelect = document.getElementById('paket');
            const kiloInput = document.getElementById('kilo');
            const hargaInput = document.getElementById('harga');
            const totalInput = document.getElementById('total');
            
            if (paketSelect.value && kiloInput.value) {
                const harga = paketSelect.options[paketSelect.selectedIndex].getAttribute('data-harga');
                const kilo = parseFloat(kiloInput.value);
                const total = parseFloat(harga) * kilo;
                
                hargaInput.value = harga;
                totalInput.value = total;
            }
        }

        function editTransaksi(kode) {
            // Implementasi edit transaksi
            alert('Fitur edit transaksi akan segera tersedia');
        }

        function hapusTransaksi(kode) {
            if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
                window.location.href = '/api/transaksi/process?action=hapus&kode_transaksi=' + kode;
            }
        }
    </script>
</body>
</html>
