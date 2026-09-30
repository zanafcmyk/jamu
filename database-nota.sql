-- Jalankan satu kali pada database jamu_tipes untuk menambahkan status pembayaran dan akses nota.
USE jamu_tipes;
ALTER TABLE orders
    ADD COLUMN payment_status VARCHAR(40) NOT NULL DEFAULT 'Belum dibayar',
    ADD COLUMN receipt_token CHAR(64) NOT NULL DEFAULT '';

-- Beri token akses pada pesanan lama agar tidak menyisakan token kosong.
UPDATE orders SET receipt_token = SHA2(CONCAT(id, UUID()), 256) WHERE receipt_token = '';
