<?php
// admin.php

// [INFO PHP]: Membuka session penanda login admin
session_start();
// [INFO PHP]: Menghubungkan script database harian
include 'koneksi.php';

// [INFO PHP]: Proteksi Gerbang - Jika sesi kosong/tidak ada, langsung ditolak dan dilempar ke login.php
if (!isset($_SESSION['admin_logged'])) {
    header("Location: login.php");
    exit;
}

// [INFO PHP]: Mendeteksi sub-halaman modular yang sedang dibuka (Default: tabel antrean)
$page = isset($_GET['page']) ? $_GET['page'] : 'tabel';

// [INFO PHP]: Proteksi Hak Akses Staf - Jika user 'staf' mencoba meretas menu riwayat via ketik URL, paksa tendang keluar
if ($_SESSION['admin_logged'] !== 'admin' && $page === 'riwayat') {
    header("Location: admin.php?page=tabel");
    exit;
}

// [INFO PHP]: Logika mengambil rekam resep data terpilih untuk dimuat kembali ke Form (Fungsi Edit Data)
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $res_edit = mysqli_query($conn, "SELECT * FROM pelanggan WHERE id = $edit_id");
    if (mysqli_num_rows($res_edit) === 1) {
        $edit_data = mysqli_fetch_assoc($res_edit);
        $page = 'form'; // Alihkan rute modul ke form pengisian
    }
}

// [INFO PHP]: Logika pengisian klausa SQL pencarian string kata kunci nama pelanggan
$search_query = "";
if (isset($_POST['search'])) {
    $keyword = mysqli_real_escape_string($conn, $_POST['keyword']);
    $search_query = " AND nama LIKE '%$keyword%' ";
}

// [INFO PHP]: Menghitung akumulasi data statistik untuk kotak informasi riil harian salon
$count_proses = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pelanggan WHERE status='Proses'"));
$kursi_tersedia = 7 - $count_proses; // Batas limit salon adalah 7 kursi utama

$stat_waiting   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pelanggan WHERE status='Waiting'"));
$stat_proses    = $count_proses;
$stat_booking   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pelanggan WHERE status='Reservasi'"));
$stat_riwayat   = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pelanggan WHERE status='Selesai'"));

// [INFO PHP]: Menghitung baris data booking murni jalur online (untuk indikator notifikasi baru)
$stat_booking_online = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pelanggan WHERE status='Reservasi' AND status_pembayaran = 'DP Lunas'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tito Panel - Premium Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="admin-wrapper">
        
        <div class="sidebar">
            <div class="sidebar-brand">Tito<span>Panel</span></div>
            <ul class="sidebar-menu">
                <li><a href="admin.php?page=form" class="<?= $page == 'form' && !isset($_GET['edit_id']) ? 'active' : ''; ?>">📝 Input Transaksi</a></li>
                <li><a href="admin.php?page=tabel" class="<?= $page == 'tabel' ? 'active' : ''; ?>">📋 Antrean Hari Ini</a></li>
                <li><a href="admin.php?page=reservasi" class="<?= $page == 'reservasi' ? 'active' : ''; ?>">📅 Buku Reservasi <span id="badge-notif-count" style="background:#EF4444; color:#fff; padding:2px 8px; border-radius:50px; font-size:11px; display:none;">New</span></a></li>
                
                <?php if ($_SESSION['admin_logged'] === 'admin') : ?>
                    <li><a href="admin.php?page=riwayat" class="<?= $page == 'riwayat' ? 'active' : ''; ?>">🗂️ Arsip Riwayat</a></li>
                <?php endif; ?>
                
                <li><a href="admin.php?page=estimasi" class="<?= $page == 'estimasi' ? 'active' : ''; ?>">💰 Estimasi Harga</a></li>
                <li style="margin-top: 40px;"><a href="logout.php" style="background: #FFF5F5; color: #E53E3E;" onclick="return confirm('Apakah Anda ingin keluar sistem?')">🚪 Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            
            <div class="content-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;">
                <h2>Sistem Administrasi Tito Salon</h2>
                <span style="font-weight: 600; color: var(--text-secondary);">Mekanik Admin: <b style="color: var(--rose-dark);"><?= htmlspecialchars($_SESSION['admin_logged']); ?></b></span>
            </div>

            <div class="banner-kursi">
                <span>KAPASITAS UTAMA OPERASIONAL SALON: <b>7 KURSI KERJA</b></span>
                <span>Kursi Terisi: <b><?= $stat_proses; ?> / 7</b> | Slot Kosong Tersisa: <b><?= $kursi_tersedia; ?> Kursi</b></span>
            </div>

            <div class="stats-grid">
                <div class="stat-card" style="border-top: 4px solid #E53E3E;">
                    <h4>Menunggu (Waiting)</h4>
                    <p><?= $stat_waiting; ?> Orang</p>
                </div>
                <div class="stat-card" style="border-top: 4px solid #D97706;">
                    <h4>Sedang Diproses</h4>
                    <p><?= $stat_proses; ?> Slot</p>
                </div>
                <div class="stat-card" style="border-top: 4px solid #2563EB;">
                    <h4>Jadwal Booking</h4>
                    <p id="stat-booking-val"><?= $stat_booking; ?> Data</p>
                </div>
                <div class="stat-card" style="border-top: 4px solid #059669;">
                    <h4>Arsip Riwayat</h4>
                    <p><?= $stat_riwayat; ?> Selesai</p>
                </div>
            </div>

            <?php 
                if ($page == 'form') {
                    include 'menu_form.php';
                } elseif ($page == 'tabel') {
                    include 'menu_tabel.php';
                } elseif ($page == 'reservasi') {
                    include 'menu_reservasi.php';
                } elseif ($page == 'riwayat' && $_SESSION['admin_logged'] === 'admin') {
                    include 'menu_riwayat.php';
                } elseif ($page == 'estimasi') {
                    include 'menu_estimasi.php';
                }
            ?>

        </div>
    </div>

    <script>
        // Mencetak data jumlah booking online saat ini dari variabel PHP ke Javascript
        let currentBookingCount = <?= $stat_booking_online; ?>;
        
        // [INFO JS]: Fungsi otomatisasi pengecekan data masuk booking online harian
        function checkIncomingBooking() {
            let lastKnownOnlineCount = sessionStorage.getItem('last_online_booking_count');
            if (lastKnownOnlineCount !== null) {
                // Jika total terhitung sekarang lebih besar dari catatan riwayat browser sebelumnya
                if (currentBookingCount > parseInt(lastKnownOnlineCount)) {
                    alert("🔔 NOTIFIKASI RESERVASI ONLINE!\n\nAda pelanggan baru yang melakukan booking secara mandiri melalui website! Silakan periksa menu Buku Reservasi.");
                    document.getElementById('badge-notif-count').style.display = 'inline-block'; // Tampilkan badge merah 'New'
                }
            }
            // Simpan kondisi terbaru ke dalam penyimpanan temporary session tab browser
            sessionStorage.setItem('last_online_booking_count', currentBookingCount);
        }

        // Pengkondisian reset alarm notifikasi jika admin sedang membuka halaman reservasi
        if("<?= $page; ?>" === "reservasi") {
            sessionStorage.setItem('last_online_booking_count', currentBookingCount);
        } else {
            window.addEventListener('load', checkIncomingBooking); // Jalankan saat web selesai dimuat
        }

        // [INFO JS]: Fungsi interaktif menyembunyikan/menampilkan box DP di menu form registrasi baru
        function toggleTipeKedatangan() {
            if(!document.getElementById('field-tipe')) return;
            let tipe = document.getElementById('field-tipe').value;
            let infoDP = document.getElementById('info-dp-container');
            
            if (tipe === 'Reservasi') {
                infoDP.style.display = 'block'; // Munculkan box DP
            } else {
                infoDP.style.display = 'none';  // Sembunyikan box DP
            }
            hitungKembalian(); // Hitung ulang matematika nominal uang tagihan kasir
        }

        // [INFO JS]: Otomasi perubahan tampilan inputan saat mengganti metode pembayaran (Cash/QRIS)
        function toggleMetodePembayaran() {
            let metode = document.getElementById('field-metode').value;
            let containerBayar = document.getElementById('container-bayar');
            let infoTunai = document.getElementById('info-kasir-tunai');
            let infoQris = document.getElementById('info-kasir-qris');
            let fieldBayar = document.getElementById('field-bayar');

            let hargaTotal = parseInt(document.getElementById('field-harga').value) || 0;
            
            // Perhitungan deteksi ada tidaknya DP senilai 50rb berdasarkan dropdown tipe kedatangan
            let tipe = document.getElementById('field-tipe') ? document.getElementById('field-tipe').value : 'Walk-in';
            let uangDP = 0;
            if (tipe === 'Reservasi') {
                uangDP = 50000;
            } else {
                uangDP = <?php echo ($edit_data && isset($edit_data['uang_dp'])) ? intval($edit_data['uang_dp']) : 0; ?>; 
            }
            
            let sisaWajibBayar = hargaTotal - uangDP;

            if(metode === 'QRIS') {
                containerBayar.style.display = 'none'; // Sembunyikan kolom isi uang bayar cash
                infoTunai.style.display = 'none';
                infoQris.style.display = 'flex';       // Nyalakan panduan qris pas
                fieldBayar.required = false;
                fieldBayar.value = sisaWajibBayar;     // Isi nilai bayar otomatis dengan sisa tagihan pas
                document.getElementById('text-qris-pas').innerText = "Silakan Scan QRIS Senilai Rp " + sisaWajibBayar.toLocaleString('id-ID');
            } else {
                containerBayar.style.display = 'flex';  // Nyalakan kembali kolom cash tunai
                infoTunai.style.display = 'flex';
                infoQris.style.display = 'none';
                fieldBayar.required = true;
                fieldBayar.value = '';                 // Kosongkan agar kasir mengetik ulang uang pelanggan
                hitungKembalian();
            }
        }

        // [INFO JS]: Kalkulator matematika hitung kembalian tunai kasir secara real-time harian
        function hitungKembalian() {
            if(!document.getElementById('field-harga')) return;
            let hargaTotal = parseInt(document.getElementById('field-harga').value) || 0;
            let metode = document.getElementById('field-metode').value;
            let fieldBayar = document.getElementById('field-bayar');
            
            let tipe = document.getElementById('field-tipe') ? document.getElementById('field-tipe').value : 'Walk-in';
            let uangDP = 0;
            if (tipe === 'Reservasi') {
                uangDP = 50000;
            } else {
                uangDP = <?php echo ($edit_data && isset($edit_data['uang_dp'])) ? intval($edit_data['uang_dp']) : 0; ?>; 
            }
            
            let sisaWajibBayar = hargaTotal - uangDP;
            
            // Perbarui teks pengingat sisa tagihan di box informasi atas
            if(document.getElementById('text-sisa-wajib')) {
                document.getElementById('text-sisa-wajib').innerText = "Rp " + sisaWajibBayar.toLocaleString('id-ID');
            }
            
            if(metode === 'QRIS') {
                fieldBayar.value = sisaWajibBayar;
                document.getElementById('text-qris-pas').innerText = "Silakan Scan QRIS Senilai Rp " + sisaWajibBayar.toLocaleString('id-ID');
                return; 
            }

            let bayar = parseInt(fieldBayar.value) || 0;
            let kembalian = bayar - sisaWajibBayar; // Formula matematika kembalian
            let textKembalian = document.getElementById('text-kembalian');

            // Jika uang yang dimasukkan kasir masih kurang dari sisa tagihan pelunasan
            if (kembalian < 0) {
                textKembalian.innerText = "Uang Kurang Rp " + Math.abs(kembalian).toLocaleString('id-ID');
                textKembalian.style.color = "#EF4444"; // Teks berubah warna merah (Warning)
            } else {
                textKembalian.innerText = "Rp " + kembalian.toLocaleString('id-ID');
                textKembalian.style.color = "#059669"; // Teks berubah warna hijau (Sukses)
            }
        }

        // [INFO JS]: Fungsi pop-up konfirmasi dua tombol (OK/Cancel) sebelum menghapus rekam data
        function confirmDelete(namaPelanggan, namaMenu) {
            return confirm("⚠️ POP-UP KONFIRMASI\n\nApakah Anda yakin ingin menghapus data milik \"" + namaPelanggan + "\" dari menu [" + namaMenu + "] secara permanen?\n\nTindakan ini tidak dapat dibatalkan.");
        }

        // [INFO JS]: Fungsi pop-up konfirmasi pengalihan navigasi sebelum mengoreksi/mengedit data
        function confirmEdit(namaPelanggan) {
            return confirm("📝 KOREKSI DATA STAF\n\nSistem akan mengalihkan Anda ke form untuk mengedit riwayat transaksi \"" + namaPelanggan + "\". Lanjutkan?");
        }

        // [INFO JS]: PROTEKSI UTAMA KASIR - Mencegah pemindahan status pengerjaan jika keuangan belum seimbang/lunas
        function updateProgres(id, statusBaru) {
            if (statusBaru === "Proses" || statusBaru === "Selesai") {
                // Menemukan element select dropdown tempat tombol diklik kasir
                let selectElement = document.querySelector('select[onchange="updateProgres(' + id + ', this.value)"]');
                
                // Membaca isi atribut kustom data- keuangan pelanggan yang terpasang di HTML tabel
                let uangDP = parseInt(selectElement.getAttribute('data-dp')) || 0;
                let uangDibayar = parseInt(selectElement.getAttribute('data-dibayar')) || 0;
                let hargaTotal = parseInt(selectElement.getAttribute('data-harga')) || 0;
                
                let totalUangMasuk = uangDP + uangDibayar;

                // JIKA terdeteksi ada cicilan DP namun akumulasi total uang masuk masih kurang dari harga total perawatan
                if (uangDP > 0 && totalUangMasuk < hargaTotal) {
                    let sisaKekurangan = hargaTotal - uangDP;
                    // BLOKIR total pengubahan status dan munculkan penolakan keras
                    alert("❌ AKSES KASIR DITOLAK!\n\nPelanggan dengan kategori Reservasi ini BELUM MELUNASI sisa tagihan di kasir.\n\nStatus dilarang dipindah ke [" + statusBaru + "] sebelum sisa pelunasan dibayar.\n\nSilakan klik tombol [Edit] pada baris data untuk menginput nominal uang pelunasan (Rp " + sisaKekurangan.toLocaleString('id-ID') + ") terlebih dahulu!");
                    location.reload(); // Reload halaman untuk mengembalikan posisi select dropdown ke semula
                    return false;
                }
            }

            // Jika lulus sensor keuangan, tanyakan konfirmasi pembaruan status akhir
            if (confirm("Perbarui status pengerjaan pelanggan sekarang?")) {
                window.location.href = "proses.php?action=update_status&id=" + id + "&status=" + statusBaru;
            } else {
                location.reload();
            }
        }

        // [INFO JS]: Event Listener yang berjalan otomatis tepat saat seluruh dokumen HTML selesai dimuat browser
        window.addEventListener('DOMContentLoaded', () => {
            if(document.getElementById('field-metode')) {
                let metode = document.getElementById('field-metode').value;
                if(metode === 'QRIS') {
                    document.getElementById('container-bayar').style.display = 'none';
                    document.getElementById('info-kasir-tunai').style.display = 'none';
                    document.getElementById('info-kasir-qris').style.display = 'flex';
                } else {
                    hitungKembalian();
                }
            }
            if(document.getElementById('field-tipe')) {
                toggleTipeKedatangan(); // Jalankan sinkronisasi awal box informasi DP form
            }
        });

        // [INFO JS]: Validasi pencegahan penekanan tombol enter simpan jika isian cash masih kurang dari tagihan wajib
        if(document.getElementById('form-data-salon')) {
            document.getElementById('form-data-salon').addEventListener('submit', function(e) {
                let nama = document.getElementById('field-nama').value.trim();
                let hargaTotal = parseInt(document.getElementById('field-harga').value) || 0;
                let metode = document.getElementById('field-metode').value;
                let bayar = parseInt(document.getElementById('field-bayar').value) || 0;
                
                let tipe = document.getElementById('field-tipe') ? document.getElementById('field-tipe').value : 'Walk-in';
                let uangDP = 0;
                if (tipe === 'Reservasi') {
                    uangDP = 50000;
                } else {
                    uangDP = <?php echo ($edit_data && isset($edit_data['uang_dp'])) ? intval($edit_data['uang_dp']) : 0; ?>; 
                }
                let sisaWajibBayar = hargaTotal - uangDP;
                
                // Sensor validasi wajib mencentang minimal satu checkbox jenis treatment
                let checkboxes = document.querySelectorAll('input[name="jenis_layanan[]"]:checked');
                if(checkboxes.length === 0) {
                    alert("Gagal: Anda harus mencentang minimal salah satu layanan perawatan!");
                    e.preventDefault(); // Gagalkan submit form pengiriman
                    return false;
                }

                // Jika cash tunai kurang dari sisa tagihan pelunasan wajib harian
                if(metode === 'Cash' && bayar < sisaWajibBayar) {
                    alert("Gagal Simpan: Uang yang dibayarkan pelanggan masih kurang dari sisa tagihan pelunasan (Rp " + sisaWajibBayar.toLocaleString('id-ID') + ")!");
                    e.preventDefault(); // Gagalkan pengiriman data ke backend proses.php
                    return false;
                }
            });
        }
    </script>
</body>
</html>