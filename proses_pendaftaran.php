<?php
// Mulai sesi
session_start();

// Include koneksi database
require_once 'config.php';

// Periksa koneksi ke database
if (!$conn) {
    die("<script>alert('Gagal terhubung ke database: " . htmlspecialchars(mysqli_connect_error()) . "');</script>");
}

// Cek apakah data dikirim melalui POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tingkat_pendidikan = $_POST['tingkat_pendidikan'];
    $alasan = trim($_POST['alasan']);

    // Validasi input
    if (empty($nama) || empty($email) || empty($telepon) || empty($alamat) || empty($tanggal_lahir) || empty($jenis_kelamin) || empty($tingkat_pendidikan) || empty($alasan)) {
        echo "<script>alert('Semua bidang harus diisi.'); window.history.back();</script>";
        exit;
    }

    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid.'); window.history.back();</script>";
        exit;
    }

    // Handle upload file foto
    $foto = $_FILES['foto'];
    $foto_new_name = null; // Default jika tidak ada foto diunggah

    if (!empty($foto['name'])) {
        $foto_name = $foto['name'];
        $foto_tmp = $foto['tmp_name'];
        $foto_size = $foto['size'];
        $foto_error = $foto['error'];
        $foto_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
        $foto_allowed = ['jpg', 'jpeg', 'png'];

        // Validasi file foto
        if (in_array($foto_ext, $foto_allowed) && $foto_error === 0 && $foto_size <= 2000000) {
            $foto_new_name = uniqid('foto_', true) . '.' . $foto_ext;
            $foto_path = 'uploads/' . $foto_new_name;

            // Periksa apakah direktori uploads dapat diakses
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }

            if (!move_uploaded_file($foto_tmp, $foto_path)) {
                echo "<script>alert('Gagal mengunggah foto. Pastikan folder uploads memiliki izin tulis.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('File foto tidak valid (hanya JPG, JPEG, PNG, maksimal 2MB).'); window.history.back();</script>";
            exit;
        }
    }

    // Simpan data ke database
    $query = "INSERT INTO peserta (nama, email, telepon, alamat, tanggal_lahir, jenis_kelamin, tingkat_pendidikan, alasan, foto) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Persiapkan pernyataan
    if ($stmt = $conn->prepare($query)) {
        // Bind parameter
        $stmt->bind_param(
            'sssssssss',
            $nama, $email, $telepon, $alamat, $tanggal_lahir,
            $jenis_kelamin, $tingkat_pendidikan, $alasan, $foto_new_name
        );

        // Eksekusi query
        if ($stmt->execute()) {
            echo "<script>alert('Pendaftaran berhasil!'); window.location.href = 'index.php';</script>";
        } else {
            error_log("SQL Error: " . $stmt->error); // Log kesalahan query
            echo "<script>alert('Pendaftaran gagal. Silakan coba lagi.'); window.history.back();</script>";
        }

        // Tutup pernyataan
        $stmt->close();
    } else {
        error_log("SQL Preparation Error: " . $conn->error); // Log kesalahan SQL
        echo "<script>alert('Gagal menyiapkan query SQL.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Metode pengiriman tidak valid.'); window.history.back();</script>";
}

// Tutup koneksi database
$conn->close();
?>
