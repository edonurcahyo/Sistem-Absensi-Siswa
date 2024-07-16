<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config/db.php';

// Ambil data siswa
$siswaQuery = "SELECT nama, kelas, username FROM siswa";
$siswaResult = $conn->query($siswaQuery);

if (!$siswaResult) {
    die("Query error: " . $conn->error);
}

// Ambil data guru
$guruQuery = "SELECT nama, mata_pelajaran, username FROM guru";
$guruResult = $conn->query($guruQuery);

if (!$guruResult) {
    die("Query error: " . $conn->error);
}

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
    <title>Dashboard Admin</title>
    <link rel="shortcut icon" href="/images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-box">
            <h1>Dashboard Admin</h1>
            <form class="logout-button" method="post" action="../logout.php">
                <button type="submit">Logout</button>
            </form>
            
            <h2>Data Siswa</h2>
            <table>
                <tr>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Username</th>
                </tr>
                <?php while ($row = $siswaResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="dashboard-box">
            <h2>Data Guru</h2>
            <table>
                <tr>
                    <th>Nama</th>
                    <th>Mata Pelajaran</th>
                    <th>Username</th>
                </tr>
                <?php while ($row = $guruResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['mata_pelajaran']); ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="dashboard-box">
            <h2>Data Absensi Siswa</h2>
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
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>