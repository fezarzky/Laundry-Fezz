<?php
// Session sudah dimulai di routes/web.php
include __DIR__ . '/../../routes/config.php';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    
    if ($action == 'edit_role') {
        $username = $_POST['username'];
        $akses_id = $_POST['akses_id'];
        
        // Get current user data to preserve password
        $current_user = $db->login($username);
        if (!empty($current_user)) {
            $password = $current_user[0]['password'];
            $result = $db->edit_data_user($username, $password, $akses_id);
            
            if ($result) {
                $_SESSION['success'] = "Role user berhasil diupdate!";
            } else {
                $_SESSION['error'] = "Gagal mengupdate role user!";
            }
        } else {
            $_SESSION['error'] = "User tidak ditemukan!";
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $action = $_GET['action'];
    
    if ($action == 'hapus' && isset($_GET['username'])) {
        $username = $_GET['username'];
        
        // Prevent deleting admin or current user
        if ($username == 'admin' || $username == $_SESSION['username']) {
            $_SESSION['error'] = "Tidak dapat menghapus user admin atau user yang sedang login!";
        } else {
            $result = $db->hapus_data_user($username);
            
            if ($result) {
                $_SESSION['success'] = "User berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus user!";
            }
        }
    }
}

header("Location: index.php");
exit();
?>
