<?php
session_start();
include 'config/db.php'; // File koneksi ke database

// Memeriksa apakah form telah dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data yang dikirimkan dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mengenkripsi password dengan MD5
    $password = md5($password);

    // Prepare statement untuk memeriksa username dan password
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    
    // Periksa jika prepare statement gagal
    if ($stmt === false) {
        echo "Prepare statement error: " . $conn->error;
        exit;
    }

    // Bind parameter ke prepared statement
    $stmt->bind_param("ss", $username, $password);

    // Execute statement
    $stmt->execute();

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Jika data ditemukan, set session dan redirect ke dashboard
        $row = $result->fetch_assoc();
        $_SESSION['user'] = $username;
        $_SESSION['role'] = $row['role'];
        
        // Redirect ke dashboard sesuai role
        if ($row['role'] == 'siswa') {
            header('Location: siswa\siswa_dashboard.php');
        } else if ($row['role'] == 'guru') {
            header('Location: guru\guru_dashboard.php');
        } else if ($row['role'] == 'admin') {
            header('Location: admin\admin_dashboard.php');
        }
    } else {
        // Jika tidak ditemukan, tampilkan pesan error atau redirect kembali ke halaman login
        echo "Username atau password salah";
    }

    // Close statement
    $stmt->close();
}

// Tutup koneksi database
$conn->close();
?>
