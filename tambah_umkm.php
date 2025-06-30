<?php
include 'koneksi.php';

// Handle form tambah
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_umkm = trim($_POST['nama_umkm']);
    $link_gmaps = trim($_POST['link_gmaps']);
    $gambar = $_FILES['gambar'];

    if ($nama_umkm && $link_gmaps && $gambar['name']) {
        $target_dir = "assets/img/umkm/";
        $gambar_name = time() . '_' . basename($gambar['name']);
        $target_file = $target_dir . $gambar_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi file gambar
        $check = getimagesize($gambar["tmp_name"]);
        if ($check === false) {
            $error = "File bukan gambar.";
        } elseif ($gambar["size"] > 2000000) {
            $error = "Ukuran gambar terlalu besar (maks 2MB).";
        } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            $error = "Format gambar harus JPG, JPEG, atau PNG.";
        } else {
            if (move_uploaded_file($gambar["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO umkm (nama_umkm, gambar, link_gmaps) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $nama_umkm, $gambar_name, $link_gmaps);
                if ($stmt->execute()) {
                    $success = "UMKM berhasil ditambahkan.";
                } else {
                    $error = "Gagal menyimpan ke database.";
                }
                $stmt->close();
            } else {
                $error = "Gagal mengupload gambar.";
            }
        }
    } else {
        $error = "Semua field harus diisi.";
    }
}

// Handle hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $get = mysqli_query($conn, "SELECT gambar FROM umkm WHERE id = $id");
    $data = mysqli_fetch_assoc($get);
    if ($data && file_exists("assets/img/umkm/" . $data['gambar'])) {
        unlink("assets/img/umkm/" . $data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM umkm WHERE id = $id");
    header("Location: tambah_umkm.php");
    exit;
}

// Ambil semua data UMKM
$umkm = mysqli_query($conn, "SELECT * FROM umkm ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<?php include 'navbar.php'; ?>

<body>
    <section>
        <div class="container mt-5">
            <h3 class="mb-4">Tambah UMKM</h3>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="mb-5">
                <div class="mb-3">
                    <label class="form-label">Nama UMKM</label>
                    <input type="text" name="nama_umkm" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Link Google Maps</label>
                    <input type="url" name="link_gmaps" class="form-control" required
                        placeholder="https://maps.app.goo.gl/...">
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Gambar</label>
                    <P class="form-text">*Utamakan gambar landscape</P>
                    <input type="file" name="gambar" class="form-control" accept="image/*" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>

            <h4 class="mb-3">Daftar UMKM</h4>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama UMKM</th>
                        <th>Gambar</th>
                        <th>Link Google Maps</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($umkm)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_umkm']) ?></td>
                            <td>
                                <?php if ($row['gambar']): ?>
                                    <img src="assets/img/umkm/<?= htmlspecialchars($row['gambar']) ?>" width="80">
                                <?php else: ?>
                                    Tidak ada
                                <?php endif; ?>
                            </td>
                            <td><a href="<?= htmlspecialchars($row['link_gmaps']) ?>" target="_blank">Lihat</a></td>
                            <td>
                                <a href="edit_umkm.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')"
                                    class="btn btn-danger btn-sm">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include 'footer2.php'; ?>
</body>

</html>