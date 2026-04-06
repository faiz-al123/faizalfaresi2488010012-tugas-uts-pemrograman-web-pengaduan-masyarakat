<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Admin/Staf - SISPEK</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 0 20px;
            flex: 1;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .card-header {
            background: #2c3e50;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        .card-header h2 {
            margin-bottom: 5px;
        }
        .card-header p {
            opacity: 0.8;
            font-size: 14px;
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
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-register {
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
        .btn-register:hover {
            background: #219a52;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .login-link a {
            color: #3498db;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .info-level {
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
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>📝 Registrasi Akun</h2>
                <p>Daftar sebagai Admin atau Staf SISPEK</p>
            </div>
            <div class="card-body">
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <form action="proses_registrasi.php" method="POST">
                    <div class="form-group">
                        <label>Username <span class="required">*</span></label>
                        <input type="text" name="username" required placeholder="Minimal 3 karakter">
                    </div>
                    
                    <div class="form-group">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama_lengkap" required placeholder="Masukkan nama lengkap">
                    </div>
                    
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" required placeholder="contoh: email@domain.com">
                    </div>
                    
                    <div class="form-group">
                        <label>Level <span class="required">*</span></label>
                        <select name="level" required>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                        <div class="info-level">
                            💡 <strong>Staff:</strong> Hanya bisa mengelola laporan<br>
                            💡 <strong>Admin:</strong> Bisa mengelola laporan & mengelola user lain
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter">
                    </div>
                    
                    <div class="form-group">
                        <label>Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" name="konfirmasi_password" required placeholder="Ulangi password">
                    </div>
                    
                    <button type="submit" class="btn-register">🚀 Daftar Sekarang</button>
                </form>
                
                <div class="login-link">
                    Sudah punya akun? <a href="login.php">Login di sini</a>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat</p>
    </footer>
</body>
</html>