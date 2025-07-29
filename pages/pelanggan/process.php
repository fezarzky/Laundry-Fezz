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
        $username = $_POST['username'];
        $password = $_POST['password'];
        $akses_id = 2; // Pelanggan
        $kode_pelanggan = $_POST['kode_pelanggan'];
        $nama_pelanggan = $_POST['nama_pelanggan'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $alamat = $_POST['alamat'];
        $email = $_POST['email'];
        $no_hp = $_POST['no_hp'];
        
        // Cek apakah username sudah ada
        $existing_user = $db->login($username);
        if (!empty($existing_user)) {
            $_SESSION['error'] = "Username sudah terdaftar! Gunakan username lain.";
        } else {
            $result = $db->tambah_data_user($username, $password, $akses_id, $kode_pelanggan, 
                                           $nama_pelanggan, $jenis_kelamin, $alamat, $email, $no_hp);
            
            if ($result) {
                $_SESSION['success'] = "Pelanggan berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan pelanggan!";
            }
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $_GET['action'];
    
    if ($action == 'hapus' && isset($_GET['username'])) {
        $username = $_GET['username'];
        $result = $db->hapus_data_pelanggan($username);
        
        if ($result) {
            $_SESSION['success'] = "Pelanggan berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus pelanggan!";
        }
    }
}

header("Location: index.php");
exit();
?>
