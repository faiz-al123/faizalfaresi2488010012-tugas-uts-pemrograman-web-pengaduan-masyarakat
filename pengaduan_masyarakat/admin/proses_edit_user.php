<?php
session_start();

// Cek apakah sudah login dan admin
if (!isset($_SESSION['user_id']) || $_SESSION['level'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelola_admin.php');
    exit;
}

// Panggil koneksi database
require_once '../config.php';

// Ambil data dari form
$id_user = $_POST['id_user'] ?? 0;
$username = trim($_POST['username'] ?? '');
$nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
$email = trim($_POST['email'] ?? '');
$level = $_POST['level'] ?? 'staff';
$password_baru = $_POST['password'] ?? '';

// Validasi data
$errors = [];

if (empty($username)) {
    $errors[] = "Username wajib diisi";
} elseif (strlen($username) < 3) {
    $errors[] = "Username minimal 3 karakter";
}

if (empty($nama_lengkap)) {
    $errors[] = "Nama lengkap wajib diisi";
}

if (empty($email)) {
    $errors[] = "Email wajib diisi";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid";
}

if (!in_array($level, ['admin', 'staff'])) {
    $errors[] = "Level tidak valid";
}

if (!empty($password_baru) && strlen($password_baru) < 6) {
    $errors[] = "Password minimal 6 karakter";
}

// Cek duplikat username/email (kecuali untuk user ini sendiri)
$cek = $koneksi->prepare("SELECT id_user FROM users WHERE (username = ? OR email = ?) AND id_user != ?");
$cek->bind_param("ssi", $username, $email, $id_user);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
    $errors[] = "Username atau Email sudah digunakan oleh user lain!";
}
$cek->close();

// Jika ada error
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header('Location: kelola_admin.php');
    exit;
}

// Build query update
$sql = "UPDATE users SET username = ?, nama_lengkap = ?, email = ?, level = ?";
$params = [$username, $nama_lengkap, $email, $level];
$types = "ssss";

if (!empty($password_baru)) {
    $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
    $sql .= ", password = ?";
    $params[] = $hashed_password;
    $types .= "s";
}

$sql .= " WHERE id_user = ?";
$params[] = $id_user;
$types .= "i";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    $_SESSION['success'] = "User berhasil diupdate!";
} else {
    $_SESSION['error'] = "Gagal mengupdate user: " . $stmt->error;
}

$stmt->close();
$koneksi->close();

header('Location: kelola_admin.php');
exit;
?>