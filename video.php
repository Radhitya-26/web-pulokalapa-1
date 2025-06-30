<?php
include 'koneksi.php';

// Konfigurasi pagination
$perPage = 6;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

// Ambil data video dari database
$sql = "SELECT * FROM video_dokumentasi ORDER BY created_at DESC LIMIT $perPage OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Hitung total data
$totalQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM video_dokumentasi");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];
$totalPages = ceil($totalData / $perPage);
?>

<!DOCTYPE html>
<html lang="id">
<?php include 'navbar.php'; ?>

<body>

<style>
    .embed-wrapper {
        max-width: 100%;
        width: 100%;
        height: 480px;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card .card-body {
        flex-grow: 1;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 767px) {
        .embed-wrapper {
            height: 400px;
        }
    }
</style>

<section id="portfolio" class="portfolio">
    <div class="container" data-aos="fade-up">

        <header class="section-header">
            <br>
            <p>Video Dokumentasi</p>
        </header>

        <div class="row justify-content-center gx-4">
            <?php while ($video = mysqli_fetch_assoc($result)) : ?>
                <div class="col-sm-6 col-md-4 mb-4 d-flex align-items-stretch">
                    <div class="card w-100 d-flex flex-column">
                        <div class="embed-wrapper">
                            <?php if ($video['platform'] === 'tiktok'): ?>
                                <blockquote class="tiktok-embed"
                                    cite="<?= $video['url'] ?>"
                                    data-video-id="<?= $video['video_id'] ?>"
                                    style="max-width: 325px; min-width: 300px;">
                                    <section>Loading...</section>
                                </blockquote>
                            <?php elseif ($video['platform'] === 'instagram'): ?>
                                <blockquote class="instagram-media"
                                    data-instgrm-permalink="<?= $video['url'] ?>"
                                    data-instgrm-version="14"
                                    style="background:#FFF; border:0; max-width:325px; width:100%;">
                                </blockquote>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= $video['caption'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</section>

<script async src="https://www.tiktok.com/embed.js"></script>
<script async src="//www.instagram.com/embed.js"></script>

<?php include 'footer2.php'; ?>
</body>
</html>
                    