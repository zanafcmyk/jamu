<?php
// Nota hanya dapat dibuka dengan nomor pesanan dan token rahasia yang dibuat saat checkout.
require __DIR__ . '/includes/bootstrap.php';
$order = null;
$items = [];
$orderNumber = filter_input(INPUT_GET, 'no', FILTER_VALIDATE_INT);
$token = $_GET['token'] ?? '';
if ($pdo && $orderNumber && preg_match('/^[a-f0-9]{64}$/', $token)) {
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND receipt_token = ?');
    $stmt->execute([$orderNumber, $token]);
    $order = $stmt->fetch();
    if ($order) {
        $itemStmt = $pdo->prepare('SELECT product_name, quantity, unit_price FROM order_items WHERE order_id = ?');
        $itemStmt->execute([$orderNumber]);
        $items = $itemStmt->fetchAll();
    }
}
$pageTitle = 'Nota pesanan';
require __DIR__ . '/includes/header.php';
?>
<?php if (!$order): ?>
<section class="receipt-error"><h1>Nota tidak ditemukan</h1><p>Periksa tautan nota, atau kembali ke halaman keranjang.</p><a class="button" href="produk.php">Kembali ke produk <span>→</span></a></section>
<?php else: ?>
<section class="receipt-page">
    <article class="receipt-card">
        <div class="receipt-top"><img src="assets/jamu-logo.png" alt="Logo Jamu"><div><p class="eyebrow">BUKTI PESANAN</p><h1>Nota Jamu</h1><span>Nomor #<?= (int)$order['id'] ?></span></div><span class="receipt-status <?= $order['payment_status'] === 'Lunas' ? 'paid' : 'pending' ?>"><?= htmlspecialchars($order['payment_status']) ?></span></div>
        <div class="receipt-meta"><div><small>Tanggal pesanan</small><b><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></b></div><div><small>Nama pembeli</small><b><?= htmlspecialchars($order['customer_name']) ?></b></div><div><small>Alamat pengantaran</small><b><?= nl2br(htmlspecialchars($order['address'])) ?></b></div></div>
        <div class="receipt-items"><div class="receipt-row receipt-head"><span>Produk</span><span>Jumlah</span><span>Harga</span></div><?php foreach ($items as $item): ?><div class="receipt-row"><span><?= htmlspecialchars($item['product_name']) ?></span><span><?= (int)$item['quantity'] ?> botol</span><span><?= rupiah($item['quantity'] * $item['unit_price']) ?></span></div><?php endforeach; ?></div>
        <div class="receipt-total"><span>Total pesanan</span><strong><?= rupiah($order['total']) ?></strong></div>
        <div class="receipt-payment"><div><small>Metode pembayaran</small><b><?= htmlspecialchars($order['payment_method']) ?></b></div><p><?= $order['payment_status'] === 'Lunas' ? 'Pembayaran telah dikonfirmasi. Simpan nota ini sebagai bukti pembayaran.' : 'Nota ini adalah bukti pesanan. Nota pembayaran lunas tersedia setelah penjual mengonfirmasi pembayaran.' ?></p></div>
        <?php if ($order['delivery_latitude'] && $order['delivery_longitude']): ?><a class="receipt-map" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat=<?= urlencode($order['delivery_latitude']) ?>&amp;mlon=<?= urlencode($order['delivery_longitude']) ?>#map=17/<?= urlencode($order['delivery_latitude']) ?>/<?= urlencode($order['delivery_longitude']) ?>">Lihat titik pengantaran di peta ↗</a><?php endif; ?>
        <div class="receipt-actions"><button class="button" type="button" onclick="window.print()">Cetak nota <span>⎙</span></button><a class="text-button" href="index.php">Kembali ke beranda</a></div>
    </article>
</section>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
