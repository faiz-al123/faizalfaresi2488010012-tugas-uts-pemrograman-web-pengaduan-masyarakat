<?php
// File ini hanya untuk setup awal. HAPUS setelah digunakan!

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'db_pengaduan';

// Koneksi tanpa database dulu
$koneksi = new mysqli($host, $user, $password);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Hapus database jika ada
$koneksi->query("DROP DATABASE IF EXISTS `$database`");
echo "✅ Database lama dihapus<br>";

// Buat database baru
$koneksi->query("CREATE DATABASE `$database`");
echo "✅ Database baru dibuat<br>";

// Pilih database
$koneksi->select_db($database);

// ========== Buat Tabel users ==========
$sql_users = "CREATE TABLE `users` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `level` ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$koneksi->query($sql_users);
echo "✅ Tabel users dibuat<br>";

// ========== Buat Tabel laporan ==========
$sql_laporan = "CREATE TABLE `laporan` (
  `id_laporan` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_tracking` VARCHAR(20) NOT NULL,
  `nama_pelapor` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `lokasi` TEXT NOT NULL,
  `isi_pengaduan` TEXT NOT NULL,
  `bukti_file` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('pending', 'proses', 'selesai') NOT NULL DEFAULT 'pending',
  `tgl_input` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_laporan`),
  UNIQUE KEY `kode_tracking` (`kode_tracking`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$koneksi->query($sql_laporan);
echo "✅ Tabel laporan dibuat<br>";

// ========== Buat user faiz dengan password faiz123 ==========
$username = 'faiz';
$password_plain = 'faiz123';
$nama_lengkap = 'Faiz Administrator';
$email = 'faiz@sispek.com';
$level = 'admin';

// Generate hash yang BENAR
$hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

$sql_insert = "INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `email`, `level`) 
               VALUES ('$username', '$hashed_password', '$nama_lengkap', '$email', '$level')";

if ($koneksi->query($sql_insert)) {
    echo "✅ User '<strong>$username</strong>' dengan password '<strong>$password_plain</strong>' berhasil dibuat!<br>";
    echo "Hash password: <code>$hashed_password</code><br>";
} else {
    echo "❌ Gagal membuat user: " . $koneksi->error . "<br>";
}

// ========== Selesai ==========
echo "<hr>";
echo "<h3>🎉 Setup Selesai!</h3>";
echo "<p>Sekarang Anda bisa login dengan:</p>";
echo "<ul>";
echo "<li><strong>Username:</strong> faiz</li>";
echo "<li><strong>Password:</strong> faiz123</li>";
echo "</ul>";
echo "<a href='admin/login.php' style='background:#3498db; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>🔐 Login Sekarang</a>";
echo "<br><br>";
echo "<div style='background:#f8d7da; color:#721c24; padding:10px; border-radius:5px;'>";
echo "⚠️ <strong>Keamanan:</strong> Setelah berhasil login, <strong>HAPUS file setup_db.php</strong> ini!";
echo "</div>";

$koneksi->close();
?>