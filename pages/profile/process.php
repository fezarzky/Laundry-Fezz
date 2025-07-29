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
    
    if ($action == 'update_profile') {
        $kode_pelanggan = $_SESSION['kode_pelanggan'];
        $nama_pelanggan = $_POST['nama_pelanggan'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $alamat = $_POST['alamat'];
        $email = $_POST['email'];
        $no_hp = $_POST['no_hp'];
        
        $result = $db->edit_data_pelanggan($kode_pelanggan, $nama_pelanggan, $jenis_kelamin, $alamat, $email, $no_hp);
        
        if ($result) {
            $_SESSION['nama_pelanggan'] = $nama_pelanggan; // Update session
            $_SESSION['success'] = "Profile berhasil diupdate!";
        } else {
            $_SESSION['error'] = "Gagal mengupdate profile!";
        }
    }
    
    elseif ($action == 'change_password') {
        $username = $_SESSION['username'];
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Validasi password lama
        $user_data = $db->login($username);
        if (!empty($user_data)) {
            $user = $user_data[0];
            
            if ($current_password === $user['password']) {
                if ($new_password === $confirm_password) {
                    // Update password (dalam implementasi nyata, gunakan password_hash)
                    $result = $db->edit_data_user($username, $new_password, $user['akses_id']);
                    
                    if ($result) {
                        $_SESSION['success'] = "Password berhasil diubah!";
                    } else {
                        $_SESSION['error'] = "Gagal mengubah password!";
                    }
                } else {
                    $_SESSION['error'] = "Konfirmasi password tidak sesuai!";
                }
            } else {
                $_SESSION['error'] = "Password lama tidak benar!";
            }
        } else {
            $_SESSION['error'] = "User tidak ditemukan!";
        }
    }
}

header("Location: index.php");
exit();
?>
