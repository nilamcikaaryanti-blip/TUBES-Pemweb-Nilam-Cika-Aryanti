<?php
// menu_form.php

// [INFO PHP]: Proteksi berkas pecahan
if (!defined('conn')) { include 'koneksi.php'; }
?>
<div class="form-container">
    <h3><?= $edit_data ? '📝 Koreksi / Edit Data Transaksi' : 'Registrasi Kedatangan Baru'; ?></h3>
    <form action="proses.php" method="POST" id="form-data-salon" style="background:none; padding:0; box-shadow:none; border:none;">
        
        <?php if($edit_data): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>Nama Lengkap Pelanggan</label>
            <input type="text" name="nama" id="field-nama" placeholder="Tulis nama pelanggan" required autocomplete="off" value="<?= $edit_data ? htmlspecialchars($edit_data['nama']) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Kategori Kedatangan</label>
            <select name="tipe_kedatangan" id="field-tipe" onchange="toggleTipeKedatangan()">
                <option value="Walk-in" <?= $edit_data && $edit_data['tipe_kedatangan'] == 'Walk-in' ? 'selected' : ''; ?>>Datang Langsung (Walk-in)</option>
                <option value="Reservasi" <?= $edit_data && $edit_data['tipe_kedatangan'] == 'Reservasi' ? 'selected' : ''; ?>>Reservasi Tempat (Booking)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Treatment / Layanan (Bisa Pilih Lebih Dari Satu)</label>
            <div class="services-checkbox-grid">
                <?php 
                // Array daftar layanan rujukan salon
                $daftar_layanan = ['Potong Rambut', 'Warna Rambut', 'Creambath / Cuci', 'Styling', 'Makeup', 'Lain-lain'];
                // Jika sedang mengedit, pecah string kalimat layanan dari database menjadi bentuk array (explode)
                $layanan_terpilih = $edit_data ? explode(', ', $edit_data['jenis_layanan']) : [];
                foreach($daftar_layanan as $layanan) :
                    // Tandai checkbox dengan tulisan HTML 'checked' jika nama layanan ada di dalam daftar array database
                    $checked = in_array($layanan, $layanan_terpilih) ? 'checked' : '';
                ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="jenis_layanan[]" value="<?= $layanan; ?>" <?= $checked; ?>>
                        <?= $layanan; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal" required value="<?= $edit_data ? date('Y-m-d', strtotime($edit_data['tanggal_waktu'])) : ''; ?>">
            </div>
            <div class="form-group">
                <label>Jam Operasional</label>
                <input type="time" name="waktu" required value="<?= $edit_data ? date('H:i', strtotime($edit_data['tanggal_waktu'])) : ''; ?>">
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label>Total Tarif Biaya Layanan (Rp)</label>
            <input type="number" name="harga" id="field-harga" placeholder="Contoh: 150000" required value="<?= $edit_data ? $edit_data['estimasi_harga'] : ''; ?>" oninput="hitungKembalian()">
        </div>

        <div class="kasir-box">
            <h4 style="color: var(--rose-dark); margin-bottom: 15px; font-size: 15px; font-weight: 700;">💰 Modul Pembayaran Kasir</h4>
            
            <div id="info-dp-container" style="display: none; background: #EFF6FF; border: 1px solid #BFDBFE; padding: 14px 18px; border-radius: 12px; margin-bottom: 18px; font-size: 13.5px;">
                <span style="color: #1E40AF; display: block; font-weight: 700; margin-bottom: 4px;">ℹ️ Komitmen Down Payment (DP) Reservasi Tempat</span>
                <span style="color: #4B5563;">Kategori Reservasi diwajibkan Uang Muka (DP): <b style="color: #1E40AF;">Rp 50.000</b></span><br>
                <span style="color: #4B5563;">Sisa Pelunasan Wajib Ditagih: <b style="color: #1E40AF;" id="text-sisa-wajib">Rp 0</b></span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" id="field-metode" onchange="toggleMetodePembayaran()">
                        <option value="Cash" <?= $edit_data && $edit_data['metode_pembayaran'] == 'Cash' ? 'selected' : ''; ?>>Tunai (Cash)</option>
                        <option value="QRIS" <?= $edit_data && $edit_data['metode_pembayaran'] == 'QRIS' ? 'selected' : ''; ?>>Digital (QRIS Scan)</option>
                    </select>
                </div>
                
                <div class="form-group" id="container-bayar">
                    <label>Uang yang Dibayar (Rp)</label>
                    <input type="number" name="uang_dibayar" id="field-bayar" placeholder="Contoh: 200000" value="<?= $edit_data ? $edit_data['uang_dibayar'] : ''; ?>" oninput="hitungKembalian()">
                </div>
            </div>

            <div class="kasir-result" id="info-kasir-tunai">
                <span>Uang Kembalian Pelanggan:</span>
                <strong id="text-kembalian">Rp 0</strong>
            </div>

            <div class="kasir-result" id="info-kasir-qris" style="display: none; background: #EFF6FF; border-color: #BFDBFE;">
                <span style="color: #1E40AF;">📱 Otomasi QRIS Pas:</span>
                <strong style="color: #1E40AF;" id="text-qris-pas">Silakan Scan QRIS Senilai Total Tarif</strong>
            </div>
        </div>

        <div class="form-group">
            <label>Catatan Tambahan / Log Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Tulis catatan opsional..."><?= $edit_data ? htmlspecialchars($edit_data['keterangan']) : ''; ?></textarea>
        </div>

        <button type="submit" name="<?= $edit_data ? 'update_pelanggan' : 'simpan_pelanggan'; ?>" class="btn" style="width: 100%; border-radius: 12px; margin-top: 10px;">
            <?= $edit_data ? 'PERBARUI DATA TRANSAKSI' : 'SIMPAN REKAM DATA'; ?>
        </button>
    </form>
</div>