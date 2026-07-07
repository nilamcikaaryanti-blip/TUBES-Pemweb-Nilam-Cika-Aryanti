<?php
// koneksi.php

// 1. KODE KONFIGURASI ALAMAT SERVER DAN IDENTITAS DATABASE
$host = "localhost";        // Nama host server database (bawaan XAMPP)
$user = "root";             // Username default untuk masuk ke MySQL server
$pass = "";                 // Password default server MySQL (kosong pada XAMPP)
$db   = "manajemen_salon";  // Nama database target yang menyimpan tabel data

// 2. KODE EKSEKUSI PERINTAH HUBUNGAN JARINGAN DATABASE
$conn = mysqli_connect($host, $user, $pass, $db);

// 3. KODE CEK KONDISI JIKA HUBUNGAN DATABASE GAGAL / ERROR
if (!$conn) {
    // Menghentikan paksa sistem dan menampilkan pesan galat spesifik dari MySQL
    die("Koneksi ke database manajemen_salon gagal: " . mysqli_connect_error());
}
?>