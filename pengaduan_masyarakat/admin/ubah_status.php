<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

// Panggil koneksi database
require_once '../config.php';

// Ambil data dari form
$id_laporan = $_POST['id_laporan'] ?? 0;
$status = $_POST['status'] ?? '';

// Validasi status
$allowed_status = ['pending', 'proses', 'selesai'];
if (!in_array($status, $allowed_status)) {
    $_SESSION['error'] = "Status tidak valid!";
    header('Location: dashboard.php');
    exit;
}

// Update status laporan
$sql = "UPDATE laporan SET status = ? WHERE id_laporan = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("si", $status, $id_laporan);

if ($stmt->execute()) {
    $_SESSION['success'] = "Status laporan berhasil diubah menjadi " . ucfirst($status);
} else {
    $_SESSION['error'] = "Gagal mengubah status laporan!";
}

$stmt->close();
$koneksi->close();

header('Location: dashboard.php');
exit;
?>