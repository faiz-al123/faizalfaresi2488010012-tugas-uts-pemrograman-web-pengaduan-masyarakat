<?php
require_once '../config.php';

// Data yang dicoba
$username = 'faiz';
$password = 'faiz123';

echo "<h3>Debug Login</h3>";

// Cari user di database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "❌ User '<strong>$username</strong>' tidak ditemukan di database!<br>";
    echo "Silakan buat user terlebih dahulu.";
} else {
    echo "✅ User ditemukan!<br>";
    echo "<pre>";
    print_r([
        'id_user' => $user['id_user'],
        'username' => $user['username'],
        'nama_lengkap' => $user['nama_lengkap'],
        'email' => $user['email'],
        'level' => $user['level'],
        'password_hash' => $user['password']
    ]);
    echo "</pre>";
    
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        echo "✅ Password '<strong>$password</strong>' <span style='color:green'>COCOK</span> dengan hash!<br>";
        echo "<br>🎉 <strong>Login seharusnya BERHASIL!</strong>";
    } else {
        echo "❌ Password '<strong>$password</strong>' <span style='color:red'>TIDAK COCOK</span> dengan hash!<br>";
        
        // Generate hash baru untuk password tersebut
        $new_hash = password_hash($password, PASSWORD_DEFAULT);
        echo "<br>📝 Hash yang benar untuk password '$password' adalah:<br>";
        echo "<code style='background:#f0f0f0;padding:10px;display:block;word-break:break-all;'>$new_hash</code><br>";
        echo "<br>🔧 Jalankan SQL ini untuk memperbaiki:<br>";
        echo "<code style='background:#f0f0f0;padding:10px;display:block;word-break:break-all;'>";
        echo "UPDATE users SET password = '$new_hash' WHERE username = '$username';";
        echo "</code>";
    }
}

$stmt->close();
$koneksi->close();
?>