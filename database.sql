-- Jalankan file ini di HeidiSQL untuk membuat database toko jamu.
CREATE DATABASE IF NOT EXISTS jamu_tipes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jamu_tipes;

-- Katalog jamu yang dijual, dengan harga per botol dalam rupiah.
CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    price INT UNSIGNED NOT NULL DEFAULT 8000,
    description VARCHAR(255) NOT NULL
);

-- Data pelanggan dan ringkasan pesanan.
CREATE TABLE IF NOT EXISTS orders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(120) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address TEXT NOT NULL,
    total INT UNSIGNED NOT NULL,
    payment_method VARCHAR(80) NOT NULL,
    payment_status VARCHAR(40) NOT NULL DEFAULT 'Belum dibayar',
    receipt_token CHAR(64) NOT NULL DEFAULT '',
    delivery_latitude DECIMAL(10,7) NULL,
    delivery_longitude DECIMAL(10,7) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Rincian produk untuk setiap pesanan.
CREATE TABLE IF NOT EXISTS order_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id INT UNSIGNED NOT NULL,
    product_slug VARCHAR(50) NOT NULL,
    product_name VARCHAR(100) NOT NULL,
    quantity INT UNSIGNED NOT NULL,
    unit_price INT UNSIGNED NOT NULL,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Tambahkan dua produk awal; aman dijalankan ulang tanpa menggandakan produk.
INSERT INTO products (slug, name, price, description) VALUES
('beras-kencur', 'Beras Kencur', 8000, 'Jamu tradisional dengan rasa lembut dan aroma kencur yang khas.'),
('kunir-asem', 'Kunir Asem', 8000, 'Perpaduan kunyit dan asam dengan rasa segar.')
ON DUPLICATE KEY UPDATE name=VALUES(name), price=VALUES(price), description=VALUES(description);
