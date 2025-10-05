<?php
session_start();
require_once '../database/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal harus 6 karakter.';
    } else {
        // Cek apakah email sudah ada
        $stmt = $pdo->prepare('SELECT id FROM mahasiswa WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email ini sudah digunakan.';
        } else {
            // Simpan pengguna baru ke database
            $stmt = $pdo->prepare('INSERT INTO mahasiswa (email, password) VALUES (?, ?)');
            if ($stmt->execute([$email, $password])) {
                $_SESSION['success_message'] = 'Registrasi berhasil! Silakan login.';
                header('Location: login.php');
                exit;
            } else {
                $error = 'Terjadi kesalahan. Coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Buat Akun Baru</h2>
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Daftar</button>
        </form>
        <div class="switch-form">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>