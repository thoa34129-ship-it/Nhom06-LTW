<?php
session_start();
require_once 'cauhinh.php';

$movieId = $_GET['id'] ?? 0;
$db = Database::getInstance()->getConnection();

    $sql = "SELECT * FROM phim WHERE MaPhim = '$movieId' AND TrangThai = 'Đang chiếu'";
    $result = $db->query($sql);
    $movie = $result ? $result->fetch_assoc() : null;

if (!$movie) {
    redirect('trangchu.php');
}
$showtimesQuery = "SELECT s.*, p.TenPhong, p.LoaiPhong 
                   FROM suatchieu s 
                   JOIN phongchieu p ON s.MaPhong = p.MaPhong 
                   WHERE s.MaPhim = '$movieId'
                   ORDER BY s.ThoiGianBatDau";
    $result = $db->query($showtimesQuery);
    $showtimes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<?php require_once 'header.php'; ?>

    <div class="container" style="margin-top: 30px;">
        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 40px;">
            <div>
                <img src="<?php echo h($movie['PosterURL'] ?: 'https://via.placeholder.com/300x450?text=Poster'); ?>" 
                     alt="<?php echo h($movie['TenPhim']); ?>" 
                     style="width: 100%; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                
                <?php if (!empty($movie['TrailerURL'])): ?>
                    <div style="margin-top: 20px;">
                        <?php 
                        $trailerUrl = $movie['TrailerURL'];
                        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $trailerUrl, $id)) {
                            $videoId = $id[1];
                        } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $trailerUrl, $id)) {
                            $videoId = $id[1];
                        } else {
                            $videoId = null;
                        }
                        
                        if ($videoId): ?>
                            <iframe width="100%" height="200" 
                                    src="https://www.youtube.com/embed/<?php echo $videoId; ?>" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    style="border-radius: 10px;">
                            </iframe>
                        <?php else: ?>
                            <a href="<?php echo h($trailerUrl); ?>" target="_blank" class="btn" style="display: block; text-align: center;">Xem Trailer</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div>
                <h2 style="color: #2c3e50; font-size: 2em; margin-bottom: 20px;"><?php echo h($movie['TenPhim']); ?></h2>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                    <p style="margin: 10px 0;"><strong>Thời lượng:</strong> <?php echo h($movie['ThoiLuong']); ?> phút</p>
                    <p style="margin: 10px 0;"><strong>Đạo diễn:</strong> <?php echo h($movie['DaoDien'] ?: 'Đang cập nhật'); ?></p>
                    <p style="margin: 10px 0;"><strong>Diễn viên:</strong> <?php echo h($movie['DienVien'] ?: 'Đang cập nhật'); ?></p>
                    <p style="margin: 10px 0;"><strong>Ngày khởi chiếu:</strong> <?php echo date('d/m/Y', strtotime($movie['NgayKhoiChieu'])); ?></p>
                    <p style="margin: 10px 0;"><strong>Mô tả:</strong> <?php echo nl2br(h($movie['Mota'])); ?></p>
                </div>

                <h3 style="margin-top: 30px; color: #2c3e50;">Suất chiếu</h3>
                
                <?php if (empty($showtimes)): ?>
                    <p style="text-align: center; padding: 40px; background: #fff; border-radius: 10px;">
                        Hiện chưa có suất chiếu nào.
                    </p>
                <?php else: ?>
                    <div style="display: grid; gap: 15px; margin-top: 20px;">
                        <?php foreach ($showtimes as $showtime): ?>
                            <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); border: 2px solid #e0e0e0;">
                                <p style="margin: 0 0 10px 0;"><strong>Thời gian:</strong> <?php echo date('d/m/Y H:i', strtotime($showtime['ThoiGianBatDau'])); ?></p>
                                <p style="margin: 0 0 15px 0;"><strong>Phòng:</strong> <?php echo h($showtime['TenPhong']); ?> (<?php echo h($showtime['LoaiPhong']); ?>)</p>
                                ''
                                <form method="POST" action="xuly_giohang.php?action=them" style="display: flex; gap: 10px;">
                                    <input type="hidden" name="showtime" value="<?php echo $showtime['MaSuatChieu']; ?>">
                                    
                                    <button type="submit" name="seattype" value="Thuong" 
                                       style="flex: 1; padding: 12px; background: #3498db; border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 0.9em;">
                                        <div>Ghế Thường</div>
                                        <div style="font-weight: bold; margin-top: 3px;">50.000 VNĐ</div>
                                    </button>
                                    
                                    <button type="submit" name="seattype" value="VIP"
                                       style="flex: 1; padding: 12px; background: #e74c3c; border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 0.9em;">
                                        <div>Ghế VIP</div>
                                        <div style="font-weight: bold; margin-top: 3px;">80.000 VNĐ</div>
                                    </button>
                                    
                                    <button type="submit" name="seattype" value="Doi"
                                       style="flex: 1; padding: 12px; background: #9b59b6; border: none; border-radius: 8px; color: white; cursor: pointer; font-size: 0.9em;">
                                        <div>Ghế Đôi</div>
                                        <div style="font-weight: bold; margin-top: 3px;">100.000 VNĐ</div>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>
