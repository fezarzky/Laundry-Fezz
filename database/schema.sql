-- Database Schema untuk Laundry Fezz
-- Buat database terlebih dahulu: CREATE DATABASE laundry_fezz;

USE laundry_fezz;

-- Tabel jenis_kelamin
CREATE TABLE jenis_kelamin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_jk VARCHAR(10) NOT NULL UNIQUE,
    jenis_kelamin VARCHAR(20) NOT NULL
);

-- Insert data jenis kelamin
INSERT INTO jenis_kelamin (kode_jk, jenis_kelamin) VALUES 
('L', 'Laki-laki'),
('P', 'Perempuan');

-- Tabel user (pelanggan dan admin)
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    akses_id INT NOT NULL DEFAULT 2, -- 1=Admin, 2=Pelanggan
    kode_pelanggan VARCHAR(50) NOT NULL UNIQUE,
    nama_pelanggan VARCHAR(100) NOT NULL,
    jenis_kelamin VARCHAR(10) NOT NULL,
    alamat TEXT NOT NULL,
    email VARCHAR(100) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (jenis_kelamin) REFERENCES jenis_kelamin(kode_jk)
);

-- Insert data admin default
INSERT INTO user (username, password, akses_id, kode_pelanggan, nama_pelanggan, jenis_kelamin, alamat, email, no_hp) VALUES 
('admin', 'admin123', 1, 'ADM001', 'Administrator', 'L', 'Jl. Admin No. 1', 'admin@laundryfezz.com', '081234567890');

-- Tabel paket laundry
CREATE TABLE paket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_paket VARCHAR(50) NOT NULL UNIQUE,
    paket VARCHAR(100) NOT NULL,
    harga_paket DECIMAL(10,2) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert data paket default
INSERT INTO paket (kode_paket, paket, harga_paket, deskripsi) VALUES 
('PKT001', 'Cuci Kering', 5000.00, 'Layanan cuci dan kering biasa'),
('PKT002', 'Cuci Setrika', 7000.00, 'Layanan cuci, kering, dan setrika'),
('PKT003', 'Cuci Express', 10000.00, 'Layanan cuci cepat selesai dalam 24 jam'),
('PKT004', 'Dry Clean', 15000.00, 'Layanan dry cleaning untuk pakaian khusus');

-- Tabel transaksi
CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi VARCHAR(50) NOT NULL UNIQUE,
    kode_pelanggan VARCHAR(50) NOT NULL,
    kode_paket VARCHAR(50) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    kilo DECIMAL(5,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_keluar DATE NULL,
    status ENUM('Pending', 'Proses', 'Selesai', 'Diambil') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kode_pelanggan) REFERENCES user(kode_pelanggan) ON DELETE CASCADE,
    FOREIGN KEY (kode_paket) REFERENCES paket(kode_paket) ON DELETE CASCADE
);

-- Insert data transaksi sample
INSERT INTO transaksi (kode_transaksi, kode_pelanggan, kode_paket, harga, kilo, total, tanggal_masuk, status) VALUES 
('TRX20250130001', 'ADM001', 'PKT001', 5000.00, 2.5, 12500.00, '2025-01-30', 'Selesai');

-- View untuk laporan transaksi lengkap
CREATE VIEW view_transaksi_lengkap AS
SELECT 
    t.kode_transaksi,
    t.kode_pelanggan,
    u.nama_pelanggan,
    u.no_hp,
    t.kode_paket,
    p.paket,
    t.harga,
    t.kilo,
    t.total,
    t.tanggal_masuk,
    t.tanggal_keluar,
    t.status,
    t.created_at
FROM transaksi t
JOIN user u ON t.kode_pelanggan = u.kode_pelanggan
JOIN paket p ON t.kode_paket = p.kode_paket
ORDER BY t.created_at DESC;

-- Index untuk optimasi performa
CREATE INDEX idx_user_username ON user(username);
CREATE INDEX idx_user_kode_pelanggan ON user(kode_pelanggan);
CREATE INDEX idx_transaksi_kode_pelanggan ON transaksi(kode_pelanggan);
CREATE INDEX idx_transaksi_tanggal_masuk ON transaksi(tanggal_masuk);
CREATE INDEX idx_transaksi_status ON transaksi(status);

-- Stored Procedure untuk laporan harian
DELIMITER //
CREATE PROCEDURE GetLaporanHarian(IN tanggal DATE)
BEGIN
    SELECT 
        COUNT(*) as total_transaksi,
        SUM(total) as total_pendapatan,
        AVG(total) as rata_rata_transaksi
    FROM transaksi 
    WHERE DATE(tanggal_masuk) = tanggal;
END //
DELIMITER ;

-- Stored Procedure untuk laporan bulanan
DELIMITER //
CREATE PROCEDURE GetLaporanBulanan(IN bulan INT, IN tahun INT)
BEGIN
    SELECT 
        DATE(tanggal_masuk) as tanggal,
        COUNT(*) as total_transaksi,
        SUM(total) as total_pendapatan
    FROM transaksi 
    WHERE MONTH(tanggal_masuk) = bulan AND YEAR(tanggal_masuk) = tahun
    GROUP BY DATE(tanggal_masuk)
    ORDER BY tanggal;
END //
DELIMITER ;

-- Function untuk generate kode otomatis
DELIMITER //
CREATE FUNCTION GenerateKodeTransaksi() RETURNS VARCHAR(50)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE next_id INT;
    SELECT COALESCE(MAX(id), 0) + 1 INTO next_id FROM transaksi;
    RETURN CONCAT('TRX', DATE_FORMAT(NOW(), '%Y%m%d'), LPAD(next_id, 3, '0'));
END //
DELIMITER ;

-- Trigger untuk auto update tanggal_keluar ketika status menjadi Selesai
DELIMITER //
CREATE TRIGGER update_tanggal_keluar
BEFORE UPDATE ON transaksi
FOR EACH ROW
BEGIN
    IF NEW.status = 'Selesai' AND OLD.status != 'Selesai' THEN
        SET NEW.tanggal_keluar = CURDATE();
    END IF;
END //
DELIMITER ;
