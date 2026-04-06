<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Panggil koneksi database
require_once '../config.php';

// Ambil data user yang login
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$nama_lengkap = $_SESSION['nama_lengkap'];
$level = $_SESSION['level'];

// Filter status (untuk menampilkan laporan berdasarkan status)
$filter_status = $_GET['status'] ?? 'semua';
$search = $_GET['search'] ?? '';

// Query untuk mengambil laporan dengan filter dan search
$sql = "SELECT * FROM laporan WHERE 1=1";
$params = [];
$types = "";

if ($filter_status !== 'semua') {
    $sql .= " AND status = ?";
    $params[] = $filter_status;
    $types .= "s";
}

if (!empty($search)) {
    $sql .= " AND (nama_pelapor LIKE ? OR kode_tracking LIKE ? OR lokasi LIKE ?)";
    $like_search = "%$search%";
    $params[] = $like_search;
    $params[] = $like_search;
    $params[] = $like_search;
    $types .= "sss";
}

$sql .= " ORDER BY tgl_input DESC";

$stmt = $koneksi->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$laporan_list = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Hitung statistik
$stat_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) as proses,
    SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
    FROM laporan";
$stat_result = $koneksi->query($stat_query);
$stats = $stat_result->fetch_assoc();

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SISPEK</title>
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
            flex-wrap: wrap;
        }
        .user-info span {
            background: #34495e;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
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
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .stat-card.active {
            border: 2px solid #3498db;
            background: #e8f4fd;
        }
        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #3498db;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
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
        .search-form {
            display: flex;
            gap: 10px;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-pending {
            background: #f39c12;
            color: white;
        }
        .badge-proses {
            background: #3498db;
            color: white;
        }
        .badge-selesai {
            background: #27ae60;
            color: white;
        }
        .btn-status {
            background: #2c3e50;
            color: white;
            padding: 5px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-status:hover {
            background: #1a252f;
        }
        .btn-detail {
            background: #3498db;
            color: white;
            padding: 5px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            display: inline-block;
        }
        .btn-detail:hover {
            background: #2980b9;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 5px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 5px;
        }
        .btn-delete:hover {
            background: #c0392b;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
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
            .user-info {
                justify-content: center;
            }
            table {
                font-size: 12px;
            }
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📢 SISPEK - Dashboard</h1>
        <div class="user-info">
            <span>👤 <?php echo htmlspecialchars($nama_lengkap); ?> (<?php echo $level == 'admin' ? 'Admin' : 'Staff'; ?>)</span>
            <div class="nav-links">
                <a href="dashboard.php" class="active">📋 Laporan</a>
                <?php if ($level == 'admin'): ?>
                    <a href="kelola_admin.php">👥 Kelola User</a>
                <?php endif; ?>
                <a href="logout.php" class="btn-logout">🚪 Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Notifikasi -->
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

        <!-- Statistik -->
        <div class="stats">
            <div class="stat-card <?php echo $filter_status == 'semua' ? 'active' : ''; ?>" onclick="window.location.href='?status=semua&search=<?php echo urlencode($search); ?>'">
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Semua Laporan</div>
            </div>
            <div class="stat-card <?php echo $filter_status == 'pending' ? 'active' : ''; ?>" onclick="window.location.href='?status=pending&search=<?php echo urlencode($search); ?>'">
                <div class="stat-number"><?php echo $stats['pending']; ?></div>
                <div class="stat-label">⏳ Pending</div>
            </div>
            <div class="stat-card <?php echo $filter_status == 'proses' ? 'active' : ''; ?>" onclick="window.location.href='?status=proses&search=<?php echo urlencode($search); ?>'">
                <div class="stat-number"><?php echo $stats['proses']; ?></div>
                <div class="stat-label">🔄 Diproses</div>
            </div>
            <div class="stat-card <?php echo $filter_status == 'selesai' ? 'active' : ''; ?>" onclick="window.location.href='?status=selesai&search=<?php echo urlencode($search); ?>'">
                <div class="stat-number"><?php echo $stats['selesai']; ?></div>
                <div class="stat-label">✅ Selesai</div>
            </div>
        </div>

        <!-- Daftar Laporan -->
        <div class="card">
            <div class="card-header">
                <h2>📋 Daftar Pengaduan Masyarakat</h2>
                <form method="GET" class="search-form">
                    <input type="hidden" name="status" value="<?php echo $filter_status; ?>">
                    <input type="text" name="search" placeholder="Cari nama, kode tracking, atau lokasi..." value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">🔍 Cari</button>
                    <?php if (!empty($search)): ?>
                        <a href="?status=<?php echo $filter_status; ?>" style="background: #e74c3c; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none;">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            <div style="overflow-x: auto;">
                <?php if (count($laporan_list) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Kode Tracking</th>
                                <th>Nama Pelapor</th>
                                <th>Lokasi</th>
                                <th>Isi Pengaduan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($laporan_list as $laporan): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($laporan['kode_tracking']); ?></strong></td>
                                <td><?php echo htmlspecialchars($laporan['nama_pelapor']); ?></td>
                                <td><?php echo htmlspecialchars(substr($laporan['lokasi'], 0, 50)) . (strlen($laporan['lokasi']) > 50 ? '...' : ''); ?></td>
                                <td><?php echo htmlspecialchars(substr($laporan['isi_pengaduan'], 0, 60)) . (strlen($laporan['isi_pengaduan']) > 60 ? '...' : ''); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $laporan['status']; ?>">
                                        <?php 
                                        $status_label = [
                                            'pending' => '⏳ Pending',
                                            'proses' => '🔄 Diproses',
                                            'selesai' => '✅ Selesai'
                                        ];
                                        echo $status_label[$laporan['status']];
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($laporan['tgl_input'])); ?></td>
                                <td>
                                    <a href="detail_laporan.php?id=<?php echo $laporan['id_laporan']; ?>" class="btn-detail">Detail</a>
                                    <button class="btn-status" onclick="openStatusModal(<?php echo $laporan['id_laporan']; ?>, '<?php echo $laporan['status']; ?>', '<?php echo $laporan['kode_tracking']; ?>')">Ubah Status</button>
                                    <button class="btn-delete" onclick="hapusLaporan(<?php echo $laporan['id_laporan']; ?>, '<?php echo $laporan['kode_tracking']; ?>')">🗑️ Hapus</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        📭 Belum ada data laporan
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Ubah Status -->
    <div id="statusModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000;">
        <div style="background: white; padding: 30px; border-radius: 10px; width: 400px; max-width: 90%;">
            <h3>Ubah Status Laporan</h3>
            <p>Kode Tracking: <strong id="modal_kode"></strong></p>
            <form action="ubah_status.php" method="POST">
                <input type="hidden" name="id_laporan" id="modal_id">
                <div style="margin: 20px 0;">
                    <label style="display: block; margin-bottom: 10px; font-weight: bold;">Status:</label>
                    <select name="status" id="modal_status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                        <option value="pending">⏳ Pending</option>
                        <option value="proses">🔄 Diproses</option>
                        <option value="selesai">✅ Selesai</option>
                    </select>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" style="padding: 10px 20px; background: #95a5a6; color: white; border: none; border-radius: 5px; cursor: pointer;">Batal</button>
                    <button type="submit" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 5px; cursor: pointer;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openStatusModal(id, status, kode) {
            document.getElementById('modal_id').value = id;
            document.getElementById('modal_kode').innerText = kode;
            document.getElementById('modal_status').value = status;
            document.getElementById('statusModal').style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('statusModal').style.display = 'none';
        }
        
        function hapusLaporan(id, kode) {
            if (confirm("Yakin ingin menghapus laporan dengan kode " + kode + "?\nData yang dihapus tidak dapat dikembalikan!")) {
                window.location.href = "hapus_laporan.php?id=" + id;
            }
        }
        
        window.onclick = function(event) {
            if (event.target == document.getElementById('statusModal')) {
                closeModal();
            }
        }
    </script>

    <footer>
        <p>&copy; 2024 SISPEK - Sistem Pengaduan Masyarakat</p>
    </footer>
</body>
</html>