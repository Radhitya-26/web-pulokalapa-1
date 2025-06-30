<?php
$host     = "localhost";       // Host database (biasanya localhost)
$user     = "root";            // Username MySQL
$password = "";                // Password MySQL (kosong jika default XAMPP)
$database = "pulokalapa";      // Nama database kamu

// Buat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>
