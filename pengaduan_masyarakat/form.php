<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Aduan - SISPEK</title>
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
        .form-group label .required {
            color: #e74c3c;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .btn-submit {
            background: #27ae60;
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #219a52;
        }
        .info-file {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
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
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
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
                <h2>📝 Form Pengaduan Masyarakat</h2>
                <p>Isi data dengan lengkap dan benar agar aduan dapat segera diproses</p>
            </div>
            <div class="card-body">
                <form action="proses_pengaduan.php" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="nama_pelapor" required placeholder="Masukkan nama lengkap Anda">
                        </div>
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" name="email" required placeholder="contoh: email@domain.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Lokasi Kejadian <span class="required">*</span></label>
                        <input type="text" name="lokasi" required placeholder="Contoh: Jl. Merdeka No. 10, RT 01/RW 02, Kel. Sukamaju, Kec. Senen, Jakarta Pusat">
                        <div class="info-file">⚠️ Cantumkan alamat lengkap atau titik lokasi kejadian</div>
                    </div>

                    <div class="form-group">
                        <label>Isi Pengaduan <span class="required">*</span></label>
                        <textarea name="isi_pengaduan" required placeholder="Jelaskan keluhan, kritik, atau saran Anda secara detail..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Upload Bukti (Foto/Dokumen)</label>
                        <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf">
                        <div class="info-file">📎 Format: JPG, JPEG, PNG, PDF | Maksimal 2MB</div>
                        <div class="info-file">💡 Bukti tidak wajib, tetapi sangat membantu proses verifikasi</div>
                    </div>

                    <button type="submit" class="btn-submit">🚀 Kirim Pengaduan</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat | Layanan Aspirasi 24 Jam</p>
    </footer>
</body>
</html>