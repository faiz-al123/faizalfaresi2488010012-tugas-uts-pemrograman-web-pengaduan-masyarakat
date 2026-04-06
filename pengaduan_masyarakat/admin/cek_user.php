<?php
require_once '../config.php';

$username = 'admin';
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo "<pre>";
print_r($user);
echo "</pre>";

// Cek password 'admin123' dengan hash yang ada
if ($user) {
    if (password_verify('admin123', $user['password'])) {
        echo "Password cocok!";
    } else {
        echo "Password tidak cocok!";
    }
}
?>