<?php
session_start();
require 'config.php';

// Periksa apakah user sudah login dan memiliki akses sebagai admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tingkat_pendidikan = $_POST['tingkat_pendidikan'];
    $alasan = trim($_POST['alasan']);
    $foto = $_FILES['gambar']['name'];


    $sql = "INSERT INTO concerts (nama, email, telepon, alamat, tanggal_lahir, jenis_kelamin, tingkat_pendidikan, alasan, foto) 
            VALUES ('$nama', '$email', '$telepon', $alamat, '$tanggal_lahir', '$jenis_kelamin', '$tingkat_pendidikan, $alasan, $foto)";
    $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Dashboard Admin</h2>
    <div class="d-flex justify-content-between mb-3">
        <a href="tambah_peserta.php" class="btn btn-primary">Tambah Peserta</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Tingkat Pendidikan</th>
                    <th>Alasan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
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
                            <?php if ($row['foto']): ?>
                                <img src="uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto" width="50">
                            <?php else: ?>
                                Tidak ada foto
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_peserta.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="hapus_peserta.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus peserta ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Belum ada data peserta.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
