<?php
// menu_riwayat.php

// [INFO PHP]: Proteksi agar file pecahan ini tidak bisa diakses langsung lewat URL tanpa melewati admin.php
if (!defined('conn')) { include 'koneksi.php'; }
?>
<h3 style="margin-bottom: 5px;">Arsip Laporan Riwayat Transaksi Selesai</h3>
<p style="color: var(--text-secondary); font-size:13px; margin-bottom: 30px;">Arsip keuangan bulanan kasir salon setelah status pengerjaan dinyatakan "Selesai".</p>

<?php
// =========================================================================
// AREA PHP BLOK PERTAMA: PENGAMBILAN DATA AGREGAT UNTUK FILTER GRAPHIC (CHART.JS)
// =========================================================================

// 1. Mengambil data transaksi harian (Kondisi: 7 Hari Terakhir dari waktu sekarang)
$days_label = []; $days_data = [];
$q_days = mysqli_query($conn, "SELECT DATE(tanggal_waktu) as tgl, DATE_FORMAT(tanggal_waktu, '%d %b') as format_tgl, SUM(estimasi_harga) as omzet FROM pelanggan WHERE status='Selesai' AND tanggal_waktu >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY tgl ORDER BY tgl ASC");
while($r = mysqli_fetch_assoc($q_days)){ 
    $days_label[] = $r['format_tgl']; // Menyimpan nama hari/tanggal ke array label chart
    $days_data[] = (int)$r['omzet'];     // Menyimpan total omzet uang ke array data chart
}

// 2. Mengambil data transaksi bulanan terbaru (Kondisi: 5 Bulan Terakhir dari waktu sekarang)
$m5_label = []; $m5_data = [];
$q_m5 = mysqli_query($conn, "SELECT DATE_FORMAT(tanggal_waktu, '%Y-%m') as bln, DATE_FORMAT(tanggal_waktu, '%b %Y') as format_bln, SUM(estimasi_harga) as omzet FROM pelanggan WHERE status='Selesai' AND tanggal_waktu >= DATE_SUB(NOW(), INTERVAL 5 MONTH) GROUP BY bln ORDER BY bln ASC");
while($r = mysqli_fetch_assoc($q_m5)){ $m5_label[] = $r['format_bln']; $m5_data[] = (int)$r['omzet']; }

// 3. Mengambil data transaksi tahunan terbaru (Kondisi: 1 Tahun Terakhir dari waktu sekarang)
$y1_label = []; $y1_data = [];
$q_y1 = mysqli_query($conn, "SELECT DATE_FORMAT(tanggal_waktu, '%Y-%m') as bln, DATE_FORMAT(tanggal_waktu, '%b %Y') as format_bln, SUM(estimasi_harga) as omzet FROM pelanggan WHERE status='Selesai' AND tanggal_waktu >= DATE_SUB(NOW(), INTERVAL 1 YEAR) GROUP BY bln ORDER BY bln ASC");
while($r = mysqli_fetch_assoc($q_y1)){ $y1_label[] = $r['format_bln']; $y1_data[] = (int)$r['omzet']; }

// 4. Mengambil seluruh rekam data omzet dari awal sampai akhir tanpa batas filter (Default Awal)
$all_label = []; $all_data = [];
$q_all = mysqli_query($conn, "SELECT DATE_FORMAT(tanggal_waktu, '%Y-%m') as bln, DATE_FORMAT(tanggal_waktu, '%b %Y') as format_bln, SUM(estimasi_harga) as omzet FROM pelanggan WHERE status='Selesai' GROUP BY bln ORDER BY bln ASC");
while($r = mysqli_fetch_assoc($q_all)){ $all_label[] = $r['format_bln']; $all_data[] = (int)$r['omzet']; }
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="table-card" style="padding: 25px; margin-bottom: 40px; background: #ffffff; border-radius: 16px; border: 1px solid #E5E7EB;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px;">
        <div>
            <h4 style="margin: 0 0 5px 0; font-family: 'Playfair Display', serif; color: var(--rose-dark); font-size: 16px;">📈 Tren Grafik Omzet Pendapatan Salon</h4>
            <p style="color: var(--text-secondary); font-size: 12.5px; margin: 0;">Visualisasi performa naik turun grafik penjualan bisnis Tito Salon.</p>
        </div>
        <div style="display: flex; gap: 8px; background: #F3F4F6; padding: 4px; border-radius: 8px;">
            <button onclick="ubahFilterGrafik('7_hari', this)" style="border:none; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; background:transparent; color:#4B5563; transition:0.2s;">7 Hari Terakhir</button>
            <button onclick="ubahFilterGrafik('5_bulan', this)" style="border:none; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; background:transparent; color:#4B5563; transition:0.2s;">5 Bulan Terakhir</button>
            <button onclick="ubahFilterGrafik('1_tahun', this)" style="border:none; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; background:transparent; color:#4B5563; transition:0.2s;">1 Tahun Terakhir</button>
            <button onclick="ubahFilterGrafik('semua', this)" id="btn-default-filter" style="border:none; padding:6px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; background:#ffffff; color:#EC4899; box-shadow:0 2px 4px rgba(0,0,0,0.05); transition:0.2s;">Semua Periode</button>
        </div>
    </div>
    
    <div style="width: 100%; height: 280px; position: relative;">
        <canvas id="omzetChart"></canvas>
    </div>
</div>

<script>
// =========================================================================
// AREA SCRIPT JAVASCRIPT KHUSUS KONTROL GRAFIK INTERAKTIF
// =========================================================================

let miChart; // Variabel global penampung instansiasi objek Chart.js

// [INFO JS]: Mengonversi data array dari PHP ke dalam objek JSON JavaScript agar bisa dibaca Chart.js
const kumpulanData = {
    '7_hari': { labels: <?php echo json_encode($days_label); ?>, data: <?php echo json_encode($days_data); ?> },
    '5_bulan': { labels: <?php echo json_encode($m5_label); ?>, data: <?php echo json_encode($m5_data); ?> },
    '1_tahun': { labels: <?php echo json_encode($y1_label); ?>, data: <?php echo json_encode($y1_data); ?> },
    'semua': { labels: <?php echo json_encode($all_label); ?>, data: <?php echo json_encode($all_data); ?> }
};

// [INFO JS]: Menginisialisasi pembuatan grafik pertama kali saat halaman selesai dimuat browser
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('omzetChart').getContext('2d');
    const defaultData = kumpulanData['semua']; // Set awal: Menampilkan semua periode data

    // Cegah error rendering jika database salon masih kosong melompong
    if (defaultData.labels.length === 0) {
        ctx.font = "14px sans-serif";
        ctx.fillStyle = "#9CA3AF";
        ctx.textAlign = "center";
        ctx.fillText("Belum ada data transaksi untuk menampilkan grafik.", ctx.canvas.width / 2, ctx.canvas.height / 2);
        return;
    }

    // Deklarasi konfigurasi pembuatan Line Chart menggunakan Chart.js
    miChart = new Chart(ctx, {
        type: 'line', // Tipe grafik garis melengkung
        data: {
            labels: defaultData.labels, // Label sumbu X bawah (Nama Bulan / Tanggal)
            datasets: [{
                label: 'Total Omzet Masuk (Rp)',
                data: defaultData.data, // Nilai nominal sumbu Y samping (Uang Omzet)
                borderColor: '#EC4899', // Warna garis utama grafik (Pink)
                backgroundColor: 'rgba(236, 72, 153, 0.05)', // Warna area transparan di bawah garis
                borderWidth: 3,
                pointBackgroundColor: '#EC4899',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                tension: 0.35, // Membuat lekukan garis melengkung smooth
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }, // Matikan teks kotak legenda atas
                tooltip: {
                    callbacks: {
                        // Merapikan tampilan teks info angka uang saat kursor mendekati titik grafik
                        label: function(context) {
                            return ' Omzet: Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true, // Sumbu Y wajib dimulai dari angka 0
                    grid: { color: '#F3F4F6' },
                    ticks: {
                        // Singkat nominal ribuan/jutaan di sumbu Y agar tidak memenuhi layar (contoh: 1.000.000 -> 1jt)
                        callback: function(value) {
                            return 'Rp ' + (value >= 1000000 ? (value / 1000000) + 'jt' : value.toLocaleString('id-ID'));
                        },
                        font: { size: 11 }
                    }
                },
                x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' } } }
            }
        }
    });
});

// [INFO JS]: Fungsi untuk mengubah muatan data grafik secara cepat (Tanpa reload web) saat tombol filter diklik
function ubahFilterGrafik(tipe, s_btn) {
    if(!miChart) return;
    
    // Suntikkan label dan data array baru berdasarkan tipe filter yang dipilih kasir
    miChart.data.labels = kumpulanData[tipe].labels;
    miChart.data.datasets[0].data = kumpulanData[tipe].data;
    miChart.update(); // Menggambar ulang grafik secara instan

    // Logika CSS JavaScript untuk memindahkan style tombol aktif (Warna Pink Putih) ke tombol yang baru diklik
    const buttons = s_btn.parentNode.querySelectorAll('button');
    buttons.forEach(btn => {
        btn.style.background = 'transparent';
        btn.style.color = '#4B5563';
        btn.style.boxShadow = 'none';
    });
    s_btn.style.background = '#ffffff';
    s_btn.style.color = '#EC4899';
    s_btn.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
}
</script>

<?php
// =========================================================================
// AREA PHP BLOK KEDUA: LIST TABEL ARSIP RIWAYAT BULANAN BAWAAN ASLI
// =========================================================================

// Query unik mengambil daftar bulan dan tahun transaksi yang ada di database secara urut menurun (Bulan terbaru di atas)
$months_query = mysqli_query($conn, "SELECT DISTINCT DATE_FORMAT(tanggal_waktu, '%Y-%m') as bln_thn, DATE_FORMAT(tanggal_waktu, '%M %Y') as nama_bln FROM pelanggan WHERE status='Selesai' ORDER BY tanggal_waktu DESC");
if (mysqli_num_rows($months_query) == 0) echo "<div style='text-align:center; padding:50px; background:#fff; border-radius:12px; color:var(--text-secondary); font-style:italic;'>Belum ada arsip riwayat pengerjaan transaksi.</div>";

// Loop Utama: Membuat satu blok section tabel terpisah untuk setiap bulannya
while ($m_row = mysqli_fetch_assoc($months_query)) :
    $key_bulan = $m_row['bln_thn'];
    
    // Sub-Query: Menghitung total akumulasi uang omzet kotor khusus bulan ini untuk dipajang di header kelompok bulan
    $sum_query = mysqli_query($conn, "SELECT SUM(estimasi_harga) as total_omzet FROM pelanggan WHERE status='Selesai' AND DATE_FORMAT(tanggal_waktu, '%Y-%m') = '$key_bulan'");
    $sum_data = mysqli_fetch_assoc($sum_query);
    $total_pendapatan = $sum_data['total_omzet'] ?? 0;
?>
    <div class="month-group">
        <div class="month-title" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <span>Periode: <?= $m_row['nama_bln']; ?></span>
            <span style="background: #ECFDF5; color: #059669; padding: 6px 16px; border-radius: 50px; font-size: 14px; font-weight: 700; border: 1px solid #A7F3D0;">Total Pendapatan: Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></span>
        </div>
        
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Layanan Eksekusi</th>
                        <th>Biaya Masuk</th>
                        <th>Metode</th>
                        <th>Bayar / Kembalian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Sub-Query: Mengambil rincian data pelanggan yang berstatus Selesai khusus pada iterasi bulan ini
                    $query_history = mysqli_query($conn, "SELECT * FROM pelanggan WHERE status='Selesai' AND DATE_FORMAT(tanggal_waktu, '%Y-%m') = '$key_bulan' ORDER BY tanggal_waktu DESC");
                    while ($h_row = mysqli_fetch_assoc($query_history)) :
                        // Menghitung uang kembalian untuk transaksi tunai (Cash)
                        $kembalian = $h_row['uang_dibayar'] - $h_row['estimasi_harga'];
                        if($kembalian < 0 || $h_row['metode_pembayaran'] == 'QRIS') $kembalian = 0;
                    ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($h_row['nama']); ?></strong><br><small style="color:var(--text-secondary);"><?= date('d/m H:i', strtotime($h_row['tanggal_waktu'])); ?></small></td>
                            <td><?= $h_row['jenis_layanan']; ?></td>
                            <td style="color:#059669; font-weight:700;">Rp <?= number_format($h_row['estimasi_harga'], 0, ',', '.'); ?></td>
                            <td><span class="pay-badge <?= strtolower($h_row['metode_pembayaran']); ?>"><?= $h_row['metode_pembayaran']; ?></span></td>
                            <td>
                                <?php if($h_row['metode_pembayaran'] == 'Cash'): ?>
                                    <small style="color: var(--text-secondary);">Bayar: Rp <?= number_format($h_row['uang_dibayar'], 0, ',', '.'); ?></small><br>
                                    <small style="color: #B91C1C;">Kembali: Rp <?= number_format($kembalian, 0, ',', '.'); ?></small>
                                <?php else: ?>
                                    <small style="color: #1E40AF; font-weight:600;">QRIS Lunas Pas</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="admin.php?edit_id=<?= $h_row['id']; ?>" class="btn-edit" onclick="return confirmEdit('<?= htmlspecialchars($h_row['nama']); ?>')">Edit</a>
                                    <a href="proses.php?action=hapus&page=riwayat&id=<?= $h_row['id']; ?>" class="btn-danger" onclick="return confirmDelete('<?= htmlspecialchars($h_row['nama']); ?>', 'Arsip Riwayat Bulanan')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endwhile; ?>