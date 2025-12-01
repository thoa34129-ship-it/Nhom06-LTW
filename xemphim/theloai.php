<?php
session_start();
require_once 'cauhinh.php';

$db = Database::getInstance()->getConnection();

$selectedGenreId = isset($_GET['genre']) ? (int)$_GET['genre'] : 0;

$genresQuery = "SELECT tl.*, COUNT(DISTINCT p.MaPhim) as SoPhim
                FROM theloai tl
                LEFT JOIN phim_theloai ptl ON tl.MaTL = ptl.MaTL
                LEFT JOIN phim p ON ptl.MaPhim = p.MaPhim AND p.TrangThai = 'Đang chiếu'
                WHERE tl.AnHien = 1
                GROUP BY tl.MaTL
                ORDER BY tl.ThuTu, tl.TenTL";
$genres = $db->query($genresQuery)->fetch_all(MYSQLI_ASSOC);

$movies = [];
$selectedGenreName = '';
if ($selectedGenreId > 0) {
  
    foreach ($genres as $genre) {
        if ($genre['MaTL'] == $selectedGenreId) {
            $selectedGenreName = $genre['TenTL'];
            break;
        }
    }
    

    $query = "SELECT p.*, GROUP_CONCAT(tl.TenTL SEPARATOR ', ') as TheLoai 
              FROM phim p 
              INNER JOIN phim_theloai ptl ON p.MaPhim = ptl.MaPhim 
              LEFT JOIN theloai tl ON ptl.MaTL = tl.MaTL 
              WHERE p.TrangThai = 'Đang chiếu' AND ptl.MaTL = '$selectedGenreId' 
              GROUP BY p.MaPhim 
              ORDER BY p.NgayKhoiChieu DESC";
    $movies = $db->query($query)->fetch_all(MYSQLI_ASSOC);
}

require_once 'header.php';
?>

<style>
#container {
    display: flex;
    gap: 20px;
    margin: 20px 0;
}

#left {
    width: 250px;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
}

#left h3 {
    margin: 0 0 15px 0;
    font-size: 1.2em;
    color: #2c3e50;
}

.genre-item {
    padding: 10px;
    margin-bottom: 8px;
    cursor: pointer;
    background: #fff;
    border: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
}

.genre-item:hover {
    background: #e9ecef;
}

.genre-item.active {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.genre-count {
    font-size: 0.9em;
    color: #666;
}

.genre-item.active .genre-count {
    color: white;
}

#right {
    flex: 1;
    min-height: 400px;
}

#right h2 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.loading-spinner {
    text-align: center;
    padding: 50px;
    color: #666;
}

.empty-state {
    text-align: center;
    padding: 50px;
    color: #666;
}

@media (max-width: 768px) {
    #container {
        flex-direction: column;
    }
    
    #left {
        width: 100%;
    }
}
</style>

    <div class="container">
        <h2>Thể loại phim</h2>
        
        <div id="container">
            <div id="left">
                <h3>Danh sách</h3>
                <?php foreach ($genres as $genre): ?>
                    <a href="?genre=<?php echo $genre['MaTL']; ?>" class="genre-item <?php echo $genre['MaTL'] == $selectedGenreId ? 'active' : ''; ?>" style="text-decoration: none; color: inherit; display: flex; justify-content: space-between;">
                        <span><?php echo h($genre['TenTL']); ?></span>
                        <span class="genre-count"><?php echo $genre['SoPhim']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <div id="right">
                <?php if ($selectedGenreId > 0): ?>
                    <h2><?php echo h($selectedGenreName); ?></h2>
                    <?php if (empty($movies)): ?>
                        <div class="empty-state">
                            <p>Không có phim nào trong thể loại này.</p>
                        </div>
                    <?php else: ?>
                        <div class="movie-grid">
                            <?php foreach ($movies as $movie): ?>
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
                <?php else: ?>
                    <div class="empty-state">
                        <p>Chọn thể loại để xem phim</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>
