<?php
session_start();
require_once 'cauhinh.php';
requireAdmin();

$db = Database::getInstance()->getConnection();

$query = "SELECT dv.*, kh.HoVaTen, kh.Email, p.TenPhim, s.ThoiGianBatDau, pc.TenPhong,
          COUNT(ctdv.MaChiTiet) as SoLuongVe
          FROM datve_thanhtoan dv
          INNER JOIN khachhang kh ON dv.MaKhachHang = kh.MaKhachHang
          INNER JOIN chitietdv ctdv ON dv.MaDatVe = ctdv.MaDatVe
          INNER JOIN suatchieu s ON ctdv.MaSuatChieu = s.MaSuatChieu
          INNER JOIN phim p ON s.MaPhim = p.MaPhim
          INNER JOIN phongchieu pc ON s.MaPhong = pc.MaPhong
          GROUP BY dv.MaDatVe
          ORDER BY dv.ThoiGianDat DESC";
$bookings = $db->query($query)->fetch_all(MYSQLI_ASSOC);
?>
<?php require_once 'header.php'; ?>

<link rel="stylesheet" href="quantri_giohang.css">

<div class="page-title">
    <div class="container">
        <h1>Xem Giỏ Hàng</h1>
    </div>
</div>

<div class="container">
    <div class="admin-links">
        <a href="quantri_trangchu.php">← Về trang chủ Admin</a>
    </div>
    
    <p class="stats-info">Tổng số đơn hàng: <strong><?php echo count($bookings); ?></strong></p>
            
            <table>
                <thead>
                    <tr>
                        <th>Mã Đặt Vé</th>
                        <th>Khách Hàng</th>
                        <th>Email</th>
                        <th>Phim</th>
                        <th>Suất Chiếu</th>
                        <th>Phòng</th>
                        <th>Số Vé</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Thời Gian Đặt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 30px; color: #999;">
                                Chưa có đơn đặt vé nào
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><strong><?php echo h($booking['MaDatVe']); ?></strong></td>
                            <td><?php echo h($booking['HoVaTen']); ?></td>
                            <td><?php echo h($booking['Email']); ?></td>
                            <td><?php echo h($booking['TenPhim']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($booking['ThoiGianBatDau'])); ?></td>
                            <td><?php echo h($booking['TenPhong']); ?></td>
                            <td style="text-align: center;"><?php echo $booking['SoLuongVe']; ?></td>
                            <td><strong><?php echo number_format($booking['TongTien'], 0, ',', '.'); ?> VNĐ</strong></td>
                            <td>
                                <?php if ($booking['TrangThaiThanhToan'] == 'DaThanhToan'): ?>
                                    <span class="status-paid"> Đã thanh toán</span>
                                <?php else: ?>
                                    <span class="status-pending"> Chờ thanh toán</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($booking['ThoiGianDat'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
</div>

<?php require_once 'footer.php'; ?>
