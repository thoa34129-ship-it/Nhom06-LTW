-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 01:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vexemphim`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `MaAdmin` int(11) NOT NULL,
  `HoVaTen` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `MatKhau` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`MaAdmin`, `HoVaTen`, `Email`, `SoDienThoai`, `MatKhau`) VALUES
(1, 'Phạm Xuân Lộc', 'admin@xemphim.com', '0901234567', '$2y$10$iOQziBavA.j5J.3Tala84eJif1S4M4cfpbfhYbUZV5KiEOenNZdbG');

-- --------------------------------------------------------

--
-- Table structure for table `chitietdv`
--

CREATE TABLE `chitietdv` (
  `MaChiTiet` int(11) NOT NULL,
  `MaDatVe` varchar(20) NOT NULL,
  `MaSuatChieu` int(11) NOT NULL,
  `MaGhe` int(11) DEFAULT NULL,
  `GiaVe` decimal(10,2) NOT NULL,
  `SoLuong` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `chitietdv`
--

INSERT INTO `chitietdv` (`MaChiTiet`, `MaDatVe`, `MaSuatChieu`, `MaGhe`, `GiaVe`, `SoLuong`) VALUES
(23, 'GH202511159562', 4, 21, 100000.00, 1),
(22, 'DV202511159820', 10, 21, 100000.00, 1),
(21, 'DV202511156125', 6, NULL, 50000.00, 1),
(20, 'DV202511156125', 4, NULL, 80000.00, 1),
(19, 'DV202511159353', 9, NULL, 50000.00, 1),
(17, 'DV202511142098', 1, NULL, 80000.00, 1),
(18, 'DV202511142098', 1, NULL, 80000.00, 1),
(24, 'DV202511161889', 4, 1, 50000.00, 1),
(25, 'DV202511161889', 6, 3, 100000.00, 1),
(26, 'DV202511160277', 3, 3, 100000.00, 1),
(27, 'DV202511165785', 3, 1, 50000.00, 1),
(28, 'DV202511166866', 3, 2, 80000.00, 1),
(29, 'DV202511162807', 4, NULL, 100000.00, 1),
(30, 'DV202511177585', 3, NULL, 50000.00, 1),
(31, 'DV202511177585', 3, NULL, 50000.00, 1),
(32, 'DV202511177452', 9, 2, 80000.00, 1),
(33, 'DV202511178963', 6, 2, 80000.00, 1),
(34, 'DV202511246343', 7, 1, 50000.00, 1),
(35, 'DV202511245044', 5, 3, 100000.00, 1),
(36, 'DV202511249293', 10, 1, 50000.00, 1),
(37, 'DV202511240761', 1, 2, 80000.00, 3);

-- --------------------------------------------------------

--
-- Table structure for table `datve_thanhtoan`
--

CREATE TABLE `datve_thanhtoan` (
  `MaDatVe` varchar(20) NOT NULL,
  `MaKhachHang` int(11) NOT NULL,
  `ThoiGianDat` datetime NOT NULL,
  `TongTien` decimal(12,2) NOT NULL,
  `PosterURL` varchar(255) DEFAULT NULL,
  `TrangThaiThanhToan` varchar(30) NOT NULL DEFAULT 'ChoThanhToan'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `datve_thanhtoan`
--

INSERT INTO `datve_thanhtoan` (`MaDatVe`, `MaKhachHang`, `ThoiGianDat`, `TongTien`, `PosterURL`, `TrangThaiThanhToan`) VALUES
('DV202511160277', 11, '2025-11-17 03:26:48', 100000.00, 'wicked.jpg', 'DaThanhToan'),
('DV202511145357', 11, '2025-11-10 10:00:00', 50000.00, NULL, 'ChoThanhToan'),
('DV202511146720', 11, '2025-11-10 10:00:00', 100000.00, NULL, 'ChoThanhToan'),
('DV202511142657', 11, '2025-11-15 03:29:13', 100000.00, 'https://image.tmdb.org/t/p/original/wIwM0QhjS6BuoB5UrHykZWXOUh6.jpg', 'DaThanhToan'),
('DV202511142032', 11, '2025-11-15 03:33:19', 100000.00, 'https://image.tmdb.org/t/p/original/wIwM0QhjS6BuoB5UrHykZWXOUh6.jpg', 'DaThanhToan'),
('DV202511142098', 11, '2025-11-15 03:38:25', 160000.00, 'https://image.tmdb.org/t/p/original/wIwM0QhjS6BuoB5UrHykZWXOUh6.jpg', 'DaThanhToan'),
('DV202511159353', 11, '2025-11-15 14:26:45', 50000.00, 'gladiator_2.jpg', 'DaThanhToan'),
('DV202511156125', 11, '2025-11-16 03:57:40', 130000.00, 'quiet_place_day_one.jpg', 'DaThanhToan'),
('DV202511159820', 11, '2025-11-16 04:06:13', 100000.00, 'moana_2.jpg', 'DaThanhToan'),
('GH202511159562', 11, '2025-11-16 04:13:08', 100000.00, 'quiet_place_day_one.jpg', 'ChuaThanhToan'),
('DV202511161889', 11, '2025-11-17 03:08:03', 300000.00, 'quiet_place_day_one.jpg', 'DaThanhToan'),
('DV202511165785', 13, '2025-11-17 03:32:31', 50000.00, 'wicked.jpg', 'DaThanhToan'),
('DV202511166866', 14, '2025-11-17 03:37:04', 160000.00, 'wicked.jpg', 'DaThanhToan'),
('DV202511162807', 11, '2025-11-17 03:53:47', 100000.00, 'quiet_place_day_one.jpg', 'DaThanhToan'),
('DV202511177585', 15, '2025-11-17 14:14:34', 100000.00, 'https://mlpnk72yciwc.i.optimole.com/cqhiHLc.IIZS~2ef73/w:auto/h:auto/q:75/https://bleedingcool.com/wp-content/uploads/2024/05/WKD_Adv1Sheet12_1080x1350_3.jpg', 'DaThanhToan'),
('DV202511177452', 16, '2025-11-17 14:24:18', 80000.00, 'https://static1.srcdn.com/wordpress/wp-content/uploads/2024/09/image003.jpg', 'DaThanhToan'),
('DV202511178963', 11, '2025-11-17 23:53:02', 80000.00, 'https://static1.srcdn.com/wordpress/wp-content/uploads/2024/01/dune-part-2-poster-showing-timothee-chalamet-as-paul-atreides-and-zendaya-as-chani-holding-daggers.jpeg', 'DaThanhToan'),
('DV202511246343', 11, '2025-11-24 18:32:24', 250000.00, 'https://static1.srcdn.com/wordpress/wp-content/uploads/2024/08/beetlejuice-beetlejuice-film-poster.jpg', 'DaThanhToan'),
('DV202511245044', 11, '2025-11-24 18:33:08', 100000.00, 'https://th.bing.com/th/id/R.5e02375020528d3a7b362b53457c116f?rik=xxH%2f9YAZpwF3cw&riu=http%3a%2f%2fwww.impawards.com%2f2024%2fposters%2finside_out_two_ver2.jpg&ehk=%2bYZ9tqL%2f6opGtODq3F1daepQP%2bihuxu95997T1oAy3Q%3d&risl=&pid=ImgRaw&r=0', 'DaThanhToan'),
('DV202511249293', 11, '2025-11-24 18:34:51', 100000.00, 'https://tse1.mm.bing.net/th/id/OIP.uOLmfehgBN56eGwYWQ-z6QHaLG?rs=1&pid=ImgDetMain&o=7&rm=3', 'DaThanhToan'),
('DV202511240761', 11, '2025-11-24 19:23:19', 240000.00, 'https://image.tmdb.org/t/p/original/wIwM0QhjS6BuoB5UrHykZWXOUh6.jpg', 'DaThanhToan');

-- --------------------------------------------------------

--
-- Table structure for table `ghe`
--

CREATE TABLE `ghe` (
  `MaGhe` int(11) NOT NULL,
  `LoaiGhe` varchar(20) DEFAULT 'Thuong',
  `GiaVe` decimal(10,2) DEFAULT 50.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ghe`
--

INSERT INTO `ghe` (`MaGhe`, `LoaiGhe`, `GiaVe`) VALUES
(1, 'Thuong', 50000.00),
(2, 'VIP', 80000.00),
(3, 'Doi', 100000.00);

-- --------------------------------------------------------

--
-- Table structure for table `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKhachHang` int(11) NOT NULL,
  `HoVaTen` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `NgayTao` datetime DEFAULT current_timestamp(),
  `HoatDong` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `khachhang`
--

INSERT INTO `khachhang` (`MaKhachHang`, `HoVaTen`, `Email`, `MatKhau`, `SoDienThoai`, `NgayTao`, `HoatDong`) VALUES
(1, 'Nguyễn Thị Hương', 'huong.nguyen@gmail.com', '4297f44b13955235245b2497399d7a93', '0901234567', '2025-11-10 08:20:19', 1),
(2, 'Trần Văn Kiên', 'kien.tran@yahoo.com', '0f5c92b138321e66a321008390780b5f', '0912345678', '2025-11-10 08:20:19', 1),
(3, 'Lê Thị Lan', 'lan.le@outlook.com', '83e7f14c840c228b7ee8e8e12e5f4a3f', '0923456789', '2025-11-10 08:20:19', 1),
(4, 'Phạm Văn Minh', 'minh.pham@gmail.com', 'bae4f653d42325472058f92bfa1755a1', '0934567890', '2025-11-10 08:20:19', 1),
(5, 'Vũ Thị Nga', 'nga.vu@yahoo.com', '96e02e7d4524bfe6cf23f52a0063edee', '0945678901', '2025-11-10 08:20:19', 1),
(6, 'Đặng Văn Phú', 'phu.dang@gmail.com', '2bcab28027f9968403d3b41c75e39c7a', '0956789012', '2025-11-10 08:20:19', 1),
(7, 'Bùi Thị Quỳnh', 'quynh.bui@outlook.com', 'd1f4d61e7bd3f0e1d8e5f0e9c2f8c0d1', '0967890123', '2025-11-10 08:20:19', 1),
(8, 'Đinh Văn Sơn', 'son.dinh@gmail.com', '0a5b4c2c8c3e1d4f5e6b7c8d9e0f1a2b', '0978901234', '2025-11-10 08:20:19', 1),
(9, 'Ngô Thị Tâm', 'tam.ngo@yahoo.com', '03c7c0ace395d80182db07ae2c30f034', '0989012345', '2025-11-10 08:20:19', 1),
(10, 'Trương Văn Uy', 'uy.truong@gmail.com', '7a3f8f4c5d1e2b3c4d5e6f7a8b9c0d1e', '0990123456', '2025-11-10 08:20:19', 1),
(11, 'Phạm Nguyên Y', 'nguyeny@gmail.com', '$2y$10$xeah4p77wQ7A4D9uxEzLhev3ZLSCcYzLydIz1YCmi.fP9G25CLTW.', '0988383373', '2025-11-11 13:44:53', 1),
(12, 'Phan Gia Bảo', 'bao123@gmail.com', '$2y$10$zIm9IP8y622p6cUIVsHv0uosyqY2gUc9DzOmo4Xu0TAZz1kKlq8GO', '098123456', '2025-11-16 02:23:41', 1),
(13, 'Lê', 'le@gmail.com', '$2y$10$TeIqX.mcV3Lz5BOisgysruucQwHgeMJpnj6sbsqizCq7gxOouQi2u', '0974774747', '2025-11-17 03:32:31', 1),
(14, 'Loc', 'loc@gmail.com', '$2y$10$93QQBxMgGB6KGykVpiRaB.70YTF1RAKWK9EqFuTO6y5aiyLg6zlY2', '09738723', '2025-11-17 03:37:04', 1),
(15, 'LI JOng ben', 'jong@gmail.com', '$2y$10$lJ2jUEd4fwd5ddFZ4vgSH.NcCV5.ZyLXdtaZdxxVGukxDrR6C6zZG', '0935353355', '2025-11-17 14:14:34', 1),
(16, 'SoJun', 'Jun@gmail.com', '$2y$10$lY/jZJ5gIN.c3Cki13XKve4QmyG27sfIe0zNi.j8iShaZ.Ywqxf..', '087654321', '2025-11-17 14:24:18', 1),
(17, 'LocSojun', 'locsojun@gmail.com', '$2y$10$nz9VRfb.CZ9Di6fdEqITo.v1QYuGm2FkEYxUlLFdpKIHOgPZimYPG', '0987654321', '2025-11-18 05:13:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `phim`
--

CREATE TABLE `phim` (
  `MaPhim` int(11) NOT NULL,
  `TenPhim` varchar(255) NOT NULL,
  `Mota` text DEFAULT NULL,
  `DaoDien` varchar(255) DEFAULT NULL,
  `DienVien` text DEFAULT NULL,
  `ThoiLuong` int(11) NOT NULL,
  `PosterURL` varchar(255) DEFAULT NULL,
  `TrailerURL` varchar(255) DEFAULT NULL,
  `NgayKhoiChieu` date DEFAULT NULL,
  `TrangThai` varchar(50) DEFAULT 'Đang chiếu'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `phim`
--

INSERT INTO `phim` (`MaPhim`, `TenPhim`, `Mota`, `DaoDien`, `DienVien`, `ThoiLuong`, `PosterURL`, `TrailerURL`, `NgayKhoiChieu`, `TrangThai`) VALUES
(1, 'Deadpool & Wolverine', 'Bộ đôi siêu anh hùng khó ưa nhất Marvel hợp tác trong một cuộc phiêu lưu điên rồ và đầy bạo lực', 'Shawn Levy', 'Ryan Reynolds, Hugh Jackman, Emma Corrin', 128, 'https://image.tmdb.org/t/p/original/wIwM0QhjS6BuoB5UrHykZWXOUh6.jpg', 'https://youtu.be/73_1biulkYk?si=nkMmNMwbDpJVjGVE', '2025-01-15', 'Đang chiếu'),
(2, 'Wicked', 'Câu chuyện về tình bạn giữa Elphaba và Glinda trước khi trở thành phù thủy xứ Oz', 'Jon M. Chu', '0', 160, 'https://mlpnk72yciwc.i.optimole.com/cqhiHLc.IIZS~2ef73/w:auto/h:auto/q:75/https://bleedingcool.com/wp-content/uploads/2024/05/WKD_Adv1Sheet12_1080x1350_3.jpg', 'https://youtu.be/XujPlTVYRW8?si=r_X0BpVMPCJ3H0tD', '2025-02-20', 'Đang chiếu'),
(3, 'A Quiet Place: Day One', 'Nguồn gốc của cuộc xâm lược ngoài hành tinh trong thế giới im lặng chết chóc', 'Michael Sarnoski', '0', 99, 'https://tse2.mm.bing.net/th/id/OIP.6W66p7OGb6ybkUtkqRWXMQHaK-?rs=1&pid=ImgDetMain&o=7&rm=3', 'https://youtu.be/YPY7J-flzE8?si=o7_6Dz1b4gfM_Uub', '2025-03-10', 'Đang chiếu'),
(4, 'Inside Out 2', 'Riley bước vào tuổi thiếu niên với những cảm xúc mới xuất hiện trong bộ não cô bé', 'Kelsey Mann', '0', 96, 'https://th.bing.com/th/id/R.5e02375020528d3a7b362b53457c116f?rik=xxH%2f9YAZpwF3cw&riu=http%3a%2f%2fwww.impawards.com%2f2024%2fposters%2finside_out_two_ver2.jpg&ehk=%2bYZ9tqL%2f6opGtODq3F1daepQP%2bihuxu95997T1oAy3Q%3d&risl=&pid=ImgRaw&r=0', 'https://youtu.be/VWavstJydZU?si=G10ikWlYsIOfLkEQ', '2025-04-05', 'Đang chiếu'),
(5, 'Dune: Part Three', 'Paul Atreides tiếp tục hành trình trở thành người dẫn dắt vũ trụ trong phần cuối của trilogy', 'Denis Villeneuve', '0', 155, 'https://static1.srcdn.com/wordpress/wp-content/uploads/2024/01/dune-part-2-poster-showing-timothee-chalamet-as-paul-atreides-and-zendaya-as-chani-holding-daggers.jpeg', 'https://youtu.be/F2_JoWuH7zc?si=J3A43kOkxEL7Jl4O', '2025-05-18', 'Đang chiếu'),
(6, 'Beetlejuice Beetlejuice', 'Sự trở lại của linh hồn ma quái sau nhiều thập kỷ với những trò đùa kinh dị mới', 'Tim Burton', '0', 104, 'https://static1.srcdn.com/wordpress/wp-content/uploads/2024/08/beetlejuice-beetlejuice-film-poster.jpg', 'https://youtu.be/e6yDanmWI1E?si=Sz7gs8KFthh65WDO', '2025-06-22', 'Đang chiếu'),
(7, 'Joker: Folie à Deux', 'Arthur Fleck gặp gỡ Harley Quinn trong bệnh viện tâm thần Arkham, tình yêu điên loạn bắt đầu', 'Todd Phillips', '0', 138, 'https://mir-s3-cdn-cf.behance.net/project_modules/1400/ccef8e169264035.64497da7c75b3.jpg', 'https://youtu.be/_OKAwz2MsJs?si=zK7IptHbSCMXqa7r', '2025-07-14', 'Đang chiếu'),
(8, 'Gladiator II', 'Con trai của Maximus trở lại đấu trường La Mã để trả thù và giành lại vinh quang', 'Ridley Scott', '0', 148, 'https://static1.srcdn.com/wordpress/wp-content/uploads/2024/09/image003.jpg', 'https://youtu.be/4rgYUipGJNo?si=EOKpLEvrfPYvomRN', '2025-08-30', 'Đang chiếu'),
(9, 'Moana 2', 'Moana khám phá những vùng biển xa xôi với sứ mệnh mới từ tổ tiên', 'David G. Derrick Jr.', '0', 100, 'https://tse1.mm.bing.net/th/id/OIP.uOLmfehgBN56eGwYWQ-z6QHaLG?rs=1&pid=ImgDetMain&o=7&rm=3', 'https://youtu.be/hDZ7y8RP5HE?si=lCD1Gunq9OY7QrDk', '2025-09-25', 'Đang chiếu'),
(10, 'Mufasa: The Lion King', 'Câu chuyện nguồn gốc về Mufasa và hành trình trở thành vua sư tử vĩ đại', 'Barry Jenkins', '0', 118, 'https://www.scifinow.co.uk/wp-content/uploads/2024/08/Mufasa.jpg', 'https://youtu.be/o17MF9vnabg?si=5P8MH814LeznwiFc', '2025-10-20', 'Đang chiếu'),
(11, 'TRUY TÌM LONG DIÊN HƯƠNG', 'đi tìm long diên hương', 'Dương Minh Chiến', '0', 103, 'https://metiz.vn/media/poster_film/long_dien_huong.jpg', 'https://youtu.be/Q0KQRoq24Ls?si=ppHlR_K9dz9D2dk-', '2025-11-14', 'Đang chiếu');

-- --------------------------------------------------------

--
-- Table structure for table `phim_theloai`
--

CREATE TABLE `phim_theloai` (
  `MaPhim` int(11) NOT NULL,
  `MaTL` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `phim_theloai`
--

INSERT INTO `phim_theloai` (`MaPhim`, `MaTL`) VALUES
(1, 1),
(1, 5),
(1, 6),
(2, 2),
(3, 1),
(3, 3),
(4, 5),
(4, 6),
(4, 7),
(5, 4),
(5, 7),
(6, 2),
(6, 5),
(6, 8),
(7, 4),
(7, 6),
(8, 1),
(8, 6),
(9, 3),
(9, 7),
(9, 8),
(10, 6),
(10, 7),
(11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `phongchieu`
--

CREATE TABLE `phongchieu` (
  `MaPhong` int(11) NOT NULL,
  `TenPhong` varchar(50) NOT NULL,
  `LoaiPhong` varchar(50) DEFAULT '2D',
  `SoLuongGhe` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `phongchieu`
--

INSERT INTO `phongchieu` (`MaPhong`, `TenPhong`, `LoaiPhong`, `SoLuongGhe`) VALUES
(1, 'Phòng 1', '2D', 25),
(2, 'Phòng 2', '3D', 25),
(3, 'Phòng 3', 'IMAX', 25),
(4, 'Phòng 4', '2D', 25),
(5, 'Phòng 5', '3D', 25),
(6, 'Phòng 6', '4DX', 25),
(7, 'Phòng 7', '2D', 25),
(8, 'Phòng 8', 'IMAX', 25),
(9, 'Phòng 9', '3D', 25),
(10, 'Phòng 10', '2D', 25);

-- --------------------------------------------------------

--
-- Table structure for table `suatchieu`
--

CREATE TABLE `suatchieu` (
  `MaSuatChieu` int(11) NOT NULL,
  `MaPhim` int(11) NOT NULL,
  `MaPhong` int(11) NOT NULL,
  `ThoiGianBatDau` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `suatchieu`
--

INSERT INTO `suatchieu` (`MaSuatChieu`, `MaPhim`, `MaPhong`, `ThoiGianBatDau`) VALUES
(1, 1, 1, '2025-11-10 10:00:00'),
(2, 1, 2, '2025-11-10 14:00:00'),
(3, 2, 3, '2025-11-10 18:00:00'),
(4, 3, 4, '2025-11-10 20:30:00'),
(5, 4, 5, '2025-11-11 09:00:00'),
(6, 5, 6, '2025-11-11 13:00:00'),
(7, 6, 7, '2025-11-11 16:30:00'),
(8, 7, 8, '2025-11-11 19:00:00'),
(9, 8, 9, '2025-11-12 10:30:00'),
(10, 9, 10, '2025-11-12 15:00:00'),
(11, 11, 3, '2025-11-18 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `theloai`
--

CREATE TABLE `theloai` (
  `MaTL` int(11) NOT NULL,
  `TenTL` varchar(100) NOT NULL,
  `ThuTu` int(11) DEFAULT 0,
  `AnHien` tinyint(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `theloai`
--

INSERT INTO `theloai` (`MaTL`, `TenTL`, `ThuTu`, `AnHien`) VALUES
(1, 'Hành Động', 1, 1),
(2, 'Kinh Dị', 2, 1),
(3, 'Hài Hước', 3, 1),
(4, 'Tình Cảm', 4, 1),
(5, 'Khoa Học Viễn Tưởng', 5, 1),
(6, 'Phiêu Lưu', 6, 1),
(7, 'Hoạt Hình', 7, 1),
(8, 'Tâm Lý', 8, 1),
(9, 'Chiến Tranh', 9, 1),
(10, 'Tài Liệu', 10, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`MaAdmin`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `chitietdv`
--
ALTER TABLE `chitietdv`
  ADD PRIMARY KEY (`MaChiTiet`),
  ADD UNIQUE KEY `idx_suat_ghe` (`MaSuatChieu`,`MaGhe`),
  ADD KEY `MaDatVe` (`MaDatVe`),
  ADD KEY `MaSuatChieu` (`MaSuatChieu`),
  ADD KEY `MaGhe` (`MaGhe`);

--
-- Indexes for table `datve_thanhtoan`
--
ALTER TABLE `datve_thanhtoan`
  ADD PRIMARY KEY (`MaDatVe`),
  ADD KEY `MaKhachHang` (`MaKhachHang`);

--
-- Indexes for table `ghe`
--
ALTER TABLE `ghe`
  ADD PRIMARY KEY (`MaGhe`);

--
-- Indexes for table `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKhachHang`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `phim`
--
ALTER TABLE `phim`
  ADD PRIMARY KEY (`MaPhim`);

--
-- Indexes for table `phim_theloai`
--
ALTER TABLE `phim_theloai`
  ADD PRIMARY KEY (`MaPhim`,`MaTL`),
  ADD KEY `MaPhim` (`MaPhim`),
  ADD KEY `MaTL` (`MaTL`);

--
-- Indexes for table `phongchieu`
--
ALTER TABLE `phongchieu`
  ADD PRIMARY KEY (`MaPhong`);

--
-- Indexes for table `suatchieu`
--
ALTER TABLE `suatchieu`
  ADD PRIMARY KEY (`MaSuatChieu`),
  ADD KEY `MaPhim` (`MaPhim`),
  ADD KEY `MaPhong` (`MaPhong`);

--
-- Indexes for table `theloai`
--
ALTER TABLE `theloai`
  ADD PRIMARY KEY (`MaTL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `MaAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chitietdv`
--
ALTER TABLE `chitietdv`
  MODIFY `MaChiTiet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `ghe`
--
ALTER TABLE `ghe`
  MODIFY `MaGhe` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKhachHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `phim`
--
ALTER TABLE `phim`
  MODIFY `MaPhim` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `phongchieu`
--
ALTER TABLE `phongchieu`
  MODIFY `MaPhong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `suatchieu`
--
ALTER TABLE `suatchieu`
  MODIFY `MaSuatChieu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `theloai`
--
ALTER TABLE `theloai`
  MODIFY `MaTL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
