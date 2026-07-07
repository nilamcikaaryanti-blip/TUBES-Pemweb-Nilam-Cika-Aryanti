<?php
// menu_tabel.php

// [INFO PHP]: Proteksi file pecahan
if (!defined('conn')) { include 'koneksi.php'; }
?>
<h3 style="margin-bottom: 5px;">Log Antrean Walk-in Hari Ini</h3>
<p style="color: var(--text-secondary); font-size:13px; margin-bottom: 25px;">Data pelanggan walk-in aktif yang sedang mengantre atau diproses.</p>

<form method="POST" action="" class="search-wrapper">
    <input type="text" name="keyword" placeholder="Cari nama antrean..." autocomplete="off">
    <button type="submit" name="search" class="btn" style="margin-top:0; padding: 12px 24px; border-radius:12px;">Cari</button>
</form>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Layanan dipilih</th>
                <th>Waktu Masuk</th>
                <th>Estimasi Harga</th>
                <th>Pembayaran</th>
                <th>Status Progres</th>
                <th>Opsi Kendali</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query SQL: Mengambil semua data kategori Walk-in yang kondisinya belum "Selesai" (masih mengantre atau diproses)
            $query_walk = mysqli_query($conn, "SELECT * FROM pelanggan WHERE tipe_kedatangan='Walk-in' AND status != 'Selesai' $search_query ORDER BY id ASC");
            if (mysqli_num_rows($query_walk) == 0) echo "<tr><td colspan='7' style='text-align:center; padding:30px; color:var(--text-secondary);'>Tidak ditemukan antrean Walk-In aktif.</td></tr>";
            
            // Perulangan baris tabel data antrean walk-in salon
            while ($row = mysqli_fetch_assoc($query_walk)) :
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['nama']); ?></strong><br><small style="color:var(--text-secondary);"><?= htmlspecialchars($row['keterangan']); ?></small></td>
                    <td><span style="color: var(--pink-brand); font-weight: 600;"><?= $row['jenis_layanan']; ?></span></td>
                    <td><strong><?= date('H:i', strtotime($row['tanggal_waktu'])); ?> WITA</strong></td>
                    <td><b>Rp <?= number_format($row['estimasi_harga'], 0, ',', '.'); ?></b></td>
                    <td>
                        <?php if (isset($row['uang_dp']) && $row['uang_dp'] > 0) : ?>
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
                                style="background: <?= $row['status']=='Proses'?'#FEF3C7':'#EDF2F7'; ?>; color: <?= $row['status']=='Proses'?'#D97706':'#4A5568'; ?>;">
                            <option value="Waiting" <?= $row['status']=='Waiting'?'selected':''; ?>>Waiting</option>
                            <option value="Proses" <?= $row['status']=='Proses'?'selected':''; ?>>Proses</option>
                            <option value="Selesai" <?= $row['status']=='Selesai'?'selected':''; ?>>Selesai</option>
                        </select>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="admin.php?edit_id=<?= $row['id']; ?>" class="btn-edit" onclick="return confirmEdit('<?= htmlspecialchars($row['nama']); ?>')">Edit</a>
                            <a href="proses.php?action=hapus&page=tabel&id=<?= $row['id']; ?>" class="btn-danger" onclick="return confirmDelete('<?= htmlspecialchars($row['nama']); ?>', 'Antrean Hari Ini')">Hapus</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>