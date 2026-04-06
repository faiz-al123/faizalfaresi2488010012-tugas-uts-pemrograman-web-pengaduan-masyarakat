<?php
session_start();

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Panggil koneksi database
require_once '../config.php';

// ========== 1. Ambil data dari form ==========
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$captcha = trim($_POST['captcha'] ?? '');

// ========== 2. Validasi CAPTCHA ==========
if (!isset($_SESSION['captcha']) || $captcha != $_SESSION['captcha']) {
    $_SESSION['error'] = "CAPTCHA salah! Silakan coba lagi.";
    header('Location: login.php');
    exit;
}

// Hapus captcha setelah digunakan
unset($_SESSION['captcha']);

// ========== 3. Validasi username & password ==========
if (empty($username)) {
    $_SESSION['error'] = "Username wajib diisi";
    header('Location: login.php');
    exit;
}

if (empty($password)) {
    $_SESSION['error'] = "Password wajib diisi";
    header('Location: login.php');
    exit;
}

// ========== 4. Cari user di database ==========
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// ========== 5. Verifikasi password ==========
if ($user && password_verify($password, $user['password'])) {
    // Login berhasil
    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
    $_SESSION['level'] = $user['level'];
    $_SESSION['login_time'] = time();
    
    // Redirect ke dashboard
    header('Location: dashboard.php');
    exit;
} else {
    // Login gagal
    $_SESSION['error'] = "Username atau password salah!";
    header('Location: login.php');
    exit;
}

$koneksi->close();
?>