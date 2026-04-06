<?php
session_start();

// Generate CAPTCHA angka random
$angka1 = rand(1, 9);
$angka2 = rand(1, 99);
$hasil_captcha = $angka1 + $angka2;

// Simpan hasil captcha ke session
$_SESSION['captcha'] = $hasil_captcha;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin/Staf - SISPEK</title>
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
            max-width: 450px;
            margin: 80px auto;
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
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        .captcha-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
        .captcha-question {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 5px;
        }
        .btn-login {
            background: #3498db;
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
        .btn-login:hover {
            background: #2980b9;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .register-link a {
            color: #27ae60;
            text-decoration: none;
        }
        .register-link a:hover {
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
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            color: #666;
            text-decoration: none;
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
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>🔐 Login Admin / Staf</h2>
               
                <p>Masukkan username dan password Anda</p>
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
                
                <form action="proses_login.php" method="POST">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Kerjain Soal Matematika Dulu, Nih Soalnya</label>
                        <div class="captcha-box">
                            <span class="captcha-question"><?php echo $angka1; ?> + <?php echo $angka2; ?> = ?</span>
                        </div>
                        <input type="number" name="captcha" required placeholder="Masukkan hasil penjumlahan">
                    </div>
                    
                    <button type="submit" class="btn-login">🚀 Login</button>
                </form>
                
                <div class="register-link">
                    <!--Belum punya akun? <a href="registrasi.php">Daftar di sini</a>-->
                </div>
                
                <div class="back-link">
                    <a href="../index.php">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat</p>
    </footer>
</body>
</html>