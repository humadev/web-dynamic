<?php include 'header.php'; ?>
// Ambil ID mahasiswa dari parameter URL
$mahasiswa_id = $_GET['id'] ?? null;

// Jika tidak ada ID, kembalikan ke halaman daftar
if (!$mahasiswa_id || !is_numeric($mahasiswa_id)) {
    header('Location: daftar-mahasiswa.php');
    exit;
}

// Ambil data lengkap mahasiswa dari database berdasarkan ID
try {
    $stmt = $pdo->prepare("SELECT * FROM mahasiswa WHERE id = ?");
    $stmt->execute([$mahasiswa_id]);
    $profil = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error mengambil data profil: " . $e->getMessage());
}

// Jika profil dengan ID tersebut tidak ditemukan
if (!$profil) {
    // Anda bisa membuat halaman 404 yang lebih baik
    die("Profil mahasiswa tidak ditemukan.");
}
?>
    <div class="profile-container">
        <aside class="profile-sidebar">
            <?php if (!empty($profil['photo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($profil['photo']); ?>" alt="Foto Profil">
            <?php else: ?>
                <img src="https://via.placeholder.com/150" alt="Foto Profil">
            <?php endif; ?>
            
            <h1><?php echo htmlspecialchars($profil['nama']); ?></h1>
            
            <ul class="contact-info">
                <?php if(!empty($profil['telepon'])): ?>
                    <li><i class="fas fa-phone"></i> <?php echo htmlspecialchars($profil['telepon']); ?></li>
                <?php endif; ?>
                <?php if(!empty($profil['alamat'])): ?>
                    <li><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($profil['alamat']); ?></li>
                <?php endif; ?>
                 <?php if(!empty($profil['website'])): ?>
                    <li><i class="fas fa-globe"></i> <a href="<?php echo htmlspecialchars($profil['website']); ?>" target="_blank">Website Pribadi</a></li>
                <?php endif; ?>
            </ul>

            <div class="social-media">
                <?php if(!empty($profil['instagram'])): ?><a href="<?php echo htmlspecialchars($profil['instagram']); ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
                <?php if(!empty($profil['linkedln'])): ?><a href="<?php echo htmlspecialchars($profil['linkedln']); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                <?php if(!empty($profil['github'])): ?><a href="<?php echo htmlspecialchars($profil['github']); ?>" target="_blank"><i class="fab fa-github"></i></a><?php endif; ?>
                <?php if(!empty($profil['tiktok'])): ?><a href="<?php echo htmlspecialchars($profil['tiktok']); ?>" target="_blank"><i class="fab fa-tiktok"></i></a><?php endif; ?>
                <?php if(!empty($profil['twitter'])): ?><a href="<?php echo htmlspecialchars($profil['twitter']); ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php endif; ?>
            </div>
        </aside>

        <main class="profile-main">
            <section class="profile-section">
                <h3><i class="fas fa-user-circle"></i> Tentang Saya</h3>
                <p><?php echo nl2br(htmlspecialchars($profil['deskripsi'])); ?></p>
            </section>

            <section class="profile-section">
                <h3><i class="fas fa-cogs"></i> Keahlian</h3>
                <ul class="tags-list">
                    <?php 
                        $keahlian = !empty($profil['keahlian']) ? explode(',', $profil['keahlian']) : [];
                        foreach ($keahlian as $skill):
                    ?>
                        <li><?php echo htmlspecialchars(trim($skill)); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="profile-section">
                <h3><i class="fas fa-paint-brush"></i> Hobi</h3>
                <ul class="tags-list">
                    <?php 
                        $hobi = !empty($profil['hobi']) ? explode(',', $profil['hobi']) : [];
                        foreach ($hobi as $hobby):
                    ?>
                        <li><?php echo htmlspecialchars(trim($hobby)); ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
             <a href="daftar-mahasiswa.php" class="back-link">Kembali ke Daftar Mahasiswa</a>
        </main>
    </div>
<?php include 'footer.php'; ?>