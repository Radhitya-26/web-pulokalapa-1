<?php
include 'koneksi.php';

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $platform = $_POST['platform'];
    $url = trim($_POST['url']);
    $caption = trim($_POST['caption']);
    $video_id = null;

    if ($platform === 'tiktok') {
        // Ambil video ID dari URL TikTok (contoh: https://www.tiktok.com/@user/video/1234567890)
        if (preg_match('/video\/(\d+)/', $url, $matches)) {
            $video_id = $matches[1];
        } else {
            $error = "URL TikTok tidak valid.";
        }
    }

    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO video_dokumentasi (platform, video_id, url, caption) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $platform, $video_id, $url, $caption);

        if ($stmt->execute()) {
            $success = "Video berhasil ditambahkan.";
        } else {
            $error = "Gagal menambahkan video: " . $conn->error;
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
        <h3>Tambah Video Dokumentasi</h3>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="platform" class="form-label">Platform</label>
                <select class="form-select" name="platform" id="platform" required>
                    <option value="">-- Pilih Platform --</option>
                    <option value="tiktok">TikTok</option>
                    <option value="instagram">Instagram</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">URL Video</label>
                <input type="url" class="form-control" name="url" id="url" required
                    placeholder="https://www.tiktok.com/..." />
            </div>

            <div class="mb-3">
                <label for="caption" class="form-label">Caption</label>
                <textarea class="form-control" name="caption" id="caption" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <?php include 'footer2.php'; ?>
</body>

</html>