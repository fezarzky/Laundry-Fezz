<?php
session_start();
include __DIR__ . '/../routes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Dalam implementasi nyata, gunakan password_hash
    $akses_id = 2; // Default sebagai pelanggan
    $kode_pelanggan = $_POST['kode_pelanggan'];
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    
    $db = new Database();
    
    // Cek apakah username sudah ada
    $existing_user = $db->login($username);
    if (!empty($existing_user)) {
        $_SESSION['error'] = "Username sudah terdaftar! Gunakan username lain.";
        header("Location: /register");
        exit();
    }
    
    // Tambah user baru
    $result = $db->tambah_data_user($username, $password, $akses_id, $kode_pelanggan, 
                                   $nama_pelanggan, $jenis_kelamin, $alamat, $email, $no_hp);
    
    if ($result) {
        $_SESSION['success'] = "Registrasi berhasil! Silakan login dengan akun Anda.";
        header("Location: /login");
        exit();
    } else {
        $_SESSION['error'] = "Registrasi gagal! Silakan coba lagi.";
        header("Location: /register");
        exit();
    }
} else {
    header("Location: /register");
    exit();
}
?>
