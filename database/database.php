<?php
// --- Konfigurasi Database ---
$db_host = '127.0.0.1'; // atau 'localhost'
$db_user = 'root'; // User default MySQL
$db_pass = 'root'; // Ganti dengan kata sandi root yang Anda atur
$db_name = 'mahasiswa'; // Nama database yang akan kita buat

// --- 1. Membuat Koneksi ke MySQL Server ---
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("<h1>Koneksi ke MySQL Gagal: " . $conn->connect_error . "</h1>");
}


// db.php - File Koneksi Database

// $dbHost = 'localhost';
// $dbName = 'mahasiswa';
// $dbUser = 'root';
// $dbPass = 'root';

// try {
//     $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
//     $options = [
//         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//         PDO::ATTR_EMULATE_PREPARES   => false,
//     ];
//     $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
// } catch (PDOException $e) {
//     die("Koneksi database gagal: " . $e->getMessage());
// }

?>