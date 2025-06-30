<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$host = 'localhost';
$db   = 'pulokalapaa';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$message = '';

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_berita'])) {
    $judul = $_POST['judul'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';
    $isi = $_POST['isi'] ?? '';
    $gambar = null;

    if (empty($judul) || empty($tanggal) || empty($isi)) {
        $message = 'Semua field kecuali gambar wajib diisi.';
    } else {
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['gambar']['tmp_name'];
            $fileName = $_FILES['gambar']['name'];
            $fileSize = $_FILES['gambar']['size'];
            $fileType = $_FILES['gambar']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../assets/img/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $gambar = $newFileName;
                } else {
                    $message = 'Terjadi kesalahan saat mengupload gambar.';
                }
            } else {
                $message = 'Format gambar tidak diperbolehkan. Hanya jpg, jpeg, png, gif yang diizinkan.';
            }
        }

        if (empty($message)) {
            $stmt = $pdo->prepare('INSERT INTO berita (judul, tanggal, gambar, isi) VALUES (?, ?, ?, ?)');
            $stmt->execute([$judul, $tanggal, $gambar, $isi]);
            $message = 'Berita berhasil ditambahkan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Desa Pulokalapa</title>
    <!-- SB Admin 2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .modal-backdrop { z-index: 1040 !important; }
        .modal { z-index: 1100 !important; }
        .message { margin-top: 20px; padding: 10px; border-radius: 4px; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Pulokalapa</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#tambahBeritaModal">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>Tambah Berita</span></a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span></a>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Selamat Datang, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php if ($message): ?>
                        <div class="message <?php echo strpos($message, 'berhasil') !== false ? 'success' : 'error'; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Daftar Berita -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Berita</h6>
                        </div>
                        <div class="card-body">
                        <?php
                        $stmt = $pdo->query('SELECT * FROM berita ORDER BY tanggal DESC, id DESC');
                        $beritaList = $stmt->fetchAll();
                        if (count($beritaList) === 0) {
                            echo '<p>Belum ada berita.</p>';
                        } else {
                            echo '<div class="table-responsive">';
                            echo '<table class="table table-bordered" width="100%" cellspacing="0">';
                            echo '<thead><tr><th>Judul</th><th>Tanggal</th><th>Gambar</th><th>Isi</th></tr></thead><tbody>';
                            foreach ($beritaList as $berita) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($berita['judul']) . '</td>';
                                echo '<td>' . htmlspecialchars($berita['tanggal']) . '</td>';
                                if ($berita['gambar']) {
                                    echo '<td><img src="../assets/img/' . htmlspecialchars($berita['gambar']) . '" alt="Gambar" style="max-width:60px; max-height:60px;"></td>';
                                } else {
                                    echo '<td>-</td>';
                                }
                                echo '<td style="max-width:200px; overflow:auto;">' . nl2br(htmlspecialchars($berita['isi'])) . '</td>';
                                echo '</tr>';
                            }
                            echo '</tbody></table></div>';
                        }
                        ?>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Tambah Berita Modal -->
    <div class="modal fade" id="tambahBeritaModal" tabindex="-1" role="dialog" aria-labelledby="tambahBeritaModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="tambahBeritaModalLabel">Tambah Berita Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="modal-body">
                <label for="judul">Judul:</label>
                <input type="text" id="judul" name="judul" class="form-control" required>

                <label for="tanggal" class="mt-2">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control" required>

                <label for="gambar" class="mt-2">Gambar (opsional):</label>
                <input type="file" id="gambar" name="gambar" class="form-control"   accept=".jpg,.jpeg,.png,.gif">

                <label for="isi" class="mt-2">Isi Berita:</label>
                <textarea id="isi" name="isi" rows="5" class="form-control" required></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <input type="submit" name="submit_berita" class="btn btn-primary" value="Tambah Berita">
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- SB Admin 2 JS, jQuery, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
</body>
</html>
