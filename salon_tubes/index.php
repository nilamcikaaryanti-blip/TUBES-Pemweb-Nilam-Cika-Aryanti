<?php
// index.php
$welcome_cookie = "";
if (isset($_COOKIE['recent_admin'])) {
    $welcome_cookie = "Sesi Terakhir: Admin " . htmlspecialchars($_COOKIE['recent_admin']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tito Salon - Premium Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Tito<span>Salon</span></h1>
        <nav>
            <a href="index.php" class="active">Beranda</a>
            <a href="#fitur">Panduan Sistem</a>
            <a href="reservasi_online.php" style="color: var(--pink-brand); font-weight: 700;">📅 Booking Online (Pelanggan)</a>
            <a href="login.php">Login Admin & Staf</a>
        </nav>
    </header>

    <div class="hero-section">
        <div class="hero-text">
            <?php if ($welcome_cookie != ""): ?>
                <span style="background: var(--pink-soft); padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 600; color: var(--pink-brand); display: inline-block; margin-bottom: 20px; border: 1px solid var(--pink-pastel);"><?= $welcome_cookie; ?></span>
            <?php endif; ?>
            <h2>Elegansi Dalam Manajemen Operasional Salon</h2>
            <p>Selamat datang di platform administrasi internal Tito Salon. Sistem ini didesain eksklusif untuk mendigitalisasi logbook operasional, memudahkan pengelolaan penjadwalan treatment, alokasi layanan kecantikan, serta monitoring efisiensi antrean pelanggan secara waktu nyata.</p>
            
            <div style="background: #FFF1F2; border-left: 4px solid var(--pink-brand); padding: 15px 20px; border-radius: 8px; margin-bottom: 30px; font-size: 14px; line-height: 1.5; color: var(--rose-dark);">
                📢 <b>Bagi Pengunjung & Pelanggan:</b> Ingin melakukan perawatan tanpa mengantre? Silakan klik tombol di bawah atau menu di atas untuk melakukan <b>Reservasi/Booking Online</b> secara mandiri kapan saja.
            </div>

            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="reservasi_online.php" class="btn" style="background: linear-gradient(135deg, #EC4899, #BE185D);">📅 Booking Online Sekarang</a>
                <a href="login.php" class="btn" style="background: linear-gradient(135deg, #4B5563, #1F2937); box-shadow: 0 10px 25px rgba(31, 41, 55, 0.2);">Akses Kontrol Admin</a>
            </div>
        </div>
        <div class="hero-image-wrapper">
            <img src="Cartoon Backstage Makeup Artist PNG Images,  Cartoon Clipart, Makeup Clipart, Artist Clipart PNG Transparent Background - Pngtree.jpg" alt="Luxury Salon Visual" class="img-salon">
        </div>
    </div>

    <!-- SEKSI INFORMASI OUTLET UNTUK PELANGGAN -->
    <div style="max-width: 1200px; margin: 0 auto 60px auto; width: 90%; background: #FFFFFF; border: 1px solid var(--pink-soft); border-radius: var(--radius-md); padding: 30px; box-shadow: var(--shadow-soft);">
        <h4 style="font-family: 'Playfair Display', serif; color: var(--rose-dark); font-size: 22px; margin-bottom: 20px; text-align: center;">📍 Informasi Operasional Outlet</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; text-align: center;">
            <div style="background: var(--bg-main); padding: 15px; border-radius: 12px;">
                <span style="font-size: 20px;">🏢</span>
                <h5 style="color: var(--rose-dark); margin: 5px 0;">Alamat Outlet</h5>
                <p style="color: var(--text-secondary); font-size: 14px;">Jl. XXX</p>
            </div>
            <div style="background: var(--bg-main); padding: 15px; border-radius: 12px;">
                <span style="font-size: 20px;">💬</span>
                <h5 style="color: var(--rose-dark); margin: 5px 0;">Hubungi Kami</h5>
                <p style="color: var(--text-secondary); font-size: 14px;">WhatsApp: 08XXX</p>
            </div>
            <div style="background: var(--bg-main); padding: 15px; border-radius: 12px;">
                <span style="font-size: 20px;">🕒</span>
                <h5 style="color: var(--rose-dark); margin: 5px 0;">Jam Operasional</h5>
                <p style="color: var(--text-secondary); font-size: 14px;">Setiap Hari (09:00 - 21:00 WITA)</p>
            </div>
        </div>
    </div>

    <!-- PANDUAN SISTEM YANG SUDAH DIBAGI PER KATEGORI USER -->
    <div class="management-section" id="fitur">
        <h3>Pusat Informasi & Panduan Sistem</h3>
        <p class="management-sub">Portal petunjuk langkah penggunaan fitur aplikasi harian bagi Pelanggan Umum dan Staf Administrasi Salon.</p>
        
        <!-- ================= BAGIAN 1: PANDUAN PELANGGAN ================= -->
        <h4 style="font-family: 'Playfair Display', serif; color: var(--pink-brand); font-size: 20px; margin: 40px 0 20px 0; text-align: left; border-bottom: 2px solid var(--pink-soft); padding-bottom: 8px;">✨ Kategori: Panduan & Reservasi Pelanggan</h4>
        <div class="management-grid">
            <div class="management-card" style="border-top: 4px solid #EC4899;">
                <div class="icon">📱</div>
                <h4>1. Pilih Layanan & Jadwal</h4>
                <p>Buka halaman Booking Online, pilih satu atau beberapa treatment kecantikan yang diinginkan, serta tentukan tanggal dan jam kedatangan Anda.</p>
            </div>
            
            <div class="management-card" style="border-top: 4px solid #EC4899;">
                <div class="icon">💳</div>
                <h4>2. Pembayaran DP Awal</h4>
                <p>Sistem otomatis menghitung total biaya. Lakukan scan QRIS sebesar Rp 50.000 sebagai uang muka (DP) komitmen pemesanan tempat salon.</p>
            </div>
            
            <div class="management-card" style="border-top: 4px solid #EC4899;">
                <div class="icon">🚀</div>
                <h4>3. Datang & Pelunasan</h4>
                <p>Data langsung masuk ke Buku Log Reservasi salon. Datanglah tepat waktu, nikmati perawatan, dan bayar sisa tagihan (Total kotor dikurangi DP) di kasir.</p>
            </div>
        </div>

        <!-- ================= BAGIAN 2: PANDUAN OPERASIONAL STAF ================= -->
        <h4 style="font-family: 'Playfair Display', serif; color: #4B5563; font-size: 20px; margin: 50px 0 20px 0; text-align: left; border-bottom: 2px solid #E5E7EB; padding-bottom: 8px;">🛠️ Kategori: Kendali Operasional Staf</h4>
        <div class="management-grid">
            <div class="management-card">
                <div class="icon">📝</div>
                <h4>Log Kasir & Proteksi</h4>
                <p>Mencatat transaksi walk-in serta memproses sisa pelunasan keuangan. Sistem mendeteksi otomatis sisa tagihan agar tidak terjadi kelalaian.</p>
            </div>
            
            <div class="management-card">
                <div class="icon">📊</div>
                <h4>Manajemen Kursi Kerja</h4>
                <p>Mengatur alur antrean harian pelanggan (Waiting/Proses) agar seimbang dengan batas maksimal kapasitas operasional harian (7 kursi).</p>
            </div>
            
            <div class="management-card">
                <div class="icon">📅</div>
                <h4>Manajemen Log Reservasi</h4>
                <p>Memantau antrean pemesanan online maupun offline. Sistem mengunci status agar data tidak bisa diproses sebelum sisa uang dibayar lunas.</p>
            </div>
            
            <div class="management-card" style="background: linear-gradient(135deg, #FFF1F2, var(--pink-soft)); border-color: var(--pink-pastel);">
                <div class="icon" style="background: var(--pink-brand); color:#fff;">📞</div>
                <h4 style="color: var(--rose-dark);">Kontak Sistem IT</h4>
                <p style="margin-bottom: 8px;">Mengalami kendala bug database, error sistem, atau lupa kata sandi login?</p>
                <div style="margin-top: auto; font-size: 12.5px; font-weight: 700; color: var(--pink-brand);">
                    📍 WhatsApp: 0812-3456-7890<br>
                    ✉️ Email: support@titosalon.com
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Tito Salon Luxury Network. All Rights Reserved.</p>
    </footer>
</body>
</html>