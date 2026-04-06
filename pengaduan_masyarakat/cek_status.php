<?php
// Panggil koneksi database
require_once 'config.php';

// Ambil kode tracking dari URL
$kode_tracking = trim($_GET['kode'] ?? '');

// Jika tidak ada kode, redirect ke tracking.php
if (empty($kode_tracking)) {
    header('Location: tracking.php');
    exit;
}

// Cari data laporan dari database
$sql = "SELECT * FROM laporan WHERE kode_tracking = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $kode_tracking);
$stmt->execute();
$result = $stmt->get_result();
$laporan = $result->fetch_assoc();
$stmt->close();
$koneksi->close();

// Status badge configuration
$status_badge = [
    'pending' => ['label' => 'Pending', 'color' => '#f39c12', 'icon' => '⏳', 'text' => 'Laporan Anda sedang menunggu diproses oleh petugas.'],
    'proses'  => ['label' => 'Diproses', 'color' => '#3498db', 'icon' => '🔄', 'text' => 'Laporan Anda sedang dalam penanganan oleh petugas.'],
    'selesai' => ['label' => 'Selesai', 'color' => '#27ae60', 'icon' => '✅', 'text' => 'Laporan Anda telah selesai diproses.']
];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tracking - SISPEK</title>
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
            flex-wrap: wrap;
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
            max-width: 800px;
            margin: 50px auto;
            padding: 0 20px;
            flex: 1;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
        }
        .card-header h2 {
            margin-bottom: 5px;
        }
        .card-body {
            padding: 30px;
        }
        .not-found {
            text-align: center;
            padding: 40px;
        }
        .not-found .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .not-found h3 {
            color: #e74c3c;
            margin-bottom: 10px;
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
        .info-laporan {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .info-laporan .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-laporan .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
            word-wrap: break-word;
        }
        .status-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .status-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .status-label {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .status-text {
            color: #666;
        }
        .bukti-link {
            margin-top: 20px;
        }
        .btn-download {
            display: inline-block;
            padding: 8px 20px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
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
        <div class="card">
            <div class="card-header">
                <h2>📋 Hasil Tracking Laporan</h2>
                <p>Kode: <?php echo htmlspecialchars($kode_tracking); ?></p>
            </div>
            <div class="card-body">
                
                <?php if (!$laporan): ?>
                    <!-- Laporan tidak ditemukan -->
                    <div class="not-found">
                        <div class="icon">🔍</div>
                        <h3>Laporan Tidak Ditemukan</h3>
                        <p>Maaf, kode tracking <strong><?php echo htmlspecialchars($kode_tracking); ?></strong> tidak ditemukan.</p>
                        <p>Pastikan kode yang Anda masukkan sudah benar.</p>
                        <a href="tracking.php" class="btn-back">← Kembali ke Tracking</a>
                    </div>
                <?php else: ?>
                    <!-- Laporan ditemukan -->
                    <div class="info-laporan">
                        <div class="label">Nama Pelapor</div>
                        <div class="value"><?php echo htmlspecialchars($laporan['nama_pelapor']); ?></div>
                    </div>
                    
                    <div class="info-laporan">
                        <div class="label">Email</div>
                        <div class="value"><?php echo htmlspecialchars($laporan['email']); ?></div>
                    </div>
                    
                    <div class="info-laporan">
                        <div class="label">Lokasi Kejadian</div>
                        <div class="value">📍 <?php echo htmlspecialchars($laporan['lokasi']); ?></div>
                    </div>
                    
                    <div class="info-laporan">
                        <div class="label">Isi Pengaduan</div>
                        <div class="value"><?php echo nl2br(htmlspecialchars($laporan['isi_pengaduan'])); ?></div>
                    </div>
                    
                    <?php if (!empty($laporan['bukti_file'])): ?>
                        <div class="info-laporan">
                            <div class="label">Bukti Pendukung</div>
                            <div class="value bukti-link">
                                <a href="uploads/<?php echo htmlspecialchars($laporan['bukti_file']); ?>" class="btn-download" target="_blank">📎 Lihat Bukti</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-laporan">
                        <div class="label">Tanggal Laporan</div>
                        <div class="value">📅 <?php echo date('d/m/Y H:i:s', strtotime($laporan['tgl_input'])); ?></div>
                    </div>
                    
                    <!-- Status Laporan -->
                    <?php 
                        $status = $laporan['status'];
                        $badge = $status_badge[$status] ?? $status_badge['pending'];
                    ?>
                    <div class="status-card">
                        <div class="status-icon"><?php echo $badge['icon']; ?></div>
                        <div class="status-label" style="color: <?php echo $badge['color']; ?>">
                            <?php echo $badge['label']; ?>
                        </div>
                        <div class="status-text"><?php echo $badge['text']; ?></div>
                    </div>
                    
                    <a href="tracking.php" class="btn-back">🔍 Cek Kode Lain</a>
                    
                <?php endif; ?>
                
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat</p>
    </footer>
</body>
</html>