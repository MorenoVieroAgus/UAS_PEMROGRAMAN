<?php
// Mulai sesi
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Ambil email user dari sesi
$email = $_SESSION['email'];

// Koneksi ke database
require_once 'config.php';

// Query untuk mengambil data pengguna
$query = "SELECT username FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User tidak ditemukan.";
    exit;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .navbar {
            background-color: #2c3e50;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .dashboard-card {
            border: none;
            box-shadow: 0px 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">PENDAFTARAN PRAMUKA Dashboard</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    
                </li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Selamat Datang Di Pendaftaran Pramuka, <?= htmlspecialchars($user['username']); ?>!</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card p-3">
                    <h5 class="card-title">Informasi Akun</h5>
                    <p class="card-text">
                        <strong>Email:</strong> <?= htmlspecialchars($email); ?><br>
                        <strong>Username:</strong> <?= htmlspecialchars($user['username']); ?>
                    </p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card p-3">
                    <h5 class="card-title">Aktivitas Terbaru</h5>
                    <ul>
                        <li>Login terakhir: <?= date('d-m-Y H:i:s'); ?></li>
                        <li>Update profil: Tidak ada</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-4">
            <div class="card dashboard-card p-3 text-center">
    <h5 class="card-title">Menu</h5>
    <a href="kelola_data.php" class="btn btn-primary w-100 mb-2">Kelola Data</a>
    <a href="lihat_laporan.php" class="btn btn-success w-100 mb-2">Lihat Laporan</a>
    <a href="logout.php" class="btn btn-danger w-100">Logout</a>
</div>

            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2024 PENDAFTARAN PRAMUKA Dashboard. All Rights Reserved.</p>
    </footer>
</body>
</html>
