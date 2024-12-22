<?php
require 'config.php';
require 'vendor/autoload.php';

use Dompdf\Dompdf;

// Query data peserta
$query = "SELECT * FROM peserta";
$result = mysqli_query($conn, $query);

// Mulai membuat HTML untuk PDF
$html = '<h2>Participant Report</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">';
$html .= '<thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Education Level</th>
                <th>Reason</th>
            </tr>
          </thead><tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
                <td>' . $no++ . '</td>
                <td>' . htmlspecialchars($row['nama']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
                <td>' . htmlspecialchars($row['telepon']) . '</td>
                <td>' . htmlspecialchars($row['alamat']) . '</td>
                <td>' . htmlspecialchars($row['jenis_kelamin']) . '</td>
                <td>' . htmlspecialchars($row['tingkat_pendidikan']) . '</td>
                <td>' . htmlspecialchars($row['alasan']) . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Konversi HTML ke PDF menggunakan Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Unduh file PDF
$dompdf->stream("Participant_Report.pdf", ["Attachment" => 1]);
exit;
?>

<div class="container">
    <div class="header">
        <h1>Participant Report</h1>
    </div>

    <div class="nav">
        <a href="kelola_data.php">Dashboard</a>
        <a href="lihat_laporan.php">View Report</a>
        <button onclick="window.print()" class="action-btn">Print Report</button> <!-- Tombol Cetak -->
        <a href="download_pdf.php" class="action-btn">Download PDF</a> <!-- Tombol Download PDF -->
        <a href="download_excel.php" class="action-btn">Download Excel</a> <!-- Tombol Download Excel -->
    </div>
</div>
