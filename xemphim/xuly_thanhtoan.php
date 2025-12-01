<?php
session_start();
require_once 'cauhinh.php';

$db = Database::getInstance()->getConnection();

if(empty($_SESSION['giohang'])) {
    $_SESSION['error_message'] = 'Giỏ hàng trống';
    header('Location: vedachon.php');
    exit;
}

if(isset($_SESSION['user_id'])) {

    $maKhachHang = $_SESSION['user_id'];
    $khachHangId = $maKhachHang;
} else {

    $hoVaTen = trim($_POST['HoVaTen'] ?? '');
    $diaChi = trim($_POST['DiaChi'] ?? '');
    $soDienThoai = trim($_POST['SoDienThoai'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    
    if(empty($hoVaTen) || empty($soDienThoai) || empty($email)) {
        $_SESSION['error_message'] = 'Vui lòng điền đầy đủ thông tin';
        header('Location: vedachon.php');
        exit;
    }
    

    $sql = $db->prepare("SELECT MaKhachHang FROM khachhang WHERE Email = ?");
    $sql = "SELECT MaKhachHang FROM khachhang WHERE Email = '$email'";
    $result = $db->query($sql);
    if($result && $result->num_rows > 0) {
        $khachHangId = $result->fetch_assoc()['MaKhachHang'];
    } else {
        $matKhau = password_hash(uniqid(), PASSWORD_DEFAULT);
        $sql = "INSERT INTO khachhang (HoVaTen, Email, SoDienThoai, MatKhau) VALUES ('$hoVaTen', '$email', '$soDienThoai', '$matKhau')";
        $db->query($sql);
        $khachHangId = $db->insert_id;
    }
}

$bookingId = 'DV' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

$tongTien = 0;
foreach($_SESSION['giohang'] as $item) {
    $tongTien += $item['gia'] * $item['soluong'];
}

$db->begin_transaction();

try {
    $firstItem = reset($_SESSION['giohang']);
    $sql = "SELECT p.PosterURL FROM suatchieu s 
              JOIN phim p ON s.MaPhim = p.MaPhim 
              WHERE s.MaSuatChieu = '{$firstItem['masuatchieu']}'";
    $movieInfo = $db->query($sql)->fetch_assoc();
    $posterURL = $movieInfo['PosterURL'] ?? '';
    

    $sql = "INSERT INTO datve_thanhtoan (MaDatVe, MaKhachHang, TongTien, PosterURL, TrangThaiThanhToan, ThoiGianDat) 
              VALUES ('$bookingId', '$khachHangId', '$tongTien', '$posterURL', 'DaThanhToan', NOW())";
    $db->query($sql);
    

   foreach($_SESSION['giohang'] as $item) {
        $maGhe = $item['maghe'] ?? null; 
        $soLuong = $item['soluong'];
        $giaVe = $item['gia'];
        $sql = "INSERT INTO chitietdv (MaDatVe, MaSuatChieu, MaGhe, GiaVe, SoLuong) 
                  VALUES ('$bookingId', '{$item['masuatchieu']}', '$maGhe', '$giaVe', '$soLuong')";
        if (!$db->query($sql)) {
            throw new Exception("Lỗi khi lưu chi tiết vé: " . $db->error);
        }
    }
    
    $db->commit();
    
    unset($_SESSION['giohang']);
    
    $_SESSION['last_booking_id'] = $bookingId;
    
    $_SESSION['success_message'] = 'Thanh toán thành công! Mã đặt vé: ' . $bookingId;
    header('Location: lichsu_datve.php');
    exit;
    
} catch (Exception $e) {
    $db->rollback();
    $_SESSION['error_message'] = 'Có lỗi xảy ra khi đặt vé. Vui lòng thử lại.';
    header('Location: vedachon.php');
    exit;
}
?>
