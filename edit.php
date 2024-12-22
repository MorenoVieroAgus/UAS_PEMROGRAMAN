<?php
session_start();
require 'config.php';

// Periksa apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: form_pendaftaran.php");
    exit();
}

// Ambil ID konser dari URL
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id_concert = $_GET['id'];

// Ambil data konser berdasarkan ID
$result = $conn->query("SELECT * FROM concerts WHERE id = '$id'");
if ($result->num_rows == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='admin.php';</script>";
    exit();
}
$concert = $result->fetch_assoc();

// Proses update data konser
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $conn->real_escape_string(trim($_POST['nama']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $telepon = $conn->real_escape_string(trim($_POST['telepon']));
    $alamat = $conn->real_escape_string(trim($_POST['alamat']));
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tingkat_pendidikan = $_POST['tingkat_pendidikan'];
    $alasan = $conn->real_escape_string(trim($_POST['alasan']));
    $gambar_sql = '';

    // Proses upload gambar
    if (!empty($_FILES['gambar']['name'])) {
        $foto = $conn->real_escape_string($_FILES['gambar']['name']);
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . basename($foto);

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_file)) {
            $gambar_sql = ", foto = '$foto'";
        } else {
            echo "<script>alert('Gagal mengupload gambar!');</script>";
        }
    }

    $sql = "UPDATE concerts SET 
            nama = '$nama', 
            email = '$email', 
            telepon = '$telepon', 
            alamat = '$alamat',
            tanggal_lahir = '$tanggal_lahir',
            jenis_kelamin = '$jenis_kelamin',
            tingkat_pendidikan = '$tingkat_pendidikan',
            alasan = '$alasan'
            $gambar_sql
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='admin.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Pendaftaran Pramuka</title>
</head>
<body>
<div class="container mt-5">
    <h3>Edit Pendaftaran Pramuka</h3>
    <a href="admin.php" class="btn btn-secondary mb-3">Kembali</a>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= $concert['nama']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" name="email" id="email" class="form-control" value="<?= $concert['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" name="telepon" id="telepon" class="form-control" value="<?= $concert['telepon']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $concert['alamat']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" value="<?= $concert['tanggal_lahir']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                <option value="Laki-laki" <?= $concert['jenis_kelamin'] === 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                <option value="Perempuan" <?= $concert['jenis_kelamin'] === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="tingkat_pendidikan" class="form-label">Tingkat Pendidikan</label>
            <select class="form-select" id="tingkat_pendidikan" name="tingkat_pendidikan" required>
                <option value="SD" <?= $concert['tingkat_pendidikan'] === 'SD' ? 'selected' : ''; ?>>SD</option>
                <option value="SMP" <?= $concert['tingkat_pendidikan'] === 'SMP' ? 'selected' : ''; ?>>SMP</option>
                <option value="SMA" <?= $concert['tingkat_pendidikan'] === 'SMA' ? 'selected' : ''; ?>>SMA</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="alasan" class="form-label">Alasan Bergabung</label>
            <textarea class="form-control" id="alasan" name="alasan" rows="3" required><?= $concert['alasan']; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="gambar" class="form-label">Foto</label>
            <input type="file" name="gambar" id="gambar" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>
