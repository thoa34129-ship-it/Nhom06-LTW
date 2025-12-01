<?php
session_start();
require_once 'cauhinh.php';


if(!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = array();
}

$db = Database::getInstance()->getConnection();

require_once 'header.php';
?>

<link rel="stylesheet" href="vedachon.css">

    <div class="container">
        <h2 class="cart-container">Giỏ Hàng</h2>
        
        <?php if (empty($_SESSION['giohang'])): ?>
            <div class="cart-empty">
                <p>Giỏ hàng trống.</p>
                <a href="theloai.php" class="btn">Đặt vé ngay</a>
            </div>
        <?php else: ?>
            <form method="POST" action="capnhat_giohang.php">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên Phim</th>
                            <th>Suất Chiếu</th>
                            <th>Loại Ghế</th>
                            <th class="text-center">Giá</th>
                            <th class="text-center">Số Lượng</th>
                            <th class="text-center">Thành Tiền</th>
                            <th class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $stt = 1;
                        $tongCong = 0;
                        foreach($_SESSION['giohang'] as $key => $item): 
                            $thanhTien = $item['gia'] * $item['soluong'];
                            $tongCong += $thanhTien;
                            
                    
                            $sql = $db->prepare("SELECT p.TenPhim, s.ThoiGianBatDau, pc.TenPhong 
                                                  FROM suatchieu s 
                                                  JOIN phim p ON s.MaPhim = p.MaPhim
                                                  JOIN phongchieu pc ON s.MaPhong = pc.MaPhong
                                                  WHERE s.MaSuatChieu = ?");
                            $sql->bind_param('i', $item['masuatchieu']);
                            $sql->execute();
                            $result = $sql->get_result()->fetch_assoc();
                        ?>
                        <tr>
                            <td><?php echo $stt++; ?></td>
                            <td><?php echo h($result['TenPhim']); ?></td>
                            <td class="cart-showtime-info">
                                <?php echo date('d/m/Y H:i', strtotime($result['ThoiGianBatDau'])); ?><br>
                                <small>Phòng: <?php echo h($result['TenPhong']); ?></small>
                            </td>
                            <td>
                                <?php 
                                if($item['loaighe'] == 'Thuong') echo 'Ghế Thường';
                                elseif($item['loaighe'] == 'VIP') echo 'Ghế VIP';
                                elseif($item['loaighe'] == 'Doi') echo 'Ghế Đôi';
                                ?>
                            </td>
                            <td class="text-center"><?php echo number_format($item['gia'], 0, ',', '.'); ?> VNĐ</td>
                            <td class="text-center">
                                <input type="number" name="soluong[<?php echo $key; ?>]" value="<?php echo $item['soluong']; ?>" 
                                       min="1" max="10" class="cart-quantity-input">
                            </td>
                            <td class="text-center font-bold"><?php echo number_format($thanhTien, 0, ',', '.'); ?> VNĐ</td>
                            <td class="text-center">
                                <a href="xuly_giohang.php?action=xoa&key=<?php echo $key; ?>" 
                                   class="cart-delete-link"
                                   onclick="return confirm('Bạn có chắc muốn xóa vé này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="cart-total-row">
                            <td colspan="6" class="cart-total-label">
                                Tổng Cộng:
                            </td>
                            <td colspan="2" class="cart-total-amount">
                                <?php echo number_format($tongCong, 0, ',', '.'); ?> VNĐ
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="cart-actions">
                    <button type="submit" class="btn">Cập Nhật</button>
                </div>
            </form>
            
       
            <?php if (!isset($_SESSION['user_id'])): ?>
            <div class="order-info-form">
                <h3>Thông tin đơn hàng</h3>
                <form method="POST" action="thanhtoan.php">
                    <table class="order-info-table">
                        <tr>
                            <td style="width: 150px;">
                                <label>Họ Tên: <span class="required">*</span></label>
                            </td>
                            <td>
                                <input type="text" name="HoVaTen" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Số ĐT: <span class="required">*</span></label>
                            </td>
                            <td>
                                <input type="tel" name="SoDienThoai" required>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Email: <span class="required">*</span></label>
                            </td>
                            <td>
                                <input type="email" name="Email" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="order-submit">
                                <button type="submit" class="btn">Đặt Vé</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <?php else: ?>
            <div class="checkout-form">
                <form method="POST" action="thanhtoan.php">
                    <button type="submit" class="btn">Đặt Vé</button>
                </form>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<?php require_once 'footer.php'; ?>
