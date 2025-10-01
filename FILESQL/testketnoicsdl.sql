-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 01, 2025 lúc 08:05 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `testketnoicsdl`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `account`
--

INSERT INTO `account` (`id`, `username`, `password`, `role`, `create_time`, `update_time`) VALUES
(1, 'Minh', 'Minh', 'user', '2025-02-26 07:17:53', '2025-03-17 16:41:49'),
(2, 'admin', 'admin', 'admin', '2025-02-26 07:18:03', '2025-02-26 07:18:17'),
(21, 'User123', 'Abc@1', 'user', '2025-09-30 09:16:56', '2025-09-30 09:16:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_hoa_don`
--

CREATE TABLE `chi_tiet_hoa_don` (
  `id` int(11) NOT NULL,
  `ma_hoa_don` int(11) NOT NULL,
  `ma_san_pham` int(11) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `gia_san_pham` decimal(20,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_hoa_don`
--

INSERT INTO `chi_tiet_hoa_don` (`id`, `ma_hoa_don`, `ma_san_pham`, `so_luong`, `gia_san_pham`) VALUES
(13, 12, 8, 2, 17),
(14, 13, 8, 1, 17000000),
(15, 14, 7, 2, 34490000),
(16, 15, 7, 2, 34490000),
(17, 15, 9, 1, 28235567),
(18, 24, 7, 1, 34490000),
(20, 26, 9, 7, 28235567),
(21, 27, 7, 1, 34490000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gio_hang`
--

CREATE TABLE `gio_hang` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ma_san_pham` int(11) NOT NULL,
  `so_luong` int(11) DEFAULT 1,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `gio_hang`
--

INSERT INTO `gio_hang` (`id`, `username`, `ma_san_pham`, `so_luong`, `create_time`, `update_time`) VALUES
(69, 'Minh', 9, 1, '2025-09-22 09:07:12', '2025-09-22 09:07:12'),
(73, 'admin', 7, 1, '2025-10-01 17:05:47', '2025-10-01 17:05:47');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `ma_hoa_don` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `tong_tien` decimal(20,0) NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ten` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(20) NOT NULL,
  `dia_chi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`ma_hoa_don`, `username`, `tong_tien`, `ngay_tao`, `ten`, `so_dien_thoai`, `dia_chi`) VALUES
(12, 'Minh', 34, '2025-03-17 08:51:19', 'Hoàng Quang Minh', '000000000000000011', 'HN tk CB'),
(13, 'Minh', 17000000, '2025-03-17 09:30:49', 'Hoàng Quang Minh', '000000000000000012', 'HN tk CB'),
(14, 'Minh', 68980000, '2025-03-17 10:41:46', 'Hoàng Quang Minh', '00000000000022', 'HN tk CB'),
(15, 'Minh', 97215567, '2025-03-19 17:39:01', 'Hoàng Quang Minhhhh', '12341240000', 'HN tk CBBB'),
(24, 'admin', 34490000, '2025-09-21 14:39:56', 'test', 'a', 's'),
(25, 'admin', 100, '2025-09-22 08:42:35', 'testaa', 'a', 'sâ'),
(26, 'admin', 197648969, '2025-09-22 08:43:28', 'testaasdasd', 'a', 'sâ'),
(27, 'admin', 34490000, '2025-10-01 17:05:30', 'testaasdasd', 'a', 'sâ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `ma_san_pham` int(11) NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `hang_sp` varchar(255) NOT NULL,
  `gia_san_pham` decimal(20,0) NOT NULL,
  `gia_cu` decimal(20,0) DEFAULT NULL,
  `mo_ta_ngan` text DEFAULT NULL,
  `mo_ta_chi_tiet` text DEFAULT NULL,
  `so_luong` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`ma_san_pham`, `ten_san_pham`, `hang_sp`, `gia_san_pham`, `gia_cu`, `mo_ta_ngan`, `mo_ta_chi_tiet`, `so_luong`, `image`, `create_time`, `update_time`) VALUES
(7, 'Laptop ASUS Vivobook S 14 S5406SA PP059WS', 'ASUS', 34490000, 36000000, 'Laptop cấu hình khủng', 'CPU:Intel Core™ Ultra 7 258V up to 4.80Ghz, 8 Cores (4P+4PLE), 8 Threads, 12MB Intel® Smart Cache\r\nRAM:32GB LPDDR5X Onboard (Không nâng cấp)\r\nỔ cứng:1TB SSD M.2 NVMe™ PCIe® 4.0\r\nCard màn hình:Intel® Arc™ Graphics 140V', 94, 'pp059w_f967e9d44fc045e0abecc2cce05b2669_1024x1024.webp', '2025-03-17 08:35:13', '2025-10-01 17:05:30'),
(8, 'Laptop Lenovo Ideapad Slim 5 16IAH8 83BG001XVN', 'Lenovo', 17000000, 100000000, '✔ Bảo hành chính hãng 36 tháng. \r\n\r\n✔ Hỗ trợ đổi mới trong 7 ngày. \r\n\r\n✔ Windows bản quyền tích hợp. \r\n\r\n✔ Miễn phí giao hàng toàn quốc.', 'CPU	Intel® Core™ i5-12450H, 8 Cores (4P + 4E) / 12 Threads, P-core 2.0 / 4.4GHz, E-core 1.5 / 3.3GHz, 12MB\r\nRam	16GB Onboard LPDDR5 4800Mhz\r\nSSD	512GB SSD M.2 2242 PCIe® 4.0x4 NVMe\r\nCard đồ họa	Intel® UHD Graphics\r\nMàn hình	16\" WUXGA (1920x1200) IPS 300nits Anti-glare, 45% NTSC, TÜV Low Blue Light', 0, '83bg001xvn_3758ad36a83946118a7a55e02437eef5_1024x1024.webp', '2025-03-17 08:37:37', '2025-03-19 16:36:51'),
(9, 'Laptop gaming ASUS TUF Gaming F16 FX607JU N3139W', 'ASUS', 28235567, 100000000, 'SALE', 'Intel® Core™ i7-13650HX\r\nRAM	16GB\r\nỔ cứng	512GB\r\nCard đồ họa	NVIDIA® GeForce RTX™ 4050', 2, 'asus-tuf-gaming-f16-fx607ju-n3139w_841813240f404d82beed365aadec1334_4c8aafba96d04d9dbc22df3363377ca4_1024x1024.webp', '2025-03-17 08:41:47', '2025-09-22 09:10:27');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ma_hoa_don` (`ma_hoa_don`),
  ADD KEY `ma_san_pham` (`ma_san_pham`);

--
-- Chỉ mục cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`),
  ADD KEY `ma_san_pham` (`ma_san_pham`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`ma_hoa_don`),
  ADD KEY `username` (`username`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`ma_san_pham`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  MODIFY `ma_hoa_don` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `ma_san_pham` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  ADD CONSTRAINT `chi_tiet_hoa_don_ibfk_1` FOREIGN KEY (`ma_hoa_don`) REFERENCES `hoa_don` (`ma_hoa_don`),
  ADD CONSTRAINT `chi_tiet_hoa_don_ibfk_2` FOREIGN KEY (`ma_san_pham`) REFERENCES `sanpham` (`ma_san_pham`);

--
-- Các ràng buộc cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `gio_hang_ibfk_1` FOREIGN KEY (`username`) REFERENCES `account` (`username`),
  ADD CONSTRAINT `gio_hang_ibfk_2` FOREIGN KEY (`ma_san_pham`) REFERENCES `sanpham` (`ma_san_pham`);

--
-- Các ràng buộc cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD CONSTRAINT `hoa_don_ibfk_1` FOREIGN KEY (`username`) REFERENCES `account` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
