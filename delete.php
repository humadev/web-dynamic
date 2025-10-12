<?php
// Memasukkan file koneksi database
include 'database/database.php';

// Memeriksa apakah parameter 'id' ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // --- Hapus file foto terkait sebelum menghapus record dari database ---
    // Query untuk mendapatkan nama file foto berdasarkan ID
    $sql_select_photo = "SELECT photo FROM mahasiswa WHERE id=$id";
    $result = mysqli_query($conn, $sql_select_photo);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $photo_to_delete = $row['photo'];
        
        // Cek jika nama file tidak kosong dan file tersebut ada
        if (!empty($photo_to_delete) && file_exists('uploads/' . $photo_to_delete)) {
            // Hapus file dari folder 'uploads'
            unlink('uploads/' . $photo_to_delete);
        }
    }

    // Query SQL untuk menghapus record berdasarkan ID
    $sql_delete = "DELETE FROM mahasiswa WHERE id=$id";

    // Eksekusi query dan periksa hasilnya
    if (mysqli_query($conn, $sql_delete)) {
        // Jika berhasil, redirect kembali ke halaman utama
        header("Location: index.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // Jika tidak ada ID, redirect ke halaman utama
    header("Location: index.php");
    exit();
}

// Menutup koneksi database
mysqli_close($conn);
?>
