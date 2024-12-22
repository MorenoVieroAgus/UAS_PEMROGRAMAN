<?php
session_start();
require 'config.php';

// Periksa apakah user sudah login dan memiliki akses (contoh untuk role admin)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Query untuk mengambil semua data peserta
$data_peserta = $conn->query("SELECT * FROM peserta");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peserta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Data Peserta</h2>

    <div class="text-end mb-3">
        <a href="tambah_peserta.php" class="btn btn-success">Tambah Peserta</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Tanggal Lahir</th>
                <th>Jenis Kelamin</th>
                <th>Pendidikan</th>
                <th>Alasan</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($data_peserta->num_rows > 0): ?>
                <?php $no = 1; ?>
                <?php while ($row = $data_peserta->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['telepon']); ?></td>
                        <td><?= htmlspecialchars($row['alamat']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_lahir']); ?></td>
                        <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                        <td><?= htmlspecialchars($row['tingkat_pendidikan']); ?></td>
                        <td><?= htmlspecialchars($row['alasan']); ?></td>
                        <td>
                            <?php if (!empty($row['foto'])): ?>
                                <img src="uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto" style="width: 80px; height: auto;">
                            <?php else: ?>
                                Tidak ada foto
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_peserta.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus_peserta.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data peserta</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
