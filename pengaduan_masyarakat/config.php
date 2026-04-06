<?php
// Konfigurasi database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'db_pengaduan';

// Buat koneksi
$koneksi = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

// Set charset ke UTF-8
$koneksi->set_charset("utf8");

// Timezone Indonesia
date_default_timezone_set('Asia/Jakarta');
?>