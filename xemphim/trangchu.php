<?php
session_start();
require_once 'cauhinh.php';

$db = Database::getInstance()->getConnection();

$currentSlide = isset($_GET['slide']) ? (int)$_GET['slide'] : 0;

$queryFeatured = "SELECT p.*, GROUP_CONCAT(tl.TenTL SEPARATOR ', ') as TheLoai
                  FROM phim p
                  LEFT JOIN phim_theloai ptl ON p.MaPhim = ptl.MaPhim
                  LEFT JOIN theloai tl ON ptl.MaTL = tl.MaTL
                  WHERE p.TrangThai = 'Đang chiếu'
                  GROUP BY p.MaPhim
                  ORDER BY p.NgayKhoiChieu DESC
                  LIMIT 5";
$featuredMovies = $db->query($queryFeatured)->fetch_all(MYSQLI_ASSOC);
$totalSlides = count($featuredMovies);

if ($currentSlide < 0) $currentSlide = $totalSlides - 1;
if ($currentSlide >= $totalSlides) $currentSlide = 0;

$queryAllMovies = "SELECT p.*, GROUP_CONCAT(tl.TenTL SEPARATOR ', ') as TheLoai
                   FROM phim p
                   LEFT JOIN phim_theloai ptl ON p.MaPhim = ptl.MaPhim
                   LEFT JOIN theloai tl ON ptl.MaTL = tl.MaTL
                   GROUP BY p.MaPhim
                   ORDER BY p.NgayKhoiChieu DESC";
$allMovies = $db->query($queryAllMovies)->fetch_all(MYSQLI_ASSOC);
?>
<?php require_once 'header.php'; ?>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; text-align: center; border-bottom: 3px solid #28a745; font-size: 18px; font-weight: bold;">
            <?php echo h($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <div class="carousel-container">
        <div class="carousel-slides">
            <?php foreach ($featuredMovies as $index => $movie): ?>
                <div class="carousel-slide <?php echo $index === $currentSlide ? 'active' : ''; ?>" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('<?php echo h($movie['PosterURL']); ?>');">
                    <div class="carousel-content">
                        <h2><?php echo h($movie['TenPhim']); ?></h2>
                        <p class="carousel-genre"><?php echo h($movie['TheLoai'] ?: 'Đang cập nhật'); ?></p>
                        <p class="carousel-description"><?php echo h(substr($movie['Mota'], 0, 150)); ?>...</p>
                        <div class="carousel-info">
                            <span> <?php echo h($movie['ThoiLuong']); ?> phút</span>
                            <span> <?php echo date('d/m/Y', strtotime($movie['NgayKhoiChieu'])); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="carousel-dots">
            <?php foreach ($featuredMovies as $index => $movie): ?>
                <a href="?slide=<?php echo $index; ?>" class="dot <?php echo $index === $currentSlide ? 'active' : ''; ?>" style="cursor: pointer; text-decoration: none;"></a>
            <?php endforeach; ?>
        </div>
        
        <?php 
        $prevSlide = $currentSlide - 1;
        if ($prevSlide < 0) $prevSlide = $totalSlides - 1;
        $nextSlide = $currentSlide + 1;
        if ($nextSlide >= $totalSlides) $nextSlide = 0;
        ?>
        <a href="?slide=<?php echo $prevSlide; ?>" class="carousel-arrow prev" style="text-decoration: none;">❮</a>
        <a href="?slide=<?php echo $nextSlide; ?>" class="carousel-arrow next" style="text-decoration: none;">❯</a>
    </div>

    <div class="container">
        <h2 style="margin: 30px 0;">Tất cả phim</h2>
        <?php if (empty($allMovies)): ?>
            <p>Hiện chưa có phim nào.</p>
        <?php else: ?>
            <div class="movie-grid">
                <?php foreach ($allMovies as $movie): ?>
                    <div class="movie-card">
                        <img src="<?php echo h($movie['PosterURL'] ?: 'https://via.placeholder.com/250x350?text=Poster'); ?>"
                             alt="<?php echo h($movie['TenPhim']); ?>">
                        <div class="movie-card-content">
                            <h3><?php echo h($movie['TenPhim']); ?></h3>
                            <p><strong>Thể loại:</strong> <?php echo h($movie['TheLoai'] ?: 'Chưa phân loại'); ?></p>
                            <p><strong>Thời lượng:</strong> <?php echo h($movie['ThoiLuong']); ?> phút</p>
                            <p><?php echo h(substr($movie['Mota'], 0, 100)); ?>...</p>
                            <a href="chitietphim.php?id=<?php echo $movie['MaPhim']; ?>" class="btn">Xem chi tiết</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php require_once 'footer.php'; ?>
