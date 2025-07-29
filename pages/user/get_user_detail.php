<?php
// Session sudah dimulai di routes/web.php
include __DIR__ . '/../../routes/config.php';

if (!isset($_SESSION['username']) || !isset($_POST['username'])) {
    echo "Unauthorized access";
    exit();
}

$db = new Database();
$userData = $db->username($_SESSION['username']);
$user = $userData[0];

// Cek akses admin
if ($user['akses_id'] != 1) {
    echo "Access denied";
    exit();
}

$targetUser = $db->username($_POST['username']);
if (empty($targetUser)) {
    echo "User tidak ditemukan";
    exit();
}

$u = $targetUser[0];

// Hitung total transaksi jika pelanggan
$totalTransaksi = 0;
if ($u['akses_id'] == 2) {
    $transaksi = $db->tampil_transaksi_pelanggan($u['kode_pelanggan']);
    $totalTransaksi = count($transaksi);
}
?>

<div class="row">
    <div class="col-md-6">
        <h6 class="text-muted">Informasi Akun</h6>
        <table class="table table-borderless">
            <tr>
                <td width="40%">Username:</td>
                <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
            </tr>
            <tr>
                <td>Kode Pelanggan:</td>
                <td><?= htmlspecialchars($u['kode_pelanggan']) ?></td>
            </tr>
            <tr>
                <td>Role:</td>
                <td>
                    <span class="badge <?= $u['akses_id'] == 1 ? 'bg-danger' : 'bg-primary' ?>">
                        <?= $u['akses_id'] == 1 ? 'Administrator' : 'Pelanggan' ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Status:</td>
                <td><span class="badge bg-success">Aktif</span></td>
            </tr>
        </table>
    </div>
    
    <div class="col-md-6">
        <h6 class="text-muted">Informasi Personal</h6>
        <table class="table table-borderless">
            <tr>
                <td width="40%">Nama Lengkap:</td>
                <td><strong><?= htmlspecialchars($u['nama_pelanggan']) ?></strong></td>
            </tr>
            <tr>
                <td>Jenis Kelamin:</td>
                <td><?= htmlspecialchars($u['jenis_kelamin']) ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?= htmlspecialchars($u['email']) ?></td>
            </tr>
            <tr>
                <td>No. HP:</td>
                <td><?= htmlspecialchars($u['no_hp']) ?></td>
            </tr>
        </table>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-muted">Alamat</h6>
        <p class="border rounded p-3 bg-light"><?= htmlspecialchars($u['alamat']) ?></p>
    </div>
</div>

<?php if ($u['akses_id'] == 2): ?>
<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-muted">Statistik Transaksi</h6>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= $totalTransaksi ?></h5>
                        <p class="card-text text-muted">Total Transaksi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-success">
                            <?php
                            $selesai = 0;
                            if (!empty($transaksi)) {
                                foreach($transaksi as $t) {
                                    if ($t['status'] == 'Selesai') $selesai++;
                                }
                            }
                            echo $selesai;
                            ?>
                        </h5>
                        <p class="card-text text-muted">Transaksi Selesai</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-info">
                            <?php
                            $totalPendapatan = 0;
                            if (!empty($transaksi)) {
                                foreach($transaksi as $t) {
                                    if ($t['status'] == 'Selesai') {
                                        $totalPendapatan += $t['total'];
                                    }
                                }
                            }
                            echo 'Rp ' . number_format($totalPendapatan, 0, ',', '.');
                            ?>
                        </h5>
                        <p class="card-text text-muted">Total Pendapatan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
