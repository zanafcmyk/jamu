<?php $pageTitle = $pageTitle ?? 'Jamu'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> — Jamu</title>
    <link rel="stylesheet" href="style.css">
    <?php if (!empty($includeMap)): ?>
    <!-- Leaflet dipakai hanya di halaman checkout untuk memilih titik peta. -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <?php endif; ?>
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php"><img src="assets/jamu-logo.png" alt="Logo Jamu"><span>JAMU <small>RACIKAN TRADISIONAL</small></span></a>
    <nav><a href="index.php">Beranda</a><a href="produk.php">Produk</a><a href="keranjang.php">Keranjang <b><?= $cartCount ?></b></a><a href="resep.php">Resep</a><a href="tentang.php">Tentang &amp; Kontak</a></nav>
</header>
<main>
