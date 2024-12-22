<?php
// Include the database connection
include('config.php');

// Fetch participants or any other data you want to display in the report
$query = "SELECT * FROM peserta";
$result = mysqli_query($conn, $query);
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
    </style>
</head>
<body>

<div class="container">

    <!-- Dashboard Header -->
    <div class="header">
        <h1>Laporan Peserta</h1>
    </div>

    <!-- Navigation Bar -->
    <div class="nav">
        <a href="kelola_data.php">Dashboard</a>
        <a href="lihat_laporan.php">Lihat Laporan</a>
    </div>

    <!-- Print Button -->
    <button class="print-btn" onclick="window.print()">Print Report</button>

    <h2>Daftar Peserta</h2>

    <table>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Alamat</th>
            <th>Jenis Kelamin</th>
            <th>Tingkat Pendidikan</th>
            <th>Alasan</th>
            <th>Tindakan</th>
            <th>Photo</th>
        </tr>

        <?php
        // Counter for row numbers
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the photo exists and form the image URL
            $foto_path = $row['foto'] ? 'uploads/' . $row['foto'] : '';
            echo "<tr>
                    <td>" . $no++ . "</td>
                    <td>" . htmlspecialchars($row['nama']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['telepon']) . "</td>
                    <td>" . htmlspecialchars($row['alamat']) . "</td>
                    <td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>
                    <td>" . htmlspecialchars($row['tingkat_pendidikan']) . "</td>
                    <td>" . htmlspecialchars($row['alasan']) . "</td>
                    <td>
                        <a href='kelola_data.php?edit=" . $row['id'] . "' class='action-btn'>Edit</a> |
                        <a href='kelola_data.php?delete=" . $row['id'] . "' class='action-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                    <td>" . ($foto_path ? "<img src='$foto_path' alt='Photo'>" : 'No photo') . "</td>
                  </tr>";
        }
        ?>
    </table>

</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
