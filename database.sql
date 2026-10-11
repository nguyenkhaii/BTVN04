CREATE DATABASE IF NOT EXISTS quanly_tainghe
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE quanly_tainghe;

DROP VIEW IF EXISTS v_tainghe;
DROP TABLE IF EXISTS tainghe;
DROP TABLE IF EXISTS hang;

CREATE TABLE hang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ten_hang VARCHAR(100) NOT NULL
);

CREATE TABLE tainghe (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ten VARCHAR(150) NOT NULL,
  hang_id INT NOT NULL,
  kieu VARCHAR(50) NOT NULL,
  ket_noi VARCHAR(50) NOT NULL,
  gia DECIMAL(12,0) NOT NULL DEFAULT 0,
  so_luong INT NOT NULL DEFAULT 0,
  mo_ta VARCHAR(255),
  ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (hang_id) REFERENCES hang(id)
);

INSERT INTO hang (ten_hang) VALUES
('Sony'),
('JBL'),
('Apple'),
('Sennheiser'),
('Anker');

INSERT INTO tainghe (ten, hang_id, kieu, ket_noi, gia, so_luong, mo_ta) VALUES
('Sony WH-1000XM5', 1, 'Over-ear', 'Bluetooth', 7490000, 12, 'Chống ồn chủ động'),
('Sony WF-1000XM5', 1, 'True wireless', 'Bluetooth', 5990000, 15, 'Có hộp sạc'),
('JBL Tune 520BT', 2, 'On-ear', 'Bluetooth', 1090000, 30, 'Pin lâu'),
('JBL Tune 310C', 2, 'In-ear', 'Có dây', 350000, 40, 'Cổng USB-C'),
('AirPods Pro 2', 3, 'True wireless', 'Bluetooth', 5890000, 20, 'Chống ồn, có hộp sạc'),
('Sennheiser HD 450BT', 4, 'Over-ear', 'Bluetooth', 2990000, 7, 'Chống ồn chủ động'),
('Soundcore Q20i', 5, 'Over-ear', 'Bluetooth', 1190000, 25, 'Pin lâu, giá mềm');

CREATE VIEW v_tainghe AS
SELECT t.id, t.ten, t.hang_id, h.ten_hang, t.kieu, t.ket_noi, t.gia, t.so_luong, t.mo_ta
FROM tainghe t
JOIN hang h ON t.hang_id = h.id;