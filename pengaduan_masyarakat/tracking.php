<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Laporan - SISPEK</title>
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
            max-width: 700px;
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
        .card-header p {
            opacity: 0.9;
        }
        .card-body {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 14px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            font-family: monospace;
            letter-spacing: 1px;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-submit {
            background: #3498db;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #2980b9;
        }
        .info {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            color: #2c3e50;
        }
        .info h4 {
            margin-bottom: 10px;
        }
        .info ul {
            margin-left: 20px;
        }
        .info li {
            margin: 5px 0;
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
        <div class="card">
            <div class="card-header">
                <h2>🔍 Tracking Laporan</h2>
                <p>Masukkan kode tracking untuk melihat status pengaduan Anda</p>
            </div>
            <div class="card-body">
                <form action="cek_status.php" method="GET">
                    <div class="form-group">
                        <label>Kode Tracking</label>
                        <input type="text" name="kode" required placeholder="Contoh: TRK-ABC123" autocomplete="off">
                    </div>
                    <button type="submit" class="btn-submit">🔎 Cek Status</button>
                </form>
                
                <div class="info">
                    <h4>📌 Informasi</h4>
                    <ul>
                        <li>Kode tracking didapatkan setelah berhasil mengirim pengaduan</li>
                        <li>Format kode: <strong>TRK-XXXXXX</strong> (6 karakter acak)</li>
                        <li>Simpan baik-baik kode tracking Anda</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat | Layanan Aspirasi 24 Jam</p>
    </footer>
</body>
</html>