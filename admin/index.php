<?php
session_start();
require_once '../database/database.php';

// Cek apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// --- LOGIKA UNTUK MENANGANI SUBMIT FORM (METHOD POST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil semua data dari form
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $website = trim($_POST['website']);
    $github = trim($_POST['github']);
    $linkedln = trim($_POST['linkedln']); // Sesuai nama kolom di SQL Anda
    $instagram = trim($_POST['instagram']);
    $tiktok = trim($_POST['tiktok']);
    $twitter = trim($_POST['twitter']);
    $keahlian = trim($_POST['keahlian']);
    $hobi = trim($_POST['hobi']);
    
    $photo_name = $_POST['current_photo']; // Simpan nama foto lama

    // --- LOGIKA UPLOAD FOTO ---
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = '../photos/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['photo']['type'];

        if (in_array($file_type, $allowed_types)) {
            // Buat nama file yang unik untuk menghindari konflik
            $photo_name = time() . '_' . basename($_FILES['photo']['name']);
            $target_file = $upload_dir . $photo_name;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                $message = 'Gagal mengunggah foto.';
                $message_type = 'error';
                $photo_name = $_POST['current_photo']; // Kembalikan ke foto lama jika gagal
            }
        } else {
            $message = 'Hanya file JPG, PNG, dan GIF yang diizinkan.';
            $message_type = 'error';
        }
    }

    // Cek apakah profil untuk user ini sudah ada
    $stmt = $pdo->prepare('SELECT id FROM mahasiswa WHERE id = ?');
    $stmt->execute([$user_id]);
    $existing_profile = $stmt->fetch();

    try {
        if ($existing_profile) {
            // --- UPDATE DATA JIKA PROFIL SUDAH ADA ---
            $sql = "UPDATE mahasiswa SET nama=?, deskripsi=?, telepon=?, alamat=?, website=?, github=?, linkedln=?, instagram=?, tiktok=?, twitter=?, keahlian=?, hobi=?, photo=? WHERE id=?";
            $params = [$nama, $deskripsi, $telepon, $alamat, $website, $github, $linkedln, $instagram, $tiktok, $twitter, $keahlian, $hobi, $photo_name, $user_id];
        } else {
            // --- INSERT DATA JIKA PROFIL BELUM ADA ---
            $sql = "INSERT INTO mahasiswa (id, nama, deskripsi, telepon, alamat, website, github, linkedln, instagram, tiktok, twitter, keahlian, hobi, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [$user_id, $nama, $deskripsi, $telepon, $alamat, $website, $github, $linkedln, $instagram, $tiktok, $twitter, $keahlian, $hobi, $photo_name];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $message = 'Profil berhasil diperbarui!';
        $message_type = 'success';

    } catch (PDOException $e) {
        $message = 'Terjadi kesalahan pada database: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// --- LOGIKA UNTUK MENGAMBIL DATA PROFIL SAAT HALAMAN DIBUKA ---
$stmt = $pdo->prepare('SELECT * FROM mahasiswa WHERE id = ?');
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

// Inisialisasi variabel profil jika kosong
if (!$profile) {
    $profile = [
        'nama' => '', 'deskripsi' => '', 'telepon' => '', 'alamat' => '', 'website' => '', 'github' => '', 'linkedln' => '',
        'instagram' => '', 'tiktok' => '', 'twitter' => '', 'keahlian' => '', 'hobi' => '', 'photo' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Kelola Profil</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { height: auto; }
        .dashboard-container { max-width: 800px; text-align: left; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .profile-photo { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; }
        .form-row { display: flex; gap: 20px; }
        .form-row .form-group { flex: 1; }
        textarea { height: 100px; resize: vertical; }
        .logout-btn {
            padding: 8px 15px; background-color: #e74c3c; text-decoration: none; color: white; border-radius: 4px; font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="auth-container dashboard-container">
        <div class="dashboard-header">
            <h2>Kelola Profil Anda</h2>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <p>Halo, <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong>! Lengkapi atau perbarui profil Anda di bawah ini.</p>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>

        <form action="index.php" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Foto Profil Saat Ini</label><br>
                <?php if (!empty($profile['photo'])): ?>
                    <img src="uploads/<?php echo $profile['photo']; ?>" alt="Foto Profil" class="profile-photo">
                <?php else: ?>
                    <p>Belum ada foto.</p>
                <?php endif; ?>
                <label for="photo">Ganti Foto Profil</label>
                <input type="file" name="photo" id="photo">
                <input type="hidden" name="current_photo" value="<?php echo $profile['photo']; ?>">
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" value="<?php echo $profile['nama']; ?>">
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Singkat</label>
                <textarea name="deskripsi" id="deskripsi"><?php echo $profile['deskripsi']; ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="tel" name="telepon" id="telepon" value="<?php echo $profile['telepon']; ?>">
                </div>
                <div class="form-group">
                    <label for="website">Website</label>
                    <input type="url" name="website" id="website" value="<?php echo $profile['website']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat"><?php echo $profile['alamat']; ?></textarea>
            </div>
            
            <hr>
            <h4>Media Sosial</h4>
            <div class="form-row">
                <div class="form-group"><label for="github">GitHub</label><input type="url" name="github" id="github" value="<?php echo $profile['github']; ?>"></div>
                <div class="form-group"><label for="linkedln">LinkedIn</label><input type="url" name="linkedln" id="linkedln" value="<?php echo $profile['linkedln']; ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label for="instagram">Instagram</label><input type="url" name="instagram" id="instagram" value="<?php echo $profile['instagram']; ?>"></div>
                <div class="form-group"><label for="tiktok">TikTok</label><input type="url" name="tiktok" id="tiktok" value="<?php echo $profile['tiktok']; ?>"></div>
            </div>
             <div class="form-group">
                <label for="twitter">Twitter/X</label>
                <input type="url" name="twitter" id="twitter" value="<?php echo $profile['twitter']; ?>">
            </div>

            <hr>
            <h4>Lain-lain</h4>
            <div class="form-group">
                <label for="keahlian">Keahlian (pisahkan dengan koma, cth: PHP, Java, UI/UX)</label>
                <textarea name="keahlian" id="keahlian"><?php echo $profile['keahlian']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="hobi">Hobi (pisahkan dengan koma, cth: Ngoding, Futsal, Membaca)</label>
                <textarea name="hobi" id="hobi"><?php echo $profile['hobi']; ?></textarea>
            </div>
            
            <button type="submit">Simpan Profil</button>
        </form>
    </div>
</body>
</html>