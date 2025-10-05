<?php
include 'header.php';
try {
    $stmt = $pdo->query("SELECT id, nama, keahlian, photo FROM mahasiswa ORDER BY nama ASC");
    $mahasiswa_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Tidak bisa mengambil data mahasiswa: " . $e->getMessage());
}
?>
<div class="main-container">
    <div class="list-header">
        <h1>Daftar Profil Mahasiswa</h1>
    </div>

    <?php if (empty($mahasiswa_list)): ?>
        <p>Belum ada profil mahasiswa yang dibuat.</p>
    <?php else: ?>
        <div class="mahasiswa-grid">
            <?php foreach ($mahasiswa_list as $mhs): ?>
                <a href="profil.php?id=<?php echo $mhs['id']; ?>" class="mahasiswa-card">
                    <div class="card-photo-container">
                        <?php if (!empty($mhs['photo'])): ?>
                            <img src="photos/<?php echo htmlspecialchars($mhs['photo']); ?>" alt="Foto <?php echo htmlspecialchars($mhs['nama']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/150" alt="Foto tidak tersedia">
                        <?php endif; ?>
                    </div>
                    <div class="card-info">
                        <h3><?php echo htmlspecialchars($mhs['nama']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($mhs['keahlian'], 0, 40)) . '...'; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>