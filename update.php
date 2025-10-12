<?php
include 'database/database.php';

$message = '';
$id = $_GET['id']; // Ambil ID dari URL

// Ambil data mahasiswa yang akan diupdate untuk ditampilkan di form
$sql_select = "SELECT * FROM mahasiswa WHERE id=$id";
$result_select = mysqli_query($conn, $sql_select);
$row = mysqli_fetch_assoc($result_select);

if (!$row) {
    die("Data mahasiswa tidak ditemukan.");
}

// Proses update data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);

    // Query untuk update data
    $sql_update = "UPDATE mahasiswa SET 
                    nama='$nama', email='$email', telepon='$telepon'
                  WHERE id=$id";


    if (mysqli_query($conn, $sql_update)) {
        header("Location: index.php");
        exit();
    } else {
        $message = '<div class="alert alert-danger" role="alert">Error updating record: ' . mysqli_error($conn) . '</div>';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { max-width: 800px; }
        .current-photo { max-width: 100px; margin-top: 10px; border-radius: 5px; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h1 class="h4 mb-0">Form Edit Mahasiswa</h1>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form action="update.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <!-- Kolom Kiri -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($row['nama']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telepon" class="form-label">Telepon</label>
                            <input type="text" class="form-control" id="telepon" name="telepon" value="<?php echo htmlspecialchars($row['telepon']); ?>">
                        </div>
                </div>
                <hr>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Data</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
