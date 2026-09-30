<?php
// Halaman keranjang dan checkout; transaksi disimpan ke tabel orders dan order_items.
require __DIR__ . '/includes/bootstrap.php';
$notice = '';
$latitude = null;
$longitude = null;
$includeMap = true;

// Tombol tambah dan kurang memperbarui jumlah untuk tiap produk secara terpisah.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_cart') {
    $slug = $_POST['slug'] ?? '';
    $change = (int)($_POST['change'] ?? 0);
    if (isset($products[$slug])) {
        $newQuantity = ($_SESSION['cart'][$slug] ?? 0) + $change;
        if ($newQuantity <= 0) unset($_SESSION['cart'][$slug]);
        else $_SESSION['cart'][$slug] = min(99, $newQuantity);
    }
    header('Location: keranjang.php#ringkasan');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'checkout') {
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $latitude = filter_var($_POST['delivery_latitude'] ?? null, FILTER_VALIDATE_FLOAT);
    $longitude = filter_var($_POST['delivery_longitude'] ?? null, FILTER_VALIDATE_FLOAT);
    $paymentChoice = $_POST['payment_method'] ?? 'cod';
    $paymentMethod = $paymentChoice === 'transfer' ? 'Transfer bank (manual)' : 'Bayar saat diterima (COD)';
    if (!$pdo) $notice = $dbError;
    elseif (!$name || !$phone || !$address || !$_SESSION['cart'] || !in_array($paymentChoice, ['cod', 'transfer'], true)) $notice = 'Lengkapi data pesanan, pilih metode pembayaran, dan pastikan keranjang terisi.';
    elseif ($latitude === false || $longitude === false) $notice = 'Pilih titik lokasi pengantaran pada peta terlebih dahulu.';
    elseif (sqrt(pow(($latitude - -7.579) * 111.0, 2) + pow(($longitude - 110.808) * 111.0 * cos(deg2rad(-7.579)), 2)) > 4.0) $notice = 'Titik pengantaran berada di luar jangkauan sekitar. Silakan pilih lokasi yang lebih dekat atau lihat halaman Resep.';
    else {
        $total = 0;
        foreach ($_SESSION['cart'] as $slug => $qty) if (isset($products[$slug])) $total += $products[$slug]['price'] * $qty;
        try {
            $pdo->beginTransaction();
            // Nota memakai token acak agar detail pesanan tidak bisa dibuka hanya dengan nomor urut.
            $receiptToken = bin2hex(random_bytes(32));
            $paymentStatus = $paymentChoice === 'transfer' ? 'Menunggu verifikasi transfer' : 'Belum dibayar - COD';
            $stmt = $pdo->prepare('INSERT INTO orders (customer_name, phone, address, total, payment_method, payment_status, receipt_token, delivery_latitude, delivery_longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $phone, $address, $total, $paymentMethod, $paymentStatus, $receiptToken, $latitude, $longitude]);
            $orderId = (int)$pdo->lastInsertId();
            $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_slug, product_name, quantity, unit_price) VALUES (?, ?, ?, ?, ?)');
            foreach ($_SESSION['cart'] as $slug => $qty) if (isset($products[$slug])) $itemStmt->execute([$orderId, $slug, $products[$slug]['name'], $qty, $products[$slug]['price']]);
            $pdo->commit();
            $_SESSION['cart'] = [];
            header('Location: nota.php?no=' . $orderId . '&token=' . $receiptToken);
            exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $notice = 'Pesanan belum tersimpan. Periksa koneksi database lalu coba lagi.';
        }
    }
}
$cartCount = array_sum($_SESSION['cart']);
$cartTotal = 0;
foreach ($_SESSION['cart'] as $slug => $qty) if (isset($products[$slug])) $cartTotal += $products[$slug]['price'] * $qty;
$pageTitle = 'Keranjang dan pembayaran';
$mapLat = $latitude ?? '';
$mapLng = $longitude ?? '';
require __DIR__ . '/includes/header.php';
?>
<section class="order-section order-page" id="ringkasan"><div class="order-intro"><p class="eyebrow">PESANANMU</p><h2>Keranjang &<br>pembayaran</h2><p>Pilih jumlah setiap varian. Kamu bisa membeli beras kencur dan kunir asem sekaligus dalam satu pesanan.</p><div class="delivery"><span>⌖</span><div><b>Area pengantaran</b><small>Sekitar area layanan</small></div></div></div><div class="checkout-card"><h3>Ringkasan keranjang</h3>
<?php if (!$cartCount): ?><p class="empty">Keranjangmu masih kosong. <a href="produk.php">Pilih jamu dari halaman produk.</a></p><?php else: ?><div class="cart-lines"><?php foreach ($_SESSION['cart'] as $slug => $qty): if (!isset($products[$slug])) continue; ?><div class="cart-line"><span><b><?= htmlspecialchars($products[$slug]['name']) ?></b><small><?= rupiah($products[$slug]['price']) ?> / botol</small></span><div class="quantity-control"><form method="post"><input type="hidden" name="action" value="update_cart"><input type="hidden" name="slug" value="<?= htmlspecialchars($slug) ?>"><input type="hidden" name="change" value="-1"><button aria-label="Kurangi <?= htmlspecialchars($products[$slug]['name']) ?>">−</button></form><b><?= $qty ?></b><form method="post"><input type="hidden" name="action" value="update_cart"><input type="hidden" name="slug" value="<?= htmlspecialchars($slug) ?>"><input type="hidden" name="change" value="1"><button aria-label="Tambah <?= htmlspecialchars($products[$slug]['name']) ?>">+</button></form></div><strong><?= rupiah($products[$slug]['price'] * $qty) ?></strong></div><?php endforeach; ?></div><div class="total"><span>Total</span><b><?= rupiah($cartTotal) ?></b></div><?php endif; ?>
<form method="post" class="checkout-form"><input type="hidden" name="action" value="checkout"><input type="hidden" name="delivery_latitude" id="delivery-latitude" value="<?= htmlspecialchars((string)$mapLat) ?>"><input type="hidden" name="delivery_longitude" id="delivery-longitude" value="<?= htmlspecialchars((string)$mapLng) ?>"><label>Nama<input name="customer_name" placeholder="Nama penerima" required></label><label>Nomor WhatsApp<input name="phone" placeholder="08xxxxxxxxxx" required></label><label>Alamat pengantaran<input name="address" placeholder="Masukkan alamat lengkap" required></label><div class="map-label"><b>Pilih titik pengantaran di peta</b><small>Klik peta untuk menandai lokasi. Jangkauan sekitar 4 km dari area layanan.</small></div><div id="delivery-map" role="application" aria-label="Peta lokasi pengantaran"></div><div class="map-actions"><button type="button" id="use-location" class="map-button">Gunakan lokasi saya</button><span id="picked-coordinates">Pilih titik di peta</span></div><label>Metode pembayaran<select name="payment_method" required><option value="cod">Bayar saat diterima (COD)</option><option value="transfer">Transfer bank (konfirmasi manual)</option></select></label><button class="button full" type="submit" <?= !$cartCount ? 'disabled' : '' ?>>Buat pesanan <span>→</span></button><?php if ($notice): ?><p class="notice"><?= htmlspecialchars($notice) ?></p><?php endif; ?></form></div></section>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
// Peta berpusat di area layanan; klik untuk menaruh pin lokasi tujuan.
const startPoint = [-7.579, 110.808];
const map = L.map('delivery-map').setView(startPoint, 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; OpenStreetMap'}).addTo(map);
// Lingkaran menunjukkan batas antar sekitar 4 km dari titik pusat layanan.
L.circle(startPoint, {radius: 4000, color: '#687348', weight: 2, fillColor: '#90966a', fillOpacity: 0.09}).addTo(map);
let marker;
const latInput = document.getElementById('delivery-latitude');
const lngInput = document.getElementById('delivery-longitude');
const coordinateText = document.getElementById('picked-coordinates');
function choosePoint(lat, lng) {
    if (marker) marker.setLatLng([lat, lng]); else marker = L.marker([lat, lng]).addTo(map);
    latInput.value = lat.toFixed(7); lngInput.value = lng.toFixed(7);
    coordinateText.textContent = `Lokasi dipilih: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;
}
if (latInput.value && lngInput.value) choosePoint(parseFloat(latInput.value), parseFloat(lngInput.value));
map.on('click', event => choosePoint(event.latlng.lat, event.latlng.lng));
document.getElementById('use-location').addEventListener('click', () => {
    if (!navigator.geolocation) { coordinateText.textContent = 'Browser tidak mendukung lokasi otomatis.'; return; }
    navigator.geolocation.getCurrentPosition(position => {
        const point = [position.coords.latitude, position.coords.longitude];
        map.setView(point, 17); choosePoint(point[0], point[1]);
    }, () => { coordinateText.textContent = 'Lokasi tidak tersedia. Silakan klik titik pada peta.'; });
});
</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
