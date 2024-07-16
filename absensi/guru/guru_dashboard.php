<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'guru') {
    header('Location: ../login.php');
    exit;
}

include '../config/db.php';

// Ambil data guru dari tabel guru berdasarkan username
$username = $_SESSION['user'];
$stmt = $conn->prepare("SELECT * FROM guru WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$guru = $result->fetch_assoc();
$nama = $guru['nama'];
$mata_pelajaran = $guru['mata_pelajaran'];

$stmt->close();

// Ambil data absensi siswa
$absensiQuery = "SELECT username, nama, kelas, tanggal, status FROM absensi";
$absensiResult = $conn->query($absensiQuery);

if (!$absensiResult) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Guru</title>
    <link rel="shortcut icon" href="/images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-box">
            <h1>Dashboard Guru</h1>
            <h2>Selamat datang, <?php echo htmlspecialchars($nama); ?></h2>
            <p>Mata Pelajaran: <?php echo htmlspecialchars($mata_pelajaran); ?></p>
            <h3>Data Absensi Siswa</h3>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $absensiResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
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
