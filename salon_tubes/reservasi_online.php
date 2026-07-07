<?php
// reservasi_online.php

// [INFO PHP]: Impor file koneksi database
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi Online - Tito Salon</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Tito<span>Salon</span></h1>
        <nav>
            <a href="index.php">Beranda</a>
            <a href="reservasi_online.php" class="active">Booking Online</a>
            <a href="login.php">Login Pegawai</a>
        </nav>
    </header>

    <div class="main-content" style="padding: 40px 8%;">
        
        <?php 
        // [INFO PHP]: LOGIKA PERCABANGAN KONDISI KONTEN VIEW (IF-ELSE)
        // Cek jika link URL mengandung parameter status sukses (?status=sukses) setelah submit form dari proses.php
        if (isset($_GET['status']) && $_GET['status'] == 'sukses') : 
        ?>
            <div class="form-container" style="text-align: center; max-width: 600px; margin: 40px auto; padding: 50px 40px; animation: fadeInUp 0.6s ease;">
                <div style="font-size: 70px; margin-bottom: 20px;">🎉</div>
                <h3 style="font-family: 'Playfair Display', serif; color: var(--rose-dark); font-size: 28px; margin-bottom: 15px;">Reservasi Online Berhasil!</h3>
                
                <p style="color: var(--text-primary); font-size: 16px; font-weight: 600; margin-bottom: 10px;">
                    Terima kasih telah mempercayakan perawatan kecantikan Anda kepada kami.
                </p>
                <p style="color: var(--text-secondary); font-size: 14.5px; line-height: 1.6; margin-bottom: 35px;">
                    Data booking dengan komitmen DP Rp 50.000 telah tercatat aman di sistem antrean internal Tito Salon. 
                    <br><b style="color: var(--pink-brand);">Mohon untuk datang tepat waktu</b> sesuai dengan jadwal yang telah Anda tentukan. Nikmati layanan premium kami!
                </p>

                <a href="index.php" class="btn" style="width: 100%; border-radius: 12px; padding: 14px; background: linear-gradient(135deg, #EC4899, #BE185D); text-decoration: none;">
                    ↩️ KEMBALI KE BERANDA
                </a>
            </div>

        <?php 
        // JIKA KONDISI B: Kondisi normal (belum isi data), muat formulir isian registrasi awal seperti biasa
        else : 
        ?>
            <div class="form-container">
                <h3>📅 Formulir Reservasi Online Pelanggan</h3>
                <p style="color: var(--text-secondary); margin-bottom: 25px; font-size:14px;">Silakan isi jadwal pengerjaan treatment Anda. Pemesanan online diwajibkan membayar Down Payment (DP) sebesar Rp 50.000 melalui QRIS.</p>
                
                <form action="proses.php" method="POST" id="form-booking-online" style="background:none; padding:0; box-shadow:none; border:none;">
                    
                    <div class="form-group">
                        <label>Nama Lengkap Anda</label>
                        <input type="text" name="nama" id="field-nama" placeholder="Tulis nama lengkap Anda" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Pilih Treatment / Layanan (Bisa Pilih Lebih Dari Satu)</label>
                        <div class="services-checkbox-grid">
                            <label class="checkbox-label"><input type="checkbox" name="jenis_layanan[]" value="Potong Rambut"> Potong Rambut (Rp 100.000)</label>
                            <label class="checkbox-label"><input type="checkbox" name="jenis_layanan[]" value="Warna Rambut"> Warna Rambut (Rp 180.000)</label>
                            <label class="checkbox-label"><input type="checkbox" name="jenis_layanan[]" value="Creambath / Cuci"> Creambath / Cuci (Rp 75.000)</label>
                            <label class="checkbox-label"><input type="checkbox" name="jenis_layanan[]" value="Styling"> Hair Styling (Rp 80.000)</label>
                            <label class="checkbox-label"><input type="checkbox" name="jenis_layanan[]" value="Makeup"> Professional Makeup (Rp 300.000)</label>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Pilih Tanggal Kedatangan</label>
                            <input type="date" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label>Pilih Jam Pengerjaan</label>
                            <input type="time" name="waktu" required>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px;">
                        <label>Total Estimasi Tarif Layanan (Rp)</label>
                        <input type="number" name="harga" id="field-harga-online" placeholder="Akan terhitung otomatis" required readonly style="background: #E5E7EB; cursor: not-allowed;">
                    </div>

                    <div class="kasir-box" style="background: #EFF6FF; border-color: #BFDBFE;">
                        <h4 style="color: #1E40AF; margin-bottom: 10px; font-size: 15px; font-weight: 700;">📱 Sistem Pembayaran Komitmen DP</h4>
                        <p style="font-size:13px; color:#1E40AF; margin-bottom: 15px;">Pemesanan online memerlukan uang muka (DP) flat senilai <b>Rp 50.000</b> sebagai tanda keseriusan booking kursi salon.</p>
                        
                        <div class="kasir-result" style="background: #FFFFFF; border-color: #93C5FD; display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px;">
                            <span style="font-size: 13px; color: var(--text-secondary);">Silakan Scan QRIS Tito Salon Senilai:</span>
                            <strong style="font-size: 24px; color: #1E40AF;">Rp 50.000</strong>
                            <div style="background: #E5E7EB; width: 150px; height: 150px; display:flex; align-items:center; justify-content:center; border-radius: 8px; font-weight: 600; color: #4B5563; border: 2px dashed #93C5FD;">[ QRIS CODE IMAGE ]</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catatan Tambahan Kepada Staf Salon (Opsional)</label>
                        <textarea name="keterangan" rows="3" placeholder="Contoh: Rambut panjang sebahu, request hair stylist tertentu..."></textarea>
                    </div>

                    <button type="submit" name="simpan_booking_online" class="btn" style="width: 100%; border-radius: 12px; margin-top: 10px; background: linear-gradient(135deg, #3B82F6, #1D4ED8); box-shadow: 0 10px 25px rgba(29, 78, 216, 0.3);">KIRIM & RESERVASI TEMPAT</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // [INFO JS]: Kamus daftar harga (Pricelist Dictionary) untuk acuan matematika hitung otomatis
        const pricelist = {
            'Potong Rambut': 100000,
            'Warna Rambut': 180000,
            'Creambath / Cuci': 75000,
            'Styling': 80000,
            'Makeup': 300000
        };

        // [INFO JS]: Fungsi utama kalkulator akumulasi penambahan nilai berdasarkan checkbox yang dicentang user
        function hitungOtomatisTarif() {
            let total = 0;
            // Menemukan seluruh input elemen checkbox layanan yang berstatus dicentang (:checked)
            let checkboxes = document.querySelectorAll('input[name="jenis_layanan[]"]:checked');
            checkboxes.forEach(cb => {
                // Jika nilai checkbox tersebut terdaftar di dalam kamus pricelist di atas
                if (pricelist[cb.value]) {
                    total += pricelist[cb.value]; // Tambahkan nilainya ke total variabel
                }
            });
            let fieldHarga = document.getElementById('field-harga-online');
            if(fieldHarga) {
                fieldHarga.value = total; // Suntikkan hasil penjumlahan akhir ke isian kolom total harga form
            }
        }

        // [INFO JS]: Memasang pendengar event perubahan ('change') pada seluruh komponen checkbox layanan
        document.querySelectorAll('input[name="jenis_layanan[]"]').forEach(cb => {
            cb.addEventListener('change', hitungOtomatisTarif);
        });

        // [INFO JS]: Interseptor tombol klik kirim form - Memvalidasi isian dan memicu tombol Cancel pembatalan
        if(document.getElementById('form-booking-online')) {
            document.getElementById('form-booking-online').addEventListener('submit', function(e) {
                let checkboxes = document.querySelectorAll('input[name="jenis_layanan[]"]:checked');
                // SENSOR WAJIB: Jika panjang array centang sama dengan 0 (artinya belum memilih treatment sama sekali)
                if(checkboxes.length === 0) {
                    alert("Gagal: Anda wajib mencentang minimal salah satu jenis layanan perawatan!");
                    e.preventDefault(); // Gagalkan sistem pengiriman form
                    return false;
                } 
                
                // POP-UP SOLUSI BATAL UTAMA: Menampilkan kotak konfirmasi interaktif dua pilihan tombol (OK / Cancel)
                let tanyaKonfirmasi = confirm("❓ KONFIRMASI PENGIRIMAN RESERVASI\n\nApakah seluruh data jadwal, treatment, dan nominal pembayaran DP Rp 50.000 sudah benar?\n\nPilih [OK] untuk mengirim, atau [Batal] jika ada yang ingin diperiksa kembali.");
                
                // Jikalau user mengklik tombol 'Batal / Cancel', kondisi bernilai false
                if (tanyaKonfirmasi === false) {
                    e.preventDefault(); // Gagalkan pengiriman data, user aman tetap berada di halaman form edit isian
                }
            });
        }
    </script>
</body>
</html>