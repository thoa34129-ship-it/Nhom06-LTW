<?php
session_start();
require_once 'cauhinh.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['soluong'])) {
    foreach($_POST['soluong'] as $key => $soLuong) {
        if(isset($_SESSION['giohang'][$key])) {
            $_SESSION['giohang'][$key]['soluong'] = max(1, intval($soLuong));
        }
    }
    $_SESSION['success_message'] = 'Đã cập nhật giỏ hàng';
}

header('Location: vedachon.php');
exit;
?>
