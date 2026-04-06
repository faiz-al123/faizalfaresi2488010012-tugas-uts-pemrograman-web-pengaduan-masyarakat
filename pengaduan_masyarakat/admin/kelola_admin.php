<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Cek apakah level admin (hanya admin yang boleh akses)
if ($_SESSION['level'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Panggil koneksi database
require_once '../config.php';

// ========== Proses Hapus User ==========
if (isset($_GET['hapus']) && is_numeric($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    // Cegah menghapus diri sendiri
    if ($id_hapus == $_SESSION['user_id']) {
        $_SESSION['error'] = "Anda tidak dapat menghapus akun sendiri!";
    } else {
        $hapus = $koneksi->prepare("DELETE FROM users WHERE id_user = ?");
        $hapus->bind_param("i", $id_hapus);
        if ($hapus->execute()) {
            $_SESSION['success'] = "User berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus user!";
        }
        $hapus->close();
    }
    header('Location: kelola_admin.php');
    exit;
}

// ========== Ambil data users dengan search ==========
$search = $_GET['search'] ?? '';
$where = "";
$params = [];
$types = "";

if (!empty($search)) {
    $where = "WHERE username LIKE ? OR nama_lengkap LIKE ? OR email LIKE ?";
    $like = "%$search%";
    $params = [$like, $like, $like];
    $types = "sss";
}

$sql = "SELECT * FROM users $where ORDER BY created_at DESC";
$stmt = $koneksi->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin/Staf - SISPEK</title>
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
            flex-wrap: wrap;
        }
        .navbar h1 {
            font-size: 20px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .nav-links a:hover, .nav-links a.active {
            background: #34495e;
        }
        .btn-logout {
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .container {
            max-width: 1300px;
            margin: 30px auto;
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
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .card-header h2 {
            margin-bottom: 0;
        }
        .btn-add {
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .search-form input {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            width: 250px;
        }
        .search-form button {
            padding: 8px 20px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .card-body {
            padding: 25px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-admin {
            background: #e74c3c;
            color: white;
        }
        .badge-staff {
            background: #3498db;
            color: white;
        }
        .btn-edit {
            background: #f39c12;
            color: white;
            padding: 5px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            margin-right: 5px;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
        }
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 450px;
            max-width: 90%;
        }
        .modal-content h3 {
            margin-bottom: 20px;
        }
        .modal-content input, .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .btn-save {
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-cancel {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .info-password {
            font-size: 12px;
            color: #666;
            margin-top: -10px;
            margin-bottom: 15px;
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
        <div class="user-info">
            <span>👤 <?php echo $_SESSION['nama_lengkap']; ?> (Admin)</span>
            <div class="nav-links">
                <a href="dashboard.php">📋 Laporan</a>
                <a href="kelola_admin.php" class="active">👥 Kelola User</a>
                <a href="logout.php" class="btn-logout">🚪 Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>👥 Kelola Admin & Staf</h2>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <form method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Cari username, nama, atau email..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit">🔍 Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="kelola_admin.php" style="background: #e74c3c; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Reset</a>
                        <?php endif; ?>
                    </form>
                    <a href="registrasi.php" class="btn-add">+ Tambah User Baru</a>
                </div>
            </div>
            <div class="card-body">
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php if (count($users) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Level</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id_user']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge <?php echo $user['level'] == 'admin' ? 'badge-admin' : 'badge-staff'; ?>">
                                        <?php echo $user['level'] == 'admin' ? 'Admin' : 'Staff'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <button class="btn-edit" onclick="openEditModal(
                                        <?php echo $user['id_user']; ?>,
                                        '<?php echo htmlspecialchars($user['username']); ?>',
                                        '<?php echo htmlspecialchars($user['nama_lengkap']); ?>',
                                        '<?php echo htmlspecialchars($user['email']); ?>',
                                        '<?php echo $user['level']; ?>'
                                    )">Edit</button>
                                    <?php if ($user['id_user'] != $_SESSION['user_id']): ?>
                                        <a href="?hapus=<?php echo $user['id_user']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; padding: 40px;">Tidak ada data user.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>✏️ Edit User</h3>
            <form action="proses_edit_user.php" method="POST">
                <input type="hidden" name="id_user" id="edit_id">
                
                <label>Username</label>
                <input type="text" name="username" id="edit_username" required>
                
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="edit_nama" required>
                
                <label>Email</label>
                <input type="email" name="email" id="edit_email" required>
                
                <label>Level</label>
                <select name="level" id="edit_level">
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
                
                <label>Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                <div class="info-password">* Minimal 6 karakter jika diisi</div>
                
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, username, nama, email, level) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_level').value = level;
            document.getElementById('editModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                closeModal();
            }
        }
    </script>

    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat</p>
    </footer>
</body>
</html>