<?php
// login.php

// [INFO PHP]: Memulai penanganan session untuk mendeteksi status login user
session_start();

// [INFO PHP]: Mengimpor file koneksi agar script bisa mencocokkan akun ke database
include 'koneksi.php';

// [INFO PHP]: Proteksi awal - Jika user sudah berstatus login, langsung dilempar ke dashboard admin.php
if (isset($_SESSION['admin_logged'])) {
    header("Location: admin.php");
    exit;
}

// [INFO PHP]: Variabel untuk menampung teks peringatan jika login gagal
$pesan_error = "";

// [INFO PHP]: Mendeteksi apakah tombol login (proses_login) sudah ditekan oleh kasir
if (isset($_POST['proses_login'])) {
    // Mengamankan inputan text agar terhindar dari serangan hacker (SQL Injection)
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk memeriksa tabel admin_user berdasarkan username dan password yang diinput
    $result = mysqli_query($conn, "SELECT * FROM admin_user WHERE username = '$username' AND password = '$password'");
    
    // Jika jumlah baris yang ditemukan sama dengan 1 (artinya akun terdaftar cocok)
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Pembuatan Session Hak Akses berdasarkan username yang valid
        $_SESSION['admin_logged'] = $row['username'];
        
        // Pembuatan Cookie pengingat di browser yang berlaku selama 24 jam (86400 detik)
        setcookie('recent_admin', $row['username'], time() + 86400, "/");

        // Alihkan halaman secara otomatis masuk ke halaman utama admin.php
        header("Location: admin.php");
        exit;
    } else {
        // Jika akun salah, isi variabel pesan_error untuk ditampilkan ke user
        $pesan_error = "Maaf, Kombinasi Akun Tidak Valid!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tito Salon - Gateway Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: var(--bg-main); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">

    <div class="form-container" style="max-width: 420px; width: 100%; box-shadow: var(--shadow-soft); padding: 45px 35px; border-radius: var(--radius-md);">
        
        <h3 style="text-align: center; font-size: 24px; color: var(--rose-dark); margin-bottom: 8px; font-family: 'Playfair Display', serif;">Gateway Otentikasi</h3>
        <p style="text-align: center; font-size: 13.5px; color: var(--text-secondary); margin-bottom: 30px;">Masukkan kredensial otorisasi sistem internal</p>
        
        <?php if($pesan_error != ""): ?>
            <div style="background-color: #FEE2E2; color: #EF4444; padding: 14px; border-radius: 12px; text-align: center; font-weight: 600; margin-bottom: 20px; font-size: 13.5px; border: 1px solid #FCA5A5;">
                <?= $pesan_error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>ID Administrator / Staf</label>
                <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>
            
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" name="proses_login" class="btn" style="margin-top: 10px; border-radius: 12px; width: 100%; padding: 14px; background: linear-gradient(135deg, var(--rose-medium), var(--rose-dark)); font-weight: 700;">
                MASUK KE DASHBOARD
            </button>
        </form>
    </div>

</body>
</html>