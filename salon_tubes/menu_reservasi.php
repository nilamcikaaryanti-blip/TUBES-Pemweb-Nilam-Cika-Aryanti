<?php
// menu_reservasi.php

// [INFO PHP]: Proteksi berkas pecahan
if (!defined('conn')) { include 'koneksi.php'; }
?>
<h3 style="margin-bottom: 5px;">Buku Log Reservasi (Jadwal Booking)</h3>
<p style="color: var(--text-secondary); font-size:13px; margin-bottom: 25px;">Manajemen pemesanan jadwal yang masuk ke sistem Tito Salon.</p>

<form method="POST" action="" class="search-wrapper">
    <input type="text" name="keyword" placeholder="Cari nama pembooking..." autocomplete="off">
    <button type="submit" name="search" class="btn" style="margin-top:0; padding: 12px 24px; border-radius:12px;">Cari</button>
</form>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Layanan dipilih</th>
                <th>Tanggal & Waktu</th>
                <th>Estimasi Harga</th>
                <th>Pembayaran / Komitmen DP</th>
                <th>Status Progres</th>
                <th>Opsi Kendali</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query SQL Utama: Mengambil rekam data yang tipenya 'Reservasi' dan kondisinya belum dikerjakan sampai selesai (status != Selesai)
            $query_resv = mysqli_query($conn, "SELECT * FROM pelanggan WHERE (tipe_kedatangan='Reservasi' OR status='Reservasi') AND status != 'Selesai' $search_query ORDER BY tanggal_waktu ASC");
            if (mysqli_num_rows($query_resv) == 0) echo "<tr><td colspan='7' style='text-align:center; padding:30px; color:var(--text-secondary);'>Buku log reservasi kosong.</td></tr>";
            
            // Loop data buku log reservasi tempat salon
            while ($row = mysqli_fetch_assoc($query_resv)) :
                
                // [INFO PHP]: Formula mendeteksi akumulasi pelunasan uang harian
                $total_masuk = (isset($row['uang_dp']) ? intval($row['uang_dp']) : 0) + (isset($row['uang_dibayar']) ? intval($row['uang_dibayar']) : 0);
                // Status dianggap lunas mutlak jika total uang masuk menyamai atau lebih besar dari harga layanan kotor asli
                $is_lunas = ($total_masuk >= intval($row['estimasi_harga'])) ? true : false;
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama']); ?></strong><br><small style="color:var(--text-secondary);"><?= htmlspecialchars($row['keterangan']); ?></small></td>
                    <td><span style="color: var(--pink-brand); font-weight: 600;"><?= $row['jenis_layanan']; ?></span></td>
                    <td><strong><?= date('d M Y - H:i', strtotime($row['tanggal_waktu'])); ?> WITA</strong></td>
                    <td><b>Rp <?= number_format($row['estimasi_harga'], 0, ',', '.'); ?></b></td>
                    <td>
                        <?php if ($is_lunas) : ?>
                            <span class="pay-badge cash" style="background:#D1FAE5; color:#065F46; border:1px solid #34D399;">LUNAS</span>
                        <?php elseif (isset($row['uang_dp']) && $row['uang_dp'] > 0) : ?>
                            <span class="pay-badge qris" style="background:#DBEAFE; color:#1E40AF; border:1px solid #93C5FD;">DP LUNAS (Rp 50rb)</span>
                        <?php else : ?>
                            <span class="pay-badge <?= strtolower($row['metode_pembayaran']); ?>"><?= $row['metode_pembayaran']; ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <select onchange="updateProgres(<?= $row['id']; ?>, this.value)" 
                                data-dp="<?= isset($row['uang_dp']) ? $row['uang_dp'] : 0; ?>" 
                                data-dibayar="<?= isset($row['uang_dibayar']) ? $row['uang_dibayar'] : 0; ?>" 
                                data-harga="<?= $row['estimasi_harga']; ?>" 
                                class="status-dropdown" 
                                style="background: <?= $row['status']=='Proses'?'#FEF3C7':'#EBF8FF'; ?>; color: <?= $row['status']=='Proses'?'#D97706':'#2B6CB0'; ?>;">
                            <option value="Reservasi" <?= $row['status']=='Reservasi'?'selected':''; ?>>Reservasi</option>
                            <option value="Proses" <?= $row['status']=='Proses'?'selected':''; ?>>Proses</option>
                            <option value="Selesai" <?= $row['status']=='Selesai'?'selected':''; ?>>Selesai</option>
                        </select>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="admin.php?edit_id=<?= $row['id']; ?>" class="btn-edit" onclick="return confirmEdit('<?= htmlspecialchars($row['nama']); ?>')">Edit</a>
                            <a href="proses.php?action=hapus&page=reservasi&id=<?= $row['id']; ?>" class="btn-danger" onclick="return confirmDelete('<?= htmlspecialchars($row['nama']); ?>', 'Buku Log Reservasi')">Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>