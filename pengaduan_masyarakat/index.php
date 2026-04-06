<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISPEK - Sistem Pengaduan Masyarakat</title>
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
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: #34495e;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            flex: 1;
        }
        .hero {
            text-align: center;
            padding: 60px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            color: white;
        }
        .hero h2 {
            font-size: 32px;
            margin-bottom: 20px;
        }
        .hero p {
            margin-bottom: 30px;
            font-size: 18px;
            opacity: 0.95;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .btn-primary {
            background: #3498db;
            color: white;
        }
        .btn-success {
            background: #27ae60;
            color: white;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            margin-top: 40px;
            gap: 20px;
        }
        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .feature-card h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .feature-card p {
            color: #666;
            line-height: 1.6;
        }
        .feature-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #3498db;
        }
        .stat-label {
            color: #666;
            margin-top: 10px;
        }
        footer {
            text-align: center;
            padding: 20px;
            background: #2c3e50;
            color: white;
            margin-top: 50px;
        }
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 10px;
            }
            .nav-links a {
                margin: 0 10px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📢 SISPEK - Sistem Pengaduan Masyarakat</h1>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="form.php">📝 Buat Aduan</a>
            <a href="tracking.php">🔍 Tracking</a>
            <a href="admin/login.php">👤 Admin/Staf</a>
        </div>
    </div>

    <div class="container">
        <div class="hero">
            <h2>Sampaikan Aspirasi Anda</h2>
            <p>Laporkan keluhan, kritik, atau saran dengan mudah. Sertakan lokasi dan bukti untuk penanganan lebih cepat.</p>
            <a href="form.php" class="btn btn-primary">📝 Buat Aduan Sekarang</a>
            <a href="tracking.php" class="btn btn-success">🔍 Cek Status Laporan</a>
        </div>

        <div class="features">
            <div class="feature-card">
                <div class="feature-icon">📍</div>
                <h3>Lokasi Wajib</h3>
                <p>Cantumkan lokasi kejadian agar aduan dapat ditindaklanjuti dengan tepat oleh petugas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🖼️</div>
                <h3>Upload Bukti</h3>
                <p>Lampirkan foto atau dokumen pendukung (max 2MB, format JPG/PNG/PDF).</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔎</div>
                <h3>Tracking Real-time</h3>
                <p>Pantau status laporan Anda dengan kode unik yang dikirim setelah pengaduan.</p>
            </div>
            <!--<div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Ditangani Staf Profesional</h3>
                <p>Tim kami akan memproses dan menindaklanjuti setiap laporan yang masuk.</p>
            </div>-->
        </div>

        
    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat | Layanan Aspirasi 24 Jam</p>
        <p style="font-size: 12px; margin-top: 5px;">Dikelola oleh Admin & Staf terlatih</p>
    </footer>
</body>
</html>