<?php
// Session sudah dimulai di routes/web.php
include __DIR__ . '/../../routes/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit();
}

$db = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'tambah') {
        $kode_transaksi = $_POST['kode_transaksi'];
        $kode_pelanggan = $_POST['kode_pelanggan'];
        $kode_paket = $_POST['kode_paket'];
        $harga = $_POST['harga'];
        $kilo = $_POST['kilo'];
        $total = $_POST['total'];
        $tanggal_masuk = $_POST['tanggal_masuk'];
        $tanggal_keluar = null; // Will be set when status is completed
        $status = $_POST['status'];
        
        $result = $db->tambah_transaksi($kode_transaksi, $kode_pelanggan, $kode_paket, 
                                       $harga, $kilo, $total, $tanggal_masuk, $tanggal_keluar, $status);
        
        if ($result) {
            $_SESSION['success'] = "Transaksi berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan transaksi!";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $_GET['action'];
    
    if ($action == 'hapus' && isset($_GET['kode_transaksi'])) {
        $kode_transaksi = $_GET['kode_transaksi'];
        $result = $db->hapus_data_transaksi($kode_transaksi);
        
        if ($result) {
            $_SESSION['success'] = "Transaksi berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus transaksi!";
        }
    }
}

header("Location: index.php");
exit();
?>
