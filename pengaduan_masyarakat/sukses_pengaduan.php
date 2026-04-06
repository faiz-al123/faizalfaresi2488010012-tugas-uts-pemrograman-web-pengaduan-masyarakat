<?php
session_start();

// Ambil kode tracking dari session
$kode_tracking = $_SESSION['tracking_sukses'] ?? null;

// Jika tidak ada kode tracking, redirect ke form
if (!$kode_tracking) {
    header('Location: form.php');
    exit;
}

// Hapus dari session agar tidak muncul lagi
unset($_SESSION['tracking_sukses']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Berhasil - SISPEK</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            font-size: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .nav-links a:hover {
            background: #34495e;
        }
        .container {
            max-width: 600px;
            margin: 80px auto;
            padding: 0 20px;
            flex: 1;
        }
        .success-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            padding: 40px;
        }
        .checkmark {
            font-size: 80px;
            color: #27ae60;
            margin-bottom: 20px;
        }
        .success-card h2 {
            color: #27ae60;
            margin-bottom: 20px;
        }
        .tracking-code {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .tracking-code .label {
            font-size: 14px;
            color: #666;
        }
        .tracking-code .code {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 2px;
            font-family: monospace;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px 5px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-primary {
            background: #3498db;
            color: white;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        footer {
            text-align: center;
            padding: 20px;
            background: #2c3e50;
            color: white;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📢 SISPEK - Sistem Pengaduan Masyarakat</h1>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="form.php">Buat Aduan</a>
            <a href="tracking.php">Tracking</a>
        </div>
    </div>

    <div class="container">
        <div class="success-card">
            <div class="checkmark">✓</div>
            <h2>Pengaduan Berhasil Dikirim!</h2>
            <p>Terima kasih, laporan Anda akan segera diproses oleh petugas.</p>
            
            <div class="tracking-code">
                <div class="label">Kode Tracking Anda:</div>
                <div class="code"><?php echo $kode_tracking; ?></div>
                <div class="label" style="margin-top: 10px;">Simpan kode ini untuk cek status laporan</div>
            </div>
            
            <a href="tracking.php" class="btn btn-primary">🔍 Cek Status Laporan</a>
            <a href="form.php" class="btn btn-success">📝 Buat Aduan Baru</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat</p>
    </footer>
</body>
</html>