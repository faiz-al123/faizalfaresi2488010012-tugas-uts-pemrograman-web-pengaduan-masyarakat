<?php
// Mulai session untuk notifikasi
session_start();

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit;
}

// Panggil koneksi database
require_once 'config.php';

// ========== 1. Ambil data dari form ==========
$nama_pelapor = trim($_POST['nama_pelapor'] ?? '');
$email = trim($_POST['email'] ?? '');
$lokasi = trim($_POST['lokasi'] ?? '');
$isi_pengaduan = trim($_POST['isi_pengaduan'] ?? '');

// ========== 2. Validasi data wajib ==========
$errors = [];

if (empty($nama_pelapor)) {
    $errors[] = "Nama lengkap wajib diisi";
}
if (empty($email)) {
    $errors[] = "Email wajib diisi";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid";
}
if (empty($lokasi)) {
    $errors[] = "Lokasi kejadian wajib diisi";
}
if (empty($isi_pengaduan)) {
    $errors[] = "Isi pengaduan wajib diisi";
}

// Jika ada error, tampilkan
if (!empty($errors)) {
    echo "<h3>Terjadi kesalahan:</h3>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
    echo "<a href='form.php'>Kembali ke Form</a>";
    exit;
}

// ========== 3. Proses upload file (opsional) ==========
$nama_file = null;
$upload_dir = 'uploads/';

// Buat folder uploads jika belum ada
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['bukti']['tmp_name'];
    $file_size = $_FILES['bukti']['size'];
    $file_info = pathinfo($_FILES['bukti']['name']);
    $file_ext = strtolower($file_info['extension']);
    
    // Validasi ekstensi
    $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];
    if (!in_array($file_ext, $allowed_ext)) {
        die("Error: Format file tidak diizinkan. Gunakan JPG, JPEG, PNG, atau PDF.");
    }
    
    // Validasi ukuran (max 2MB)
    if ($file_size > 2 * 1024 * 1024) {
        die("Error: Ukuran file maksimal 2MB.");
    }
    
    // Buat nama file unik
    $nama_file = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['bukti']['name']);
    $tujuan = $upload_dir . $nama_file;
    
    if (!move_uploaded_file($file_tmp, $tujuan)) {
        die("Error: Gagal mengupload file.");
    }
}

// ========== 4. Generate kode tracking unik ==========
// Pastikan kode benar-benar unik di database
$kode_tracking = '';
do {
    $kode_tracking = 'TRK-' . strtoupper(substr(md5(uniqid() . time() . rand()), 0, 6));
    // Cek apakah kode sudah ada di database
    $cek = $koneksi->prepare("SELECT kode_tracking FROM laporan WHERE kode_tracking = ?");
    $cek->bind_param("s", $kode_tracking);
    $cek->execute();
    $cek->store_result();
    $ada = $cek->num_rows > 0;
    $cek->close();
} while ($ada);

// ========== 5. INSERT ke database ==========
$status = 'pending';
$sql = "INSERT INTO laporan (kode_tracking, nama_pelapor, email, lokasi, isi_pengaduan, bukti_file, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("sssssss", $kode_tracking, $nama_pelapor, $email, $lokasi, $isi_pengaduan, $nama_file, $status);

if ($stmt->execute()) {
    // Simpan kode tracking ke session untuk ditampilkan
    $_SESSION['tracking_sukses'] = $kode_tracking;
    
    // Redirect ke halaman sukses
    header("Location: sukses_pengaduan.php");
    exit;
} else {
    die("Error: Gagal menyimpan data. " . $stmt->error);
}

$stmt->close();
$koneksi->close();
?>