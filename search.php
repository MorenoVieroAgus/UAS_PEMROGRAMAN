<?php
// Include the database connection
include('config.php');

// Inisialisasi variabel hasil pencarian
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';

    // Query untuk mencari data berdasarkan nama, email, atau telepon
    $query = "SELECT * FROM peserta WHERE nama LIKE ? OR email LIKE ? OR telepon LIKE ?";
    $stmt = $conn->prepare($query);

    // Debug jika $stmt gagal
    if (!$stmt) {
        die("Kesalahan Query: " . $conn->error);
    }

    $stmt->bind_param('sss', $search, $search, $search);
    $stmt->execute();

    $result = $stmt->get_result();

    // Periksa hasil query
    if ($result->num_rows > 0) {
        $results = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Debug jika data tidak ditemukan
        error_log("Tidak ada data ditemukan untuk pencarian: " . htmlspecialchars($_GET['search']));
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Report</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { width: 80%; margin: 20px auto; }
        .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
        .nav { background-color: #333; overflow: hidden; }
        .nav a { color: white; padding: 14px 20px; text-decoration: none; display: inline-block; }
        .nav a:hover { background-color: #ddd; color: black; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f2f2f2; }
        .action-btn { color: #4CAF50; text-decoration: none; }
        .action-btn:hover { color: #45a049; }
        img { width: 50px; height: auto; }
        .print-btn {
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .print-btn:hover { background-color: #45a049; }
        @media print {
            .nav, .print-btn { display: none; }
            body { background-color: white; }
        }
        .alert-warning {
            background-color: #ff9800;
            color: white;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Dashboard Header -->
    <div class="header">
        <h1>Participant Report</h1>
    </div>

    <!-- Navigation Bar -->
    <div class="nav">
        <a href="kelola_data.php">Dashboard</a>
        <a href="lihat_laporan.php">View Report</a>
    </div>

    <!-- Form Pencarian -->
    <form method="GET" action="" class="d-flex mb-4">
        <input class="form-control me-2" type="search" name="search" placeholder="Cari berdasarkan nama, email, atau telepon" aria-label="Search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" required>
        <button class="btn btn-outline-primary" type="submit">Cari</button>
    </form>

    <!-- Tabel Hasil Pencarian -->
    <?php if (!empty($results)): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['telepon']); ?></td>
                        <td><?= htmlspecialchars($row['alamat']); ?></td>
                        <td><?= htmlspecialchars($row['tanggal_lahir']); ?></td>
                        <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                        <td><?= htmlspecialchars($row['tingkat_pendidikan']); ?></td>
                        <td><?= htmlspecialchars($row['alasan']); ?></td>
                        <td>
                            <?php if (!empty($row['foto']) && file_exists('uploads/' . $row['foto'])): ?>
                                <img src="uploads/<?= htmlspecialchars($row['foto']); ?>" alt="Foto" style="width: 100px;">
                            <?php else: ?>
                                Tidak ada foto
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <?php if (isset($_GET['search'])): ?>
            <div class="alert alert-warning">Tidak ada data ditemukan untuk pencarian "<?= htmlspecialchars($_GET['search']); ?>".</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2024 Sistem Informasi Pendaftaran Pramuka</p>
</footer>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
