<?php
// Halaman katalog; tombol tambah menyimpan produk ke keranjang sesi.
require __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add' && isset($products[$_POST['slug'] ?? ''])) {
    $slug = $_POST['slug'];
    $_SESSION['cart'][$slug] = min(99, ($_SESSION['cart'][$slug] ?? 0) + 1);
    header('Location: keranjang.php?added=1');
    exit;
}
$pageTitle = 'Produk';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><p class="eyebrow">PILIHAN HARI INI</p><h1>Jamu siap minum</h1><p>Diracik segar, dikemas dalam botol, dan tersedia dalam dua rasa tradisional.</p><span class="page-price">Rp8.000 <small>/ botol</small></span></section>
<section class="products section"><div class="product-grid">
<?php foreach ($products as $slug => $product): ?>
<article class="product-card"><div class="product-visual photo-product <?= htmlspecialchars($product['color']) ?>"><span class="fresh-tag">✦ DIRACIK SEGAR</span><div class="photo-bottle"><img src="<?= $slug === 'beras-kencur' ? 'assets/beras-kencur.png' : 'assets/kunir-asem.png' ?>" alt="Botol jamu <?= htmlspecialchars($product['name']) ?>"></div></div><div class="product-info"><div><h3><?= htmlspecialchars($product['name']) ?></h3><p><?= htmlspecialchars($product['description']) ?></p></div><strong class="price"><?= rupiah($product['price']) ?><small> / botol</small></strong></div><form method="post" action="produk.php"><input type="hidden" name="action" value="add"><input type="hidden" name="slug" value="<?= htmlspecialchars($slug) ?>"><button class="add-button" type="submit">Tambah ke keranjang <span>＋</span></button></form></article>
<?php endforeach; ?></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
