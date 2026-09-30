<?php
// Halaman beranda berisi pengenalan usaha dan cara memilih layanan.
require __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Beranda';
require __DIR__ . '/includes/header.php';
?>
<section class="hero" id="beranda">
    <div class="hero-copy">
        <p class="eyebrow">RACIKAN TRADISI · JAMU HERBAL</p>
        <h1>Jamu tradisi,<br><em>segar setiap hari.</em></h1>
        <p>Beras kencur dan kunir asem, diracik dengan bahan pilihan. Pesan jamu siap minum untuk pengantaran lokal, atau buat sendiri dari resep rumahan kami.</p>
        <a class="button" href="produk.php">Pilih jamu favorit <span>↘</span></a>
        <div class="hero-points"><span>✓ Racikan rumahan</span><span>✓ Rp8.000 / botol</span></div>
    </div>
    <div class="hero-art">
        <div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div>
        <div class="hero-products">
            <figure class="hero-product hero-product-beras"><img src="assets/beras-kencur.png" alt="Botol jamu beras kencur"><figcaption>Beras Kencur</figcaption></figure>
            <figure class="hero-product hero-product-kunir"><img src="assets/kunir-asem.png" alt="Botol jamu kunir asem"><figcaption>Kunir Asem</figcaption></figure>
        </div>
    </div>
</section>
<section class="intro"><div><p class="eyebrow">TENTANG JAMU KAMI</p><h2>Warisan rasa,<br>diracik sederhana.</h2></div><p>Kami menghadirkan dua jamu rumahan yang akrab di lidah: beras kencur dan kunir asem. Nikmati jamu siap minum untuk pengantaran lokal, atau coba resepnya di rumah jika tinggal jauh dari area layanan.</p></section>
<section class="promise"><div><span>01</span><b>Bahan sederhana</b><small>Rempah pilihan, rasa alami</small></div><div><span>02</span><b>Diracik segar</b><small>Dibuat dengan teliti</small></div><div><span>03</span><b>Pilih cara menikmati</b><small>Beli botolan atau racik sendiri</small></div></section>
<section class="home-choices"><a class="choice-card choice-buy" href="produk.php"><span class="choice-icon">✳</span><p class="eyebrow">JAMU SIAP MINUM</p><h2>Pilih botol favoritmu.</h2><p>Beras kencur atau kunir asem, masing-masing Rp8.000 per botol.</p><b>Lihat produk <span>→</span></b></a><a class="choice-card choice-make" href="resep.php"><span class="choice-icon">♨</span><p class="eyebrow">RACIK SENDIRI</p><h2>Ikuti resep rumahan.</h2><p>Pelajari bahan dan langkah membuat kedua varian jamu.</p><b>Buka resep <span>→</span></b></a></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
