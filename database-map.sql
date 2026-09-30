-- Jalankan SATU KALI pada database jamu_tipes yang sudah dibuat sebelumnya.
-- Kolom ini menyimpan koordinat pin pengantaran yang dipilih pada peta.
USE jamu_tipes;
ALTER TABLE orders
    ADD COLUMN delivery_latitude DECIMAL(10,7) NULL,
    ADD COLUMN delivery_longitude DECIMAL(10,7) NULL;
