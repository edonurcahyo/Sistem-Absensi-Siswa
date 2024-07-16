<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../login.php');
    exit;
}

include '../config/db.php';

// Ambil data siswa dari tabel siswa berdasarkan username
$username = $_SESSION['user'];
$stmt = $conn->prepare("SELECT * FROM siswa WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$siswa = $result->fetch_assoc();
$nama = $siswa['nama'];
$kelas = $siswa['kelas'];

$stmt->close();

// Ambil data jadwal pelajaran berdasarkan kelas siswa
$jadwalQuery = "SELECT * FROM jadwal WHERE kelas = ?";
$jadwalStmt = $conn->prepare($jadwalQuery);
$jadwalStmt->bind_param("s", $kelas);
$jadwalStmt->execute();
$jadwalResult = $jadwalStmt->get_result();

if (!$jadwalResult) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Siswa</title>
    <link rel="shortcut icon" href="/images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-box">
            <h1>Dashboard Siswa</h1>
            <h2>Selamat datang, <?php echo htmlspecialchars($nama); ?></h2>
            <p>Kelas: <?php echo htmlspecialchars($kelas); ?></p>
            <h3>Jadwal Pelajaran</h3>
            <table>
                <tr>
                    <th>Hari</th>
                    <th>Mata Pelajaran</th>
                    <th>Jam</th>
                </tr>
                <?php while ($row = $jadwalResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['hari']); ?></td>
                        <td><?php echo htmlspecialchars($row['mata_pelajaran']); ?></td>
                        <td><?php echo htmlspecialchars($row['jam_mulai'] . ' - ' . $row['jam_selesai']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <form method="post" action="../logout.php">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>