<?php
// Memasukkan file koneksi database
include 'database/database.php';

// Inisialisasi variabel pesan
$message = '';

// Memeriksa apakah form telah disubmit (metode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Mengambil data dari form dan melindunginya dari SQL injection
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // Menggunakan password_hash untuk keamanan kata sandi
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);

    // --- Proses Upload Foto ---
    $photo = ''; // Inisialisasi nama file foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        // Buat folder 'uploads' jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        // Buat nama file unik untuk menghindari penimpaan file
        $photo = time() . '_' . basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $photo;

        // Pindahkan file yang di-upload ke folder tujuan
        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $message = '<div class="alert alert-danger" role="alert">Maaf, terjadi kesalahan saat mengupload file Anda.</div>';
            $photo = ''; // Reset nama file jika gagal
        }
    }

    // Query SQL untuk memasukkan data baru ke tabel mahasiswa
    $sql = "INSERT INTO mahasiswa (nama, email, telepon) 
            VALUES ('$nama', '$email', '$telepon')";

    // Eksekusi query dan periksa hasilnya
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, redirect ke halaman utama
        header("Location: index.php");
        exit(); // Hentikan eksekusi skrip setelah redirect
    } else {
        // Jika gagal, tampilkan pesan error
        $message = '<div class="alert alert-danger" role="alert">Error: ' . $sql . '<br>' . mysqli_error($conn) . '</div>';
    }

    // Menutup koneksi database
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 800px; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Form Tambah Mahasiswa</h1>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <!-- Form untuk menambah data, pastikan 'enctype' untuk upload file -->
            <form action="create.php" method="post" enctype="multipart/form-data">
                <div class="row">
                    <!-- Kolom Kiri -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon">
                        </div>
                </div>
                <hr>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
