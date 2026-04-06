<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Panggil koneksi database
require_once '../config.php';

$id_laporan = $_GET['id'] ?? 0;

// Ambil detail laporan
$sql = "SELECT * FROM laporan WHERE id_laporan = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_laporan);
$stmt->execute();
$result = $stmt->get_result();
$laporan = $result->fetch_assoc();
$stmt->close();

if (!$laporan) {
    header('Location: dashboard.php');
    exit;
}

$status_label = [
    'pending' => ['label' => 'Pending', 'color' => '#f39c12', 'icon' => '⏳'],
    'proses' => ['label' => 'Diproses', 'color' => '#3498db', 'icon' => '🔄'],
    'selesai' => ['label' => 'Selesai', 'color' => '#27ae60', 'icon' => '✅']
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - SISPEK</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
        }
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background: #3498db;
            color: white;
            padding: 20px 30px;
        }
        .card-body {
            padding: 30px;
        }
        .info-group {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-value {
            font-size: 18px;
            color: #333;
            margin-top: 8px;
            word-wrap: break-word;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: bold;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-back:hover {
            background: #2980b9;
        }
        .bukti-img {
            max-width: 100%;
            max-height: 300px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .btn-download {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 20px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
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
        <h1>📢 SISPEK - Detail Laporan</h1>
        <div style="color: white;"><?php echo $_SESSION['nama_lengkap']; ?> (<?php echo $_SESSION['level']; ?>)</div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>📋 Detail Pengaduan</h2>
                <p>Kode Tracking: <strong><?php echo htmlspecialchars($laporan['kode_tracking']); ?></strong></p>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <div class="info-label">Nama Pelapor</div>
                    <div class="info-value"><?php echo htmlspecialchars($laporan['nama_pelapor']); ?></div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($laporan['email']); ?></div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Lokasi Kejadian</div>
                    <div class="info-value">📍 <?php echo nl2br(htmlspecialchars($laporan['lokasi'])); ?></div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Isi Pengaduan</div>
                    <div class="info-value"><?php echo nl2br(htmlspecialchars($laporan['isi_pengaduan'])); ?></div>
                </div>
                
                <?php if (!empty($laporan['bukti_file'])): ?>
                <div class="info-group">
                    <div class="info-label">Bukti Pendukung</div>
                    <div class="info-value">
                        <?php 
                        $file_ext = pathinfo($laporan['bukti_file'], PATHINFO_EXTENSION);
                        if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png'])):
                        ?>
                            <img src="../uploads/<?php echo htmlspecialchars($laporan['bukti_file']); ?>" class="bukti-img">
                        <?php else: ?>
                            <a href="../uploads/<?php echo htmlspecialchars($laporan['bukti_file']); ?>" class="btn-download" download>📎 Download Bukti (PDF)</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="info-group">
                    <div class="info-label">Status Laporan</div>
                    <div class="info-value">
                        <span class="status-badge" style="background: <?php echo $status_label[$laporan['status']]['color']; ?>; color: white;">
                            <?php echo $status_label[$laporan['status']]['icon']; ?> <?php echo $status_label[$laporan['status']]['label']; ?>
                        </span>
                    </div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Tanggal Laporan</div>
                    <div class="info-value">📅 <?php echo date('d F Y H:i:s', strtotime($laporan['tgl_input'])); ?></div>
                </div>
                
                <a href="dashboard.php" class="btn-back">← Kembali ke Dashboard</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat</p>
    </footer>
</body>
</html>