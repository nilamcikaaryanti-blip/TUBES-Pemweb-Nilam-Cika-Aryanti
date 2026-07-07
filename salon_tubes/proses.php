<?php
// proses.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['admin_logged']) && !isset($_POST['simpan_booking_online'])) {
    header("Location: login.php");
    exit;
}

// 1. PROSES SIMPAN DATA BARU DARI DASHBOARD (CREATE)
if (isset($_POST['simpan_pelanggan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe_kedatangan']);
    
    $layanan_array = isset($_POST['jenis_layanan']) ? $_POST['jenis_layanan'] : ['Lain-lain'];
    $layanan = mysqli_real_escape_string($conn, implode(', ', $layanan_array));
    
    $datetime = $_POST['tanggal'] . ' ' . $_POST['waktu'];
    $harga = intval($_POST['harga']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $uang_dibayar = intval($_POST['uang_dibayar']);

    $check_kursi = mysqli_query($conn, "SELECT id FROM pelanggan WHERE status = 'Proses'");
    $kursi_terisi = mysqli_num_rows($check_kursi);

    // DITERAPKAN PADA RESERVASI MANUAL: Set nilai DP default Rp 50.000
    $uang_dp = 0;
    $status_pembayaran = 'Belum Bayar';

    if ($tipe == 'Reservasi') {
        $status = 'Reservasi';
        $uang_dp = 50000;
        // DIUBAH DI SINI: Diberi tanda (Offline) agar tidak memicu pop-up notifikasi admin
        $status_pembayaran = 'DP Lunas (Offline)';
    } else {
        if ($kursi_terisi >= 7) { 
            $status = 'Waiting';
        } else {
            $status = 'Proses';
        }
    }

    $query = "INSERT INTO pelanggan (nama, tipe_kedatangan, jenis_layanan, tanggal_waktu, estimasi_harga, status, keterangan, metode_pembayaran, uang_dibayar, uang_dp, status_pembayaran) 
              VALUES ('$nama', '$tipe', '$layanan', '$datetime', '$harga', '$status', '$keterangan', '$metode_pembayaran', '$uang_dibayar', '$uang_dp', '$status_pembayaran')";
    
    mysqli_query($conn, $query);
    header("Location: admin.php?page=tabel");
    exit;
}

// 2. PROSES UPDATE KOREKSI DATA (UPDATE)
if (isset($_POST['update_pelanggan'])) {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tipe = mysqli_real_escape_string($conn, $_POST['tipe_kedatangan']);
    
    $layanan_array = isset($_POST['jenis_layanan']) ? $_POST['jenis_layanan'] : ['Lain-lain'];
    $layanan = mysqli_real_escape_string($conn, implode(', ', $layanan_array));
    
    $datetime = $_POST['tanggal'] . ' ' . $_POST['waktu'];
    $harga = intval($_POST['harga']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $uang_dibayar = intval($_POST['uang_dibayar']);

    // Jaga agar saat reservasi tempat diubah, nilai DP tetap sinkron Rp 50.000 jika bertipe reservasi
    $check_old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT uang_dp FROM pelanggan WHERE id = $id"));
    $uang_dp = $check_old['uang_dp'];
    if($tipe == 'Reservasi' && $uang_dp == 0) {
        $uang_dp = 50000;
    } elseif($tipe == 'Walk-in') {
        $uang_dp = 0;
    }

    $query = "UPDATE pelanggan SET 
                nama = '$nama', 
                tipe_kedatangan = '$tipe', 
                jenis_layanan = '$layanan', 
                tanggal_waktu = '$datetime', 
                estimasi_harga = '$harga', 
                keterangan = '$keterangan',
                metode_pembayaran = '$metode_pembayaran',
                uang_dibayar = '$uang_dibayar',
                uang_dp = '$uang_dp'
              WHERE id = $id";
              
    mysqli_query($conn, $query);
    header("Location: admin.php?page=tabel");
    exit;
}

// 3. PROSES UPDATE STATUS KILAT DARI DROPDOWN
if (isset($_GET['action']) && $_GET['action'] == 'update_status') {
    $id = intval($_GET['id']);
    $status_baru = mysqli_real_escape_string($conn, $_GET['status']);

    $query = "UPDATE pelanggan SET status = '$status_baru' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: admin.php?page=tabel");
    exit;
}

// 4. PROSES HAPUS DATA (DELETE)
if (isset($_GET['action']) && $_GET['action'] == 'hapus') {
    $id = intval($_GET['id']);
    $kembali_ke_page = mysqli_real_escape_string($conn, $_GET['page']);

    $query = "DELETE FROM pelanggan WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: admin.php?page=" . $kembali_ke_page);
    exit;
}

// 5. PROSES RESERVASI MANDIRI DARI PEMBELI (ONLINE CLIENT)
if (isset($_POST['simpan_booking_online'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $tipe = "Reservasi"; 
    
    $layanan_array = isset($_POST['jenis_layanan']) ? $_POST['jenis_layanan'] : ['Lain-lain'];
    $layanan = mysqli_real_escape_string($conn, implode(', ', $layanan_array));
    
    $datetime = $_POST['tanggal'] . ' ' . $_POST['waktu'];
    $harga = intval($_POST['harga']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    $uang_dp = 50000; 
    $metode_pembayaran = "QRIS"; 
    $status_pembayaran = "DP Lunas"; 
    $status = "Reservasi"; 

    $query = "INSERT INTO pelanggan (nama, tipe_kedatangan, jenis_layanan, tanggal_waktu, estimasi_harga, status, keterangan, metode_pembayaran, uang_dibayar, uang_dp, status_pembayaran) 
              VALUES ('$nama', '$tipe', '$layanan', '$datetime', '$harga', '$status', '$keterangan', '$metode_pembayaran', 0, 50000, '$status_pembayaran')";
    
    mysqli_query($conn, $query);
    header("Location: reservasi_online.php?status=sukses");
    exit;
}
?>