<?php
session_start();
include 'config/db.php';

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
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>LOGIN</h1>
            <p>Ijinkan Sistem Mengenali Anda.</p>
            <form action="process_login.php" method="post">
                <div class="input-group">
                    <label for="username"><i class='bx bxs-user'></i> Username</label>
                    <input type="text" placeholder="Username" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password"><i class='bx bxs-lock-alt'></i> Password</label>
                    <input type="password" placeholder="Password" id="password" name="password" required>
                </div>
                <div class="input-group-button">
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

