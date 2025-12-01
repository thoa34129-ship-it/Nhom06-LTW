<?php
session_start();
require_once 'cauhinh.php';

$db = Database::getInstance()->getConnection();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $query = "SELECT dv.*, p.TenPhim, s.ThoiGianBatDau, pc.TenPhong, 
              GROUP_CONCAT(DISTINCT CONCAT(
                  CASE 
                      WHEN ctdv.GiaVe = 50000 THEN 'Ghế Thường'
                      WHEN ctdv.GiaVe = 80000 THEN 'Ghế VIP'
                      WHEN ctdv.GiaVe = 100000 THEN 'Ghế Đôi'
                      ELSE 'Ghế Thường'
                  END
              ) SEPARATOR ', ') as LoaiGhe,
              SUM(ctdv.SoLuong) as SoLuong
              FROM datve_thanhtoan dv
              INNER JOIN chitietdv ctdv ON dv.MaDatVe = ctdv.MaDatVe
              INNER JOIN suatchieu s ON ctdv.MaSuatChieu = s.MaSuatChieu
              INNER JOIN phim p ON s.MaPhim = p.MaPhim
              INNER JOIN phongchieu pc ON s.MaPhong = pc.MaPhong
              WHERE dv.MaKhachHang = '$userId' 
              AND dv.TrangThaiThanhToan = 'DaThanhToan'  
              GROUP BY dv.MaDatVe
              ORDER BY dv.ThoiGianDat DESC";
    $bookings = $db->query($query)->fetch_all(MYSQLI_ASSOC);

} elseif (isset($_SESSION['last_booking_id'])) {
    $bookingId = $_SESSION['last_booking_id'];
    $query = "SELECT dv.*, p.TenPhim, s.ThoiGianBatDau, pc.TenPhong, kh.HoVaTen, kh.Email, kh.SoDienThoai,
              GROUP_CONCAT(DISTINCT CONCAT(
                  CASE 
                      WHEN ctdv.GiaVe = 50000 THEN 'Ghế Thường'
                      WHEN ctdv.GiaVe = 80000 THEN 'Ghế VIP'
                      WHEN ctdv.GiaVe = 100000 THEN 'Ghế Đôi'
                      ELSE 'Ghế Thường'
                  END
              ) SEPARATOR ', ') as LoaiGhe,
              SUM(ctdv.SoLuong) as SoLuong
              FROM datve_thanhtoan dv
              INNER JOIN khachhang kh ON dv.MaKhachHang = kh.MaKhachHang
              INNER JOIN chitietdv ctdv ON dv.MaDatVe = ctdv.MaDatVe
              INNER JOIN suatchieu s ON ctdv.MaSuatChieu = s.MaSuatChieu
              INNER JOIN phim p ON s.MaPhim = p.MaPhim
              INNER JOIN phongchieu pc ON s.MaPhong = pc.MaPhong
              WHERE dv.MaDatVe = '$bookingId' 
              AND dv.TrangThaiThanhToan = 'DaThanhToan'
              GROUP BY dv.MaDatVe";
    $bookings = $db->query($query)->fetch_all(MYSQLI_ASSOC);
} else {
    $bookings = array();
}

require_once 'header.php';
?>

    <div class="container">
        <h2 style="margin: 30px 0;">Lịch Sử Đặt Vé</h2>
        
        <?php if (!empty($_SESSION['success_message'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($bookings)): ?>
            <div style="background: #fff; padding: 30px; border-radius: 10px; text-align: center;">
                <p style="font-size: 18px; color: #666;">Bạn chưa có vé đã thanh toán nào.</p>
                <a href="theloai.php" class="btn" style="margin-top: 20px; display: inline-block;">Đặt vé ngay</a>
            </div>
        <?php else: ?>
            <div style="display: grid; gap: 20px;">
                <?php foreach ($bookings as $booking): ?>
                    <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: grid; grid-template-columns: 150px 1fr; gap: 20px;">
                        <div>
                            <img src="<?php echo htmlspecialchars($booking['PosterURL'] ?: 'https://via.placeholder.com/150x225?text=Poster'); ?>" 
                                 alt="<?php echo htmlspecialchars($booking['TenPhim']); ?>" 
                                 style="width: 100%; border-radius: 5px;">
                        </div>
                        <div>
                            <h3 style="margin-bottom: 10px;"><?php echo htmlspecialchars($booking['TenPhim']); ?></h3>
                            <p><strong>Mã đặt vé:</strong> <?php echo htmlspecialchars($booking['MaDatVe']); ?></p>
                            
                            <?php if (!isset($_SESSION['user_id']) && isset($booking['HoVaTen'])): ?>
                                <p><strong>Tên khách hàng:</strong> <?php echo htmlspecialchars($booking['HoVaTen']); ?></p>
                                <hr style="margin: 15px 0; border: none; border-top: 1px solid #eee;">
                            <?php endif; ?>
                            
                            <p><strong>Suất chiếu:</strong> <?php echo date('d/m/Y H:i', strtotime($booking['ThoiGianBatDau'])); ?></p>
                            <p><strong>Phòng:</strong> <?php echo htmlspecialchars($booking['TenPhong']); ?></p>
                            
                            <?php if (!empty($booking['LoaiGhe'])): ?>
                                <p><strong>Loại ghế:</strong> <?php echo htmlspecialchars($booking['LoaiGhe']); ?></p>
                            <?php endif; ?>
                            
                            <p><strong>Số lượng:</strong> <?php echo htmlspecialchars($booking['SoLuong']); ?> vé</p>
                            
                            <p><strong>Tổng tiền:</strong> <span style="color: #16d8ff; font-size: 20px; font-weight: bold;"><?php echo number_format($booking['TongTien'], 0, ',', '.'); ?> VNĐ</span></p>
                            
                            <p><strong>Trạng thái:</strong> 
                                <span style="color: #27ae60; font-weight: bold;"> Đã thanh toán</span>
                            </p>
                            
                            <p style="color: #999; font-size: 14px; margin-top: 10px;">Đặt lúc: <?php echo date('d/m/Y H:i:s', strtotime($booking['ThoiGianDat'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php require_once 'footer.php'; ?>