<?php
session_start();
include 'config/db.php'; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $password = md5($password);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    
    if ($stmt === false) {
        echo "Prepare statement error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ss", $username, $password);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user'] = $username;
        $_SESSION['role'] = $row['role'];
        
        if ($row['role'] == 'siswa') {
            header('Location: siswa\siswa_dashboard.php');
        } else if ($row['role'] == 'guru') {
            header('Location: guru\guru_dashboard.php');
        } else if ($row['role'] == 'admin') {
            header('Location: admin\admin_dashboard.php');
        }
    } else {
        echo "Username atau password salah";
    }

    $stmt->close();
}

$conn->close();
?>
