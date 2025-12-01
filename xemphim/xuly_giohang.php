<?php
session_start();
require_once 'cauhinh.php';

$action = $_GET['action'] ?? '';

if($action == 'xoa') {
    $key = $_GET['key'] ?? '';
    if(isset($_SESSION['giohang'][$key])) {
        unset($_SESSION['giohang'][$key]);
    }
    header('Location: vedachon.php');
    exit;
}

if($action == 'them') {
    $maSuatChieu = $_POST['showtime'] ?? 0;
    $loaiGhe = $_POST['seattype'] ?? '';
    
    if($maSuatChieu && $loaiGhe) {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT MaGhe, GiaVe FROM ghe WHERE LoaiGhe = '$loaiGhe' LIMIT 1";
        $result = $db->query($sql)->fetch_assoc();
        $gia = $result ? $result['GiaVe'] : 50000;
        $maGhe = $result ? $result['MaGhe'] : 1;
        
    
        $key = $maSuatChieu . '_' . $loaiGhe;
        
        if(isset($_SESSION['giohang'][$key])) {
            $_SESSION['giohang'][$key]['soluong']++;
        } else {
            $_SESSION['giohang'][$key] = array(
                'masuatchieu' => $maSuatChieu,
                'loaighe' => $loaiGhe,
                'maghe' => $maGhe,
                'gia' => $gia,
                'soluong' => 1
            );
        }
    }
    header('Location: vedachon.php');
    exit;
}

header('Location: vedachon.php');
exit;
?>
