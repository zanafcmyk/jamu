<?php
// Pengaturan bersama untuk semua halaman: sesi belanja, database, dan produk.
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$dbError = '';
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=jamu_tipes;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    $pdo = null;
    $dbError = 'Database belum tersambung. Pastikan MySQL Laragon berjalan dan database jamu_tipes sudah dibuat.';
}

// Katalog cadangan ditampilkan jika database sedang belum tersedia.
$products = [
    'beras-kencur' => ['name' => 'Beras Kencur', 'price' => 8000, 'color' => 'cream', 'description' => 'Jamu tradisional dengan rasa lembut dan aroma kencur yang khas.'],
    'kunir-asem' => ['name' => 'Kunir Asem', 'price' => 8000, 'color' => 'gold', 'description' => 'Perpaduan kunyit dan asam dengan rasa segar.'],
];
if ($pdo) {
    foreach ($pdo->query('SELECT slug, name, price, description FROM products') as $row) {
        if (isset($products[$row['slug']])) $products[$row['slug']] = array_merge($products[$row['slug']], $row);
    }
}
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cartCount = array_sum($_SESSION['cart']);
function rupiah($amount) { return 'Rp' . number_format((int)$amount, 0, ',', '.'); }
?>
