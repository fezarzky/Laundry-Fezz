<?php
session_start();
include __DIR__ . '/../routes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $db = new Database();
    $user_data = $db->login($username);
    
    if (!empty($user_data)) {
        $user = $user_data[0];
        
        // Verifikasi password (dalam implementasi nyata, gunakan password_hash dan password_verify)
        if ($password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_pelanggan'] = $user['nama_pelanggan'];
            $_SESSION['akses_id'] = $user['akses_id'];
            $_SESSION['kode_pelanggan'] = $user['kode_pelanggan'];
            
            $_SESSION['success'] = "Login berhasil! Selamat datang " . $user['nama_pelanggan'];
            header("Location: /");
            exit();
        } else {
            $_SESSION['error'] = "Username atau password salah!";
            header("Location: /login");
            exit();
        }
    } else {
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: /login");
        exit();
    }
} else {
    header("Location: /login");
    exit();
}
?>
