<?php
session_start();
include 'config/db.php'; // Path untuk file koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
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
            header('Location: siswa/siswa_dashboard.php');
        } elseif ($row['role'] == 'guru') {
            header('Location: guru/guru_dashboard.php');
        } elseif ($row['role'] == 'admin') {
            header('Location: admin/admin_dashboard.php');
        }
    } else {
        $login_error = "Username atau password salah";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="shortcut icon" href="images\logo.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>LOGIN</h1>
            <p>Ijinkan Sistem Mengenali Anda.</p>
            <form action="process_login.php" method="post">
                <div class="input-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" placeholder="username" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" placeholder="password" id="password" name="password" required>
                </div>
                <div class="input-group-button">
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

