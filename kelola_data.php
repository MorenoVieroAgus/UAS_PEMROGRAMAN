<?php
// Include database connection
include('config.php');

// Add new participant
if (isset($_POST['add'])) {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tingkat_pendidikan = $_POST['tingkat_pendidikan'];
    $alasan = trim($_POST['alasan']);
    $foto = $_FILES['gambar']['name'];

    // Move the uploaded file to the desired folder (optional step)
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($foto);
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_file)) {
        // File successfully uploaded
    } else {
        // Handle file upload error
        $foto = NULL;
    }

    // Insert the participant data into the database
    $query = "INSERT INTO peserta (nama, email, telepon, alamat, tanggal_lahir, jenis_kelamin, tingkat_pendidikan, alasan, foto)
              VALUES ('$nama', '$email', '$telepon', '$alamat', '$tanggal_lahir', '$jenis_kelamin', '$tingkat_pendidikan', '$alasan', '$foto')";
    if (mysqli_query($conn, $query)) {
        echo "New participant added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Update participant
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $tingkat_pendidikan = $_POST['tingkat_pendidikan'];
    $alasan = trim($_POST['alasan']);
    $foto = $_FILES['gambar']['name'];

    $query = "UPDATE peserta SET nama='$nama', email='$email', telepon='$telepon', alamat='$alamat', tanggal_lahir='$tanggal_lahir',
              jenis_kelamin='$jenis_kelamin', tingkat_pendidikan='$tingkat_pendidikan', alasan='$alasan', foto='$foto' WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "Participant updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete participant
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM peserta WHERE id='$id'";
    if (mysqli_query($conn, $query)) {
        echo "Participant deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch participants to display
$query = "SELECT * FROM peserta";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Management Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { width: 80%; margin: 20px auto; }
        .header { background-color: #4CAF50; color: white; padding: 20px; text-align: center; }
        .nav { background-color: #333; overflow: hidden; }
        .nav a { color: white; padding: 14px 20px; text-decoration: none; display: inline-block; }
        .nav a:hover { background-color: #ddd; color: black; }
        .form-container { background-color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f2f2f2; }
        .action-btn { color: #4CAF50; text-decoration: none; }
        .action-btn:hover { color: #45a049; }
        img { width: 50px; height: auto; }
    </style>
</head>
<body>

<div class="container">

    <!-- Dashboard Header -->
    <div class="header">
        <h1>Dashboard Manajemen Peserta</h1>
    </div>

    <!-- Navigation Bar -->
    <div class="nav">
        <a href="kelola_data.php">Home</a>
        <a href="kelola_data.php?add">Tambah Peserta</a>
        <a href="kelola_data.php?view">Lihat Peserta</a>
    </div>

    <!-- Add New Participant Form -->
    <?php if (isset($_GET['add'])) { ?>
        <div class="form-container">
            <h2>Add New Participant</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="nama" placeholder="Name" required><br><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                <input type="text" name="telepon" placeholder="Phone" required><br><br>
                <input type="text" name="alamat" placeholder="Address" required><br><br>
                <input type="date" name="tanggal_lahir" required><br><br>
                <select name="jenis_kelamin" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select><br><br>
                <input type="text" name="tingkat_pendidikan" placeholder="Education Level" required><br><br>
                <textarea name="alasan" placeholder="Reason for Participation" required></textarea><br><br>
                <input type="file" name="gambar" placeholder="photo" required><br><br>
                <button type="submit" name="add">Add Participant</button>
            </form>
        </div>
    <?php } ?>

    <!-- Display Participants -->
<?php if (!isset($_GET['add'])) { ?>
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
            <th>Alasan</th> <!-- Added column for alasan -->
            <th>Tindakan</th>
            <th>Photo</th>
        </tr>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $foto_path = $row['foto'] ? 'uploads/' . $row['foto'] : '';
            echo "<tr>
                    <td>" . $no++ . "</td>
                    <td>" . htmlspecialchars($row['nama']) . "</td>
                    <td>" . htmlspecialchars($row['email']) . "</td>
                    <td>" . htmlspecialchars($row['telepon']) . "</td>
                    <td>" . htmlspecialchars($row['alamat']) . "</td>
                    <td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>
                    <td>" . htmlspecialchars($row['tingkat_pendidikan']) . "</td>
                    <td>" . htmlspecialchars($row['alasan']) . "</td> <!-- Display alasan -->
                    <td>
                        <a href='edit.php?edit=" . $row['id'] . "' class='action-btn'>Edit</a> |
                        <a href='kelola_data.php?delete=" . $row['id'] . "' class='action-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                    <td>" . ($foto_path ? "<img src='$foto_path' alt='Photo'>" : 'No photo') . "</td>
                  </tr>";
        }
        ?>
    </table>
<?php } ?>



</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
