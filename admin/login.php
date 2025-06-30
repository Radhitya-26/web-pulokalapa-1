<?php
session_start();
// Cek jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: Dashboard.php');
    exit();
}
// Proses login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Koneksi ke database
    $conn = new mysqli('localhost', 'root', '', 'pulokalapa');
    if ($conn->connect_error) {
        die('Koneksi gagal: ' . $conn->connect_error);
    }
    $stmt = $conn->prepare('SELECT * FROM admin WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        // Bandingkan password secara langsung (plain text)
        if ($password === $row['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header('Location: Dashboard.php');
            exit();
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Username tidak ditemukan!';
    }
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Desa Pulokalapa</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
            font-family: 'Montserrat', Arial, sans-serif;
        }
        .login-box {
            background: #fff;
            padding: 40px 32px 32px 32px;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            width: 100%;
            max-width: 370px;
            text-align: center;
            position: relative;
        }
        .login-box img {
            width: 80px;
            margin-bottom: 18px;
        }
        .login-box h2 {
            margin-bottom: 18px;
            font-weight: 700;
            color: #4e54c8;
            letter-spacing: 1px;
        }
        .login-box input {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 16px;
            border: 1px solid #d1d1d1;
            border-radius: 8px;
            font-size: 1rem;
            background: #f7f7f7;
            transition: border 0.2s;
        }
        .login-box input:focus {
            border: 1.5px solid #4e54c8;
            outline: none;
            background: #fff;
        }
        .login-box button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(78, 84, 200, 0.15);
            transition: background 0.2s, transform 0.2s;
        }
        .login-box button:hover {
            background: linear-gradient(90deg, #8f94fb 0%, #4e54c8 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .error {
            color: #e74c3c;
            margin-bottom: 14px;
            font-size: 0.97rem;
        }
        .login-box .footer {
            margin-top: 18px;
            font-size: 0.93rem;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <img src="../assets/img/logo_krw-removebg-preview.png" alt="Logo Desa" />
        <h2>Login Admin</h2>
        <?php if ($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <form method="post" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="footer">&copy; <?php echo date('Y'); ?> Desa Pulokalapa</div>
    </div>
</body>
</html>
