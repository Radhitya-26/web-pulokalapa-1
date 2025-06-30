<?php
include 'koneksi.php';

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM umkm WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data UMKM tidak ditemukan.");
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_umkm']);
    $link = trim($_POST['link_gmaps']);

    $gambarBaru = $_FILES['gambar'];
    $gambarFinal = $data['gambar'];

    // Jika upload gambar baru
    if ($gambarBaru['name']) {
        $folder = "assets/img/umkm/";
        $namaFileBaru = time() . '_' . basename($gambarBaru['name']);
        $targetPath = $folder . $namaFileBaru;
        $imageType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));

        if (!in_array($imageType, ['jpg', 'jpeg', 'png'])) {
            $error = "Format gambar harus JPG, JPEG, atau PNG.";
        } elseif ($gambarBaru["size"] > 2000000) {
            $error = "Ukuran gambar maksimal 2MB.";
        } else {
            // hapus gambar lama
            if ($data['gambar'] && file_exists($folder . $data['gambar'])) {
                unlink($folder . $data['gambar']);
            }

            // simpan gambar baru
            if (move_uploaded_file($gambarBaru['tmp_name'], $targetPath)) {
                $gambarFinal = $namaFileBaru;
            } else {
                $error = "Gagal upload gambar baru.";
            }
        }
    }

    // Update DB jika tidak ada error
    if (!$error) {
        $stmt = $conn->prepare("UPDATE umkm SET nama_umkm=?, link_gmaps=?, gambar=? WHERE id=?");
        $stmt->bind_param("sssi", $nama, $link, $gambarFinal, $id);

        if ($stmt->execute()) {
            $success = "Data berhasil diperbarui.";
            header("Location: tambah_umkm.php");
            exit;
        } else {
            $error = "Gagal update data.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<?php include 'navbar.php'; ?>

<body>
<div class="container mt-5">
    <h3>Edit UMKM</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nama UMKM</label>
            <input type="text" name="nama_umkm" class="form-control" value="<?= htmlspecialchars($data['nama_umkm']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Link Google Maps</label>
            <input type="url" name="link_gmaps" class="form-control" value="<?= htmlspecialchars($data['link_gmaps']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Saat Ini</label><br>
            <img src="assets/img/umkm/<?= htmlspecialchars($data['gambar']) ?>" width="120"><br><br>
            <label>Ganti Gambar (opsional)</label>
            <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="tambah_umkm.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include 'footer2.php'; ?>
</body>
</html>
