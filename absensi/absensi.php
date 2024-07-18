<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'siswa') {
    header('Location: login.php');
    exit;
}

include 'config/db.php';

$username = $_SESSION['user'];
$stmt = $conn->prepare("SELECT * FROM siswa WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$siswa = $result->fetch_assoc();
$nama = $siswa['nama'];
$kelas = $siswa['kelas'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = date('Y-m-d');
    $status = $_POST['status'];
 
    $stmt = $conn->prepare("SELECT * FROM absensi WHERE username = ? AND tanggal = ?");
    $stmt->bind_param("ss", $username, $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO absensi (username, nama, kelas, tanggal, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $nama, $kelas, $tanggal, $status);
        $stmt->execute();
        $msg = "Absensi berhasil dicatat!";
    } else {
        $msg = "Anda sudah mengisi absensi hari ini!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Absensi Siswa</title>
    <link rel="stylesheet" type="text/css" href="css\dashboard.css">
</head>
<body>
    <h1>Absensi Siswa</h1>
    <form method="POST" action="absensi.php">
        <label for="nama">Nama:</label>
        <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>" readonly>
        <br>
        <label for="kelas">Kelas:</label>
        <input type="text" id="kelas" name="kelas" value="<?php echo $kelas; ?>" readonly>
        <br>
        <label for="status">Status Kehadiran:</label>
        <select id="status" name="status">
            <option value="Hadir">Hadir</option>
            <option value="Izin">Izin</option>
            <option value="Sakit">Sakit</option>
            <option value="Alpa">Alpa</option>
        </select>
        <br>
        <button type="submit">Submit</button>
    </form>
    <?php if (isset($msg)): ?>
        <p><?php echo $msg; ?></p>
    <?php endif; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
