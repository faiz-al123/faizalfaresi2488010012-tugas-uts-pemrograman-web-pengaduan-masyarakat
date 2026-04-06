<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Panggil koneksi database
require_once '../config.php';

// Ambil ID laporan dari URL
$id_laporan = $_GET['id'] ?? 0;

if ($id_laporan <= 0) {
    $_SESSION['error'] = "ID laporan tidak valid!";
    header('Location: dashboard.php');
    exit;
}

// Ambil informasi file bukti sebelum menghapus (untuk hapus file fisik)
$query_file = $koneksi->prepare("SELECT bukti_file FROM laporan WHERE id_laporan = ?");
$query_file->bind_param("i", $id_laporan);
$query_file->execute();
$result_file = $query_file->get_result();
$laporan = $result_file->fetch_assoc();
$query_file->close();

// Hapus laporan dari database
$sql = "DELETE FROM laporan WHERE id_laporan = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_laporan);

if ($stmt->execute()) {
    // Jika ada file bukti, hapus juga file fisiknya
    if (!empty($laporan['bukti_file'])) {
        $file_path = "../uploads/" . $laporan['bukti_file'];
        if (file_exists($file_path)) {
            unlink($file_path); // Hapus file
        }
    }
    $_SESSION['success'] = "Laporan berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus laporan: " . $stmt->error;
}

$stmt->close();
$koneksi->close();

header('Location: dashboard.php');
exit;
?>