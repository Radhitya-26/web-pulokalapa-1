<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query berita
$sql = "SELECT * FROM berita WHERE id = $id";
$result = $conn->query($sql);

// Jika data ditemukan
if ($result->num_rows > 0) {
  $berita = $result->fetch_assoc();
} else {
  echo "Berita tidak ditemukan.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<?php include 'navbar.php'; ?>
<body>

<main style="padding: 40px 20px; max-width: 800px; margin: auto;">
  
<br><br><br><br><br><br>
<h1><?php echo $berita['judul']; ?></h1>
  <p><em>Dipublikasikan oleh Desa Pulokalapa</em></p>
  <img src="assets/img/berita/<?php echo $berita['gambar']; ?>" alt="Gambar Berita" style="width: 100%; max-height: 400px; object-fit: cover; margin-bottom: 20px;">
  <div style="line-height: 1.8; font-size: 1.1em;">
    <?php
      // Pisahkan per paragraf (jika isinya disimpan dengan baris baru)
      $paragraf = explode("\n", $berita['isi']);
      foreach ($paragraf as $p) {
        if (trim($p)) echo "<p>" . nl2br(htmlspecialchars($p)) . "</p>";
      }
    ?>
  </div>
</main>

<?php include 'footer2.php'; ?>

</body>
</html>
