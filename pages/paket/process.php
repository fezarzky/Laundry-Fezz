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
        $kode_paket = $_POST['kode_paket'];
        $paket = $_POST['paket'];
        $harga_paket = $_POST['harga_paket'];
        $deskripsi = $_POST['deskripsi'];
        
        $result = $db->tambah_data_paket($kode_paket, $paket, $harga_paket, $deskripsi);
        
        if ($result) {
            $_SESSION['success'] = "Paket berhasil ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambahkan paket!";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $_GET['action'];
    
    if ($action == 'hapus' && isset($_GET['kode_paket'])) {
        $kode_paket = $_GET['kode_paket'];
        $result = $db->hapus_data_paket($kode_paket);
        
        if ($result) {
            $_SESSION['success'] = "Paket berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus paket!";
        }
    }
}

header("Location: index.php");
exit();
?>
