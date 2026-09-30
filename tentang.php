<?php
// Halaman informasi usaha dan kontak penjual.
require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/site-info.php';
$pageTitle = 'Tentang dan kontak';
$whatsappNumber = preg_replace('/\D+/', '', $sellerWhatsApp);
if (str_starts_with($whatsappNumber, '0')) $whatsappNumber = '62' . substr($whatsappNumber, 1);
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero contact-hero"><p class="eyebrow">CERITA DI BALIK RACIKAN</p><h1>Tentang Jamu.</h1><p>Jamu tradisional untuk dinikmati dekat rumah atau diracik sendiri dari resep keluarga.</p></section>
<section class="seller-about">
    <div class="seller-emblem"><img src="assets/jamu-logo.png" alt="Lambang Jamu"><span>RACIKAN TRADISIONAL</span></div>
    <article class="seller-story batik-surface"><p class="eyebrow">TENTANG PENJUAL</p><h2><?= htmlspecialchars($sellerName) ?></h2><p>Kami menyediakan dua varian jamu rumahan: beras kencur dan kunir asem. Setiap botol ditawarkan seharga Rp8.000. Pembeli di area layanan dapat memesan jamu siap minum, sedangkan pengunjung dari luar area bisa mengikuti resep yang tersedia di halaman Resep.</p><p>Terima kasih sudah mendukung racikan jamu tradisional.</p><a class="button" href="produk.php">Lihat produk <span>→</span></a></article>
</section>
<section class="seller-contact-section" id="kontak-info">
    <div class="contact-heading"><p class="eyebrow">ADA YANG INGIN DITANYAKAN?</p><h2>Hubungi penjual.</h2><p>Tanyakan ketersediaan jamu, pesanan, atau informasi racikan.</p></div>
    <div class="contact-options">
        <?php if ($whatsappNumber): ?><a class="contact-option whatsapp-option" href="https://wa.me/<?= htmlspecialchars($whatsappNumber) ?>" target="_blank" rel="noopener"><span class="contact-symbol">◉</span><span><b>WhatsApp</b><small>Chat penjual</small></span><strong>↗</strong></a><?php else: ?><div class="contact-option contact-pending"><span class="contact-symbol">◉</span><span><b>WhatsApp</b><small>Nomor penjual akan ditambahkan</small></span><strong>…</strong></div><?php endif; ?>
        <?php if ($sellerInstagram): ?><a class="contact-option" href="https://instagram.com/<?= rawurlencode(ltrim($sellerInstagram, '@')) ?>" target="_blank" rel="noopener"><span class="contact-symbol">◎</span><span><b>Instagram</b><small>@<?= htmlspecialchars(ltrim($sellerInstagram, '@')) ?></small></span><strong>↗</strong></a><?php endif; ?>
        <div class="contact-option"><span class="contact-symbol">⌖</span><span><b>Area layanan</b><small>Pengantaran lokal terbatas</small></span><strong>✦</strong></div>
    </div>
</section>
<section class="contact-bottom"><p>Belum tahu mau pesan atau meracik sendiri?</p><a href="resep.php">Lihat buku resep dan tutorial →</a></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
