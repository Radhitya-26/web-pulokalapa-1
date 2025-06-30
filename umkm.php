<?php
include 'koneksi.php';

// Konfigurasi pagination
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM umkm");
$total_row = mysqli_fetch_assoc($total_result);
$total_data = $total_row['total'];
$total_pages = ceil($total_data / $limit);

// Ambil data sesuai halaman
$query = "SELECT * FROM umkm ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<?php include 'navbar.php'; ?>

<body>
    <div class="container mt-5">
        <header class="section-header">
            <br><br>
            <p>Daftar UMKM Desa</p>
        </header>

        <div class="row justify-content-center">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($row['gambar']) && file_exists("assets/img/umkm/" . $row['gambar'])): ?>
                            <img src="assets/img/umkm/<?= htmlspecialchars($row['gambar']) ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($row['nama_umkm']) ?>">
                        <?php else: ?>
                            <img src="assets/img/umkm/default.png" class="card-img-top" alt="Gambar default">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($row['nama_umkm']) ?></h5>
                            <p class="card-text">Klik tombol di bawah untuk melihat lokasi di Google Maps.</p>
                            <a href="<?= htmlspecialchars($row['link_gmaps']) ?>" target="_blank"
                                class="btn btn-success mt-auto">
                                Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- PAGINATION -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">«</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">»</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <?php include 'footer2.php'; ?>
</body>
</html>
