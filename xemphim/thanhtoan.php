<?php
session_start();
require_once 'cauhinh.php';

$db = Database::getInstance()->getConnection();

if(empty($_SESSION['giohang'])) {
    $_SESSION['error_message'] = 'Giỏ hàng trống';
    header('Location: vedachon.php');
    exit;
}

$tongTien = 0;
foreach($_SESSION['giohang'] as $item) {
    $tongTien += $item['gia'] * $item['soluong'];
}

require_once 'header.php';
?>

    <div class="container" style="margin-top: 30px; max-width: 900px;">
        <h2 style="margin-bottom: 30px; text-align: center;">Chi Tiết Đơn Hàng</h2>
        

        <div style="background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <table style="width: 100%; margin-bottom: 30px;">
                    <thead style="background: #f8f9fa; border-bottom: 2px solid #ddd;">
                        <tr>
                            <th style="padding: 15px; text-align: left;">Phim</th>
                            <th style="padding: 15px; text-align: center;">Số Lượng</th>
                            <th style="padding: 15px; text-align: right;">Giá</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($_SESSION['giohang'] as $item): 
                                $sql = "SELECT p.TenPhim, s.ThoiGianBatDau, pc.TenPhong 
                                    FROM suatchieu s 
                                    JOIN phim p ON s.MaPhim = p.MaPhim
                                    JOIN phongchieu pc ON s.MaPhong = pc.MaPhong
                                    WHERE s.MaSuatChieu = '{$item['masuatchieu']}'";
                                $info = $db->query($sql)->fetch_assoc();
                            
                            $thanhTien = $item['gia'] * $item['soluong'];
                        ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;">
                                <strong style="font-size: 1.1em;"><?php echo h($info['TenPhim']); ?></strong><br>
                                <small style="color: #666; line-height: 1.6;">
                                    <?php echo date('d/m/Y H:i', strtotime($info['ThoiGianBatDau'])); ?><br>
                                    Phòng: <?php echo h($info['TenPhong']); ?><br>
                                    <?php 
                                    if($item['loaighe'] == 'Thuong') echo 'Ghế Thường';
                                    elseif($item['loaighe'] == 'VIP') echo 'Ghế VIP';
                                    elseif($item['loaighe'] == 'Doi') echo 'Ghế Đôi';
                                    ?>
                                </small>
                            </td>
                            <td style="padding: 15px; text-align: center; font-size: 1.1em; font-weight: bold;"><?php echo $item['soluong']; ?></td>
                            <td style="padding: 15px; text-align: right; font-weight: bold; font-size: 1.1em;"><?php echo number_format($thanhTien, 0, ',', '.'); ?> VNĐ</td>
                        </tr>
                        <?php endforeach; ?>
                        <tr style="background: #f8f9fa; font-weight: bold;">
                            <td colspan="2" style="padding: 20px; text-align: right; font-size: 1.2em;">Tổng Cộng:</td>
                            <td style="padding: 20px; text-align: right; font-size: 1.4em; color: #16d8ff;"><?php echo number_format($tongTien, 0, ',', '.'); ?> VNĐ</td>
                        </tr>
                    </tbody>
                </table>
                
                <form method="POST" action="xuly_thanhtoan.php">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <input type="hidden" name="HoVaTen" value="<?php echo h($_POST['HoVaTen'] ?? ''); ?>">
                        <input type="hidden" name="DiaChi" value="<?php echo h($_POST['DiaChi'] ?? ''); ?>">
                        <input type="hidden" name="SoDienThoai" value="<?php echo h($_POST['SoDienThoai'] ?? ''); ?>">
                        <input type="hidden" name="Email" value="<?php echo h($_POST['Email'] ?? ''); ?>">
                    <?php endif; ?>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <a href="vedachon.php" class="btn" style="background: #95a5a6; padding: 15px 40px; text-decoration: none; display: inline-block; font-size: 1.1em;">Quay Lại</a>
                        <button type="submit" class="btn" style="background: #27ae60; padding: 15px 40px; font-size: 1.1em; cursor: pointer;">Xác Nhận Thanh Toán</button>
                    </div>
                </form>
            </div>
    </div>

<?php require_once 'footer.php'; ?>
