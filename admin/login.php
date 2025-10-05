<?php
session_start();
require_once '../database/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM mahasiswa WHERE email = ? and password = ?');
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch();

        if ($user) {
            // Jika password cocok, simpan info user ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}

// Menampilkan pesan sukses dari registrasi jika ada
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="switch-form">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>
</body>
</html>