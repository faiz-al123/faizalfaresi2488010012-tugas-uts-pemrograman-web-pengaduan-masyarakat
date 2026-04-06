<?php
session_start();

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registrasi.php');
    exit;
}

// Panggil koneksi database
require_once '../config.php';

// ========== 1. Ambil data dari form ==========
$username = trim($_POST['username'] ?? '');
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$email = trim($_POST['email'] ?? '');
$level = trim($_POST['level'] ?? 'staff');
$password = $_POST['password'] ?? '';
$konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

// ========== 2. Validasi data ==========
$errors = [];

// Validasi username
if (empty($username)) {
    $errors[] = "Username wajib diisi";
} elseif (strlen($username) < 3) {
    $errors[] = "Username minimal 3 karakter";
}

// Validasi nama lengkap
if (empty($nama_lengkap)) {
    $errors[] = "Nama lengkap wajib diisi";
}

// Validasi email
if (empty($email)) {
    $errors[] = "Email wajib diisi";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid";
}

// Validasi level
if (!in_array($level, ['admin', 'staff'])) {
    $errors[] = "Level tidak valid";
}

// Validasi password
if (empty($password)) {
    $errors[] = "Password wajib diisi";
} elseif (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter";
}

// Validasi konfirmasi password
if ($password !== $konfirmasi_password) {
    $errors[] = "Konfirmasi password tidak cocok";
}

// Jika ada error, simpan ke session dan redirect back
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header('Location: registrasi.php');
    exit;
}

// ========== 3. Cek apakah username atau email sudah terdaftar ==========
$cek = $koneksi->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
$cek->bind_param("ss", $username, $email);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
    $_SESSION['error'] = "Username atau Email sudah terdaftar!";
    $cek->close();
    header('Location: registrasi.php');
    exit;
}
$cek->close();

// ========== 4. Hash password ==========
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ========== 5. INSERT ke database ==========
$sql = "INSERT INTO users (username, password, nama_lengkap, email, level) VALUES (?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("sssss", $username, $hashed_password, $nama_lengkap, $email, $level);

if ($stmt->execute()) {
    $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
    header('Location: login.php');
    exit;
} else {
    $_SESSION['error'] = "Registrasi gagal: " . $stmt->error;
    header('Location: registrasi.php');
    exit;
}

$stmt->close();
$koneksi->close();
?>