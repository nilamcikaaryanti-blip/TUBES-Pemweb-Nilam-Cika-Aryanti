<?php
// menu_estimasi.php

// [INFO PHP]: Proteksi pengaman agar file pecahan ini tidak bisa diakses langsung via URL tanpa melewati admin.php
if (!defined('conn')) { include 'koneksi.php'; }
?>
<h3 style="margin-bottom: 5px;">💰 Acuan Estimasi Harga Perawatan</h3>
<p style="color: var(--text-secondary); font-size:13px; margin-bottom: 15px;">Gunakan kartu referensi di bawah ini sebagai standar pengetikan nominal harga harian.</p>

<div style="background: #FFFBEB; border-left: 4px solid #D97706; padding: 15px 20px; border-radius: 8px; margin-bottom: 30px; font-size: 13.5px; line-height: 1.5; color: #92400E;">
    ⚠️ <b>Catatan Penting Kasir & Pelanggan:</b> Seluruh nominal di bawah ini merupakan <b>Estimasi Kira-Kira Tarif Dasar</b>. Jika dalam pelaksanaan riil pelanggan meminta penambahan bahan, treatment khusus, atau tingkat kesulitan tertentu, silakan sesuaikan kembali nominal harga akhir saat pengisian form kasir/edit data.
</div>

<div class="price-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
    
    <div class="price-reference-card" onclick="tampilkanInfoLayanan('Potong Rambut', 'Layanan potong rambut premium kami mencakup:\n- Konsultasi model rambut sesuai bentuk wajah\n- Cuci rambut dengan sampo & conditioner berkualitas\n- Pijat kepala ringan\n- Hair styling dasar (Blow-dry / Pomade)\n\nEstimasi pengerjaan: 30 - 45 Menit.')" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; background: #fff; border: 1px solid #E5E7EB; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; padding: 0; align-items: stretch; justify-content: flex-start;">
        <img src="assets/potong.jpg" alt="Potong Rambut" style="width: 100%; height: 180px; object-fit: cover; display: block; background: #F3F4F6; margin: 0; border: none;">
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #ffffff; width: 100%; box-sizing: border-box; flex: 1;">
            <div class="price-info" style="text-align: left;">
                <h4 style="margin: 0 0 4px 0; color: var(--rose-dark); font-size: 16px; font-weight: 700;">✂️ Potong Rambut</h4>
                <p style="margin: 0; font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Termasuk cuci & styling dasar</p>
            </div>
            <div class="price-tag" style="font-weight: 700; color: #059669; background: #ECFDF5; padding: 6px 12px; border-radius: 8px; font-size: 14px; white-space: nowrap; margin-left: 10px;">Rp 100.000</div>
        </div>
    </div>

    <div class="price-reference-card" onclick="tampilkanInfoLayanan('Warna Rambut', 'Layanan pewarnaan rambut basic mencakup:\n- Aplikasi cat rambut fashion short atau medium size\n- Penggunaan produk pewarna yang aman bagi kulit kepala\n- Cuci bilas khusus pelindung warna\n- Vitamin rambut setelah proses mewarnai\n\n*Catatan: Harga belum termasuk bleaching jika diperlukan model rambut tertentu.')" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; background: #fff; border: 1px solid #E5E7EB; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; padding: 0; align-items: stretch; justify-content: flex-start;">
        <img src="assets/warna.jpg" alt="Warna Rambut" style="width: 100%; height: 180px; object-fit: cover; display: block; background: #F3F4F6; margin: 0; border: none;">
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #ffffff; width: 100%; box-sizing: border-box; flex: 1;">
            <div class="price-info" style="text-align: left;">
                <h4 style="margin: 0 0 4px 0; color: var(--rose-dark); font-size: 16px; font-weight: 700;">🎨 Warna Rambut</h4>
                <p style="margin: 0; font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Pewarnaan basic short / medium</p>
            </div>
            <div class="price-tag" style="font-weight: 700; color: #059669; background: #ECFDF5; padding: 6px 12px; border-radius: 8px; font-size: 14px; white-space: nowrap; margin-left: 10px;">Rp 180.000</div>
        </div>
    </div>

    <div class="price-reference-card" onclick="tampilkanInfoLayanan('Creambath / Cuci', 'Layanan creambath perawatan mendalam mencakup:\n- Cuci rambut bersih\n- Aplikasi cream masker vitamin sesuai jenis keluhan rambut (Rontok/Kering/Ketombe)\n- Steam rambut hangat\n- Pijat punggung, leher, dan lengan rileks\n\nEstimasi pengerjaan: 45 - 60 Menit.')" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; background: #fff; border: 1px solid #E5E7EB; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; padding: 0; align-items: stretch; justify-content: flex-start;">
        <img src="assets/creambath.jpg" alt="Creambath / Cuci" style="width: 100%; height: 180px; object-fit: cover; display: block; background: #F3F4F6; margin: 0; border: none;">
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #ffffff; width: 100%; box-sizing: border-box; flex: 1;">
            <div class="price-info" style="text-align: left;">
                <h4 style="margin: 0 0 4px 0; color: var(--rose-dark); font-size: 16px; font-weight: 700;">💆‍♀️ Creambath / Cuci</h4>
                <p style="margin: 0; font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Perawatan vitamin rambut & pijat</p>
            </div>
            <div class="price-tag" style="font-weight: 700; color: #059669; background: #ECFDF5; padding: 6px 12px; border-radius: 8px; font-size: 14px; white-space: nowrap; margin-left: 10px;">Rp 75.000</div>
        </div>
    </div>

    <div class="price-reference-card" onclick="tampilkanInfoLayanan('Hair Styling', 'Layanan tataan rambut formal mencakup:\n- Catok lurus berkilau\n- Curling otomatis / curly ombak estetik\n- Sanggul modern hijab / non-hijab untuk pesta harian\n- Spray penahan tatanan rambut tahan lama\n\nSangat cocok dikombinasikan dengan paket makeup.')" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; background: #fff; border: 1px solid #E5E7EB; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; padding: 0; align-items: stretch; justify-content: flex-start;">
        <img src="assets/styling.jpg" alt="Hair Styling" style="width: 100%; height: 180px; object-fit: cover; display: block; background: #F3F4F6; margin: 0; border: none;">
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #ffffff; width: 100%; box-sizing: border-box; flex: 1;">
            <div class="price-info" style="text-align: left;">
                <h4 style="margin: 0 0 4px 0; color: var(--rose-dark); font-size: 16px; font-weight: 700;">⚡ Hair Styling</h4>
                <p style="margin: 0; font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Catok / Curling / Sanggul modern</p>
            </div>
            <div class="price-tag" style="font-weight: 700; color: #059669; background: #ECFDF5; padding: 6px 12px; border-radius: 8px; font-size: 14px; white-space: nowrap; margin-left: 10px;">Rp 80.000</div>
        </div>
    </div>

    <div class="price-reference-card" onclick="tampilkanInfoLayanan('Professional Makeup', 'Layanan tata rias wajah eksklusif mencakup:\n- Pembersihan kulit wajah & primer dasar\n- Penggunaan foundation premium anti luntur harian\n- Koreksi alis & shading kontur wajah proporsional\n- Termasuk pemasangan bulu mata palsu standar\n- Sempurna untuk wisuda, lamaran, kondangan, & photoshoot.')" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; background: #fff; border: 1px solid #E5E7EB; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; padding: 0; align-items: stretch; justify-content: flex-start;">
        <img src="assets/makeup.jpg" alt="Professional Makeup" style="width: 100%; height: 180px; object-fit: cover; display: block; background: #F3F4F6; margin: 0; border: none;">
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #ffffff; width: 100%; box-sizing: border-box; flex: 1;">
            <div class="price-info" style="text-align: left;">
                <h4 style="margin: 0 0 4px 0; color: var(--rose-dark); font-size: 16px; font-weight: 700;">💄 Professional Makeup</h4>
                <p style="margin: 0; font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Riasan wisuda, pesta, & event</p>
            </div>
            <div class="price-tag" style="font-weight: 700; color: #059669; background: #ECFDF5; padding: 6px 12px; border-radius: 8px; font-size: 14px; white-space: nowrap; margin-left: 10px;">Rp 300.000</div>
        </div>
    </div>

    <div class="price-reference-card" onclick="tampilkanInfoLayanan('Perawatan Lain-lain', 'Kategori ini mencakup treatment tambahan fleksibel salon seperti:\n- Manicure & Pedicure kuku\n- Nail Art hias\n- Eyebash extension, dll.\n\n*Tarif pengetikan silakan dikonsultasikan kembali dengan kapabilitas staf kecantikan harian.')" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; background: #fff; border: 1px solid #E5E7EB; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; padding: 0; align-items: stretch; justify-content: flex-start;">
        <img src="assets/lain.jpg" alt="Perawatan Lain-lain" style="width: 100%; height: 180px; object-fit: cover; display: block; background: #F3F4F6; margin: 0; border: none;">
        <div style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #ffffff; width: 100%; box-sizing: border-box; flex: 1;">
            <div class="price-info" style="text-align: left;">
                <h4 style="margin: 0 0 4px 0; color: var(--rose-dark); font-size: 16px; font-weight: 700;">🛍️ Perawatan Lain-lain</h4>
                <p style="margin: 0; font-size: 12.5px; color: var(--text-secondary); line-height: 1.4;">Menicure, pedicure, atau treatment tambahan</p>
            </div>
            <div class="price-tag" style="font-weight: 700; color: #B45309; background: #FEF3C7; padding: 6px 12px; border-radius: 8px; font-size: 14px; white-space: nowrap; margin-left: 10px;">Menyesuaikan</div>
        </div>
    </div>

</div>

<script>
// [INFO JS]: Fungsi pop-up interaktif info rincian layanan dari database tiruan
function tampilkanInfoLayanan(namaLayanan, rincianInfo) {
    alert("ℹ️ INFORMASI DETAIL LAYANAN\n\nNama Treatment: " + namaLayanan + "\n\n" + rincianInfo);
}

// [INFO JS]: Perulangan animasi hover (naik ke atas & bayangan) untuk seluruh elemen kartu harga
document.querySelectorAll('.price-reference-card').forEach(card => {
    // Ketika kursor masuk area kartu
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-6px)';
        card.style.boxShadow = '0 12px 30px rgba(236, 72, 153, 0.12)';
        card.style.borderColor = 'var(--pink-pastel)';
    });
    // Ketika kursor keluar area kartu
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
        card.style.boxShadow = 'none';
        card.style.borderColor = '#E5E7EB';
    });
});
</script>