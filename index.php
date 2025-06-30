<!-- index.php -->
<!DOCTYPE html>
<html lang="en">


<?php include 'navbar.php'; ?>
<body>
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="hero d-flex align-items-center">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column justify-content-center">
                    <h1 data-aos="fade-up">Selamat Datang Di Website Desa Pulokalapa</h1>
                    <h2>Kec. Lemahabang Kab. Karawang</h2>
                    <div data-aos="fade-up" data-aos-delay="600">
                        <div class="text-center text-lg-start">
                            <a href="#about"
                                class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
                                <span>Tentang Kami</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
                    <img src="assets/img/Silmi_Yushini.png" class="img-fluid" alt="foto-kades">
                </div>
            </div>
        </div>

    </section><!-- End Hero -->

    <main id="main">
        <!-- ======= About Section ======= -->
        <section id="about" class="about">

            <div class="container" data-aos="fade-up">
                <header class="section-header">
                    <p>Profil Desa</p>
                </header>
                <div class="row gx-0">
                    <div class="col-lg-6 d-flex flex-column justify-content-center" data-aos="fade-up"
                        data-aos-delay="200">
                        <div class="content">
                            <h2>Sejarah Desa</h2>
                            <h2></h2>
                            <p>
                                Desa Pulokalapa terletak di Kecamatan Lemahabang, Kab. Karawang.
                            </p>
                            <div class="text-center text-lg-start">
                                <a href="sejarah.php"
                                    class="btn-read-more d-inline-flex align-items-center justify-content-center align-self-center">
                                    <span>Read More</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
                        <img src="assets/img/kapula-.jpg" class="img-fluid" alt="foto_bersama_perangkat_desa">
                    </div>

                </div>
            </div>

        </section><!-- End About Section -->


        <!-- ======= Recent Blog Posts Section ======= -->
        <section id="recent-blog-posts" class="recent-blog-posts">
            <div class="container" data-aos="fade-up">

                <header class="section-header">
                    <p>Berita</p>
                </header>

                <div class="row">

                    <?php
                    include 'koneksi.php';

                    // Ambil 4 berita terbaru
                    $query = "SELECT * FROM berita ORDER BY id DESC LIMIT 5";
                    $result = $conn->query($query);

                    while ($row = $result->fetch_assoc()) {
                        $judul = $row['judul'];
                        $gambar = $row['gambar'];
                        $id = $row['id'];
                        $isi = substr(strip_tags($row['isi']), 0, 70) . '...';
                        echo '
<div class="col-lg-4">
  <div class="post-box">
    <div class="post-img">
      <img src="assets/img/berita/' . htmlspecialchars($gambar) . '" class="img-fluid w-100" alt="' . htmlspecialchars($judul) . '">
    </div>
    <span class="post-date">Berita Desa</span>
    <h3 class="post-title">' . htmlspecialchars($judul) . '</h3>
    <p>' . htmlspecialchars($isi) . '</p>
    <a href="berita.php?id=' . $id . '" class="readmore stretched-link mt-auto">
      <span>Lihat</span><i class="bi bi-arrow-right"></i>
    </a>
  </div>
</div>
';

                    }

                    $conn->close();
                    ?>

                </div>
            </div>
        </section>
        <!-- End Recent Blog Posts Section -->


        <!-- ======= Contact Section ======= -->
        <section id="lokasi" class="lokasi">

            <div class="container" data-aos="fade-up">

                <header class="section-header">
                    <p>LOKASI</p>
                </header>

                <div class="row gy-4">

                    <div class="col-lg-6">

                        <div class="row gy-4">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15865.74274984258!2d107.4837595!3d-6.249753149999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69744bdb3ff183%3A0x23d15c99f8f6b546!2sPulokalapa%2C%20Kec.%20Lemahabang%2C%20Kabupaten%20Karawang%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1719212109571!5m2!1sid!2sid"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>

                    </div>

                    <div class="col-lg-6">
                        <h3>Lokasi Desa Pulokalapa</h3>
                        <p>Desa Pulokalapa terletak di Kecamatan Lemahabang, Kabupaten Karawang, Jawa Barat. Desa ini
                            dapat diakses melalui jalan utama dari Karawang kota dan terhubung dengan beberapa desa
                            sekitarnya seperti Desa Ciwaringin, Karangtanjung, Karyamukti, Kedawung, Lemahabang,
                            Lemahmukti, Pasirtanjung, Pulojaya, Pulomulya, dan Waringinkarya.</p>
                        <ul>
                            <li>Jarak dari pusat kota Karawang: ± 25 km</li>
                            <li>Waktu tempuh: Sekitar 40 menit</li>
                            <li>Transportasi umum: Tersedia angkutan desa</li>
                        </ul>

                    </div>

                </div>

            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->

    <?php include 'footer.php'; ?>

</body>

</html>