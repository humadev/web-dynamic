<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <!-- Integrasi Bootstrap 5 untuk styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styling untuk tampilan yang lebih baik */
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .action-buttons a {
            margin-right: 5px;
        }
        .profile-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Manajemen Data Mahasiswa</h1>
            <a href="create.php" class="btn btn-light btn-sm">Tambah Mahasiswa Baru</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Memasukkan file koneksi database
                        include 'database/database.php';

                        // Query untuk mengambil semua data dari tabel mahasiswa
                        $sql = "SELECT id, nama, email, telepon, photo FROM mahasiswa";
                        $result = mysqli_query($conn, $sql);

                        // Periksa apakah ada data yang ditemukan
                        if (mysqli_num_rows($result) > 0) {
                            // Looping untuk menampilkan setiap baris data
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>";
                                // Tampilkan foto jika ada, jika tidak tampilkan placeholder
                                if (!empty($row["photo"]) && file_exists("uploads/" . $row["photo"])) {
                                    echo "<img src='uploads/" . $row["photo"] . "' alt='Foto " . $row['nama'] . "' class='profile-photo'>";
                                } else {
                                    echo "<img src='https://placehold.co/50x50/ced4da/6c757d?text=N/A' alt='Foto tidak tersedia' class='profile-photo'>";
                                }
                                echo "</td>";
                                echo "<td>" . htmlspecialchars($row["nama"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["telepon"]) . "</td>";
                                echo "<td class='action-buttons'>";
                                echo "<a href='update.php?id=" . $row["id"] . "' class='btn btn-warning btn-sm'>Edit</a>";
                                // Tambahkan konfirmasi javascript sebelum menghapus
                                echo "<a href='delete.php?id=" . $row["id"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            // Tampilkan pesan jika tidak ada data
                            echo "<tr><td colspan='6' class='text-center'>Tidak ada data mahasiswa ditemukan.</td></tr>";
                        }

                        // Menutup koneksi database
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
