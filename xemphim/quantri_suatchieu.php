<?php
session_start();
require_once 'cauhinh.php';

requireAdmin();

$db = Database::getInstance()->getConnection();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $maPhim = $_POST['MaPhim'];
                $maPhong = $_POST['MaPhong'];
                $thoiGianBatDau = $_POST['ThoiGianBatDau'];
                
                $sql = "INSERT INTO suatchieu (MaPhim, MaPhong, ThoiGianBatDau) VALUES ('$maPhim', '$maPhong', '$thoiGianBatDau')";
                if ($db->query($sql)) {
                    $message = '<div class="alert alert-success">Thêm suất chiếu thành công!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Lỗi: ' . $db->error . '</div>';
                }
                break;
                
            case 'delete':
                $maSuatChieu = $_POST['MaSuatChieu'];
                $sql = "DELETE FROM suatchieu WHERE MaSuatChieu = '$maSuatChieu'";
                if ($db->query($sql)) {
                    $message = '<div class="alert alert-success">Xóa suất chiếu thành công!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Lỗi: Không thể xóa suất chiếu!</div>';
                }
                break;
                
            case 'update':
                $maSuatChieu = $_POST['MaSuatChieu'];
                $maPhim = $_POST['MaPhim'];
                $maPhong = $_POST['MaPhong'];
                $thoiGianBatDau = $_POST['ThoiGianBatDau'];
                $sql = "UPDATE suatchieu SET MaPhim = '$maPhim', MaPhong = '$maPhong', ThoiGianBatDau = '$thoiGianBatDau' WHERE MaSuatChieu = '$maSuatChieu'";
                if ($db->query($sql)) {
                    $message = '<div class="alert alert-success">Cập nhật suất chiếu thành công!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Lỗi: ' . $db->error . '</div>';
                }
                break;
        }
    }
}

$showtimesQuery = "SELECT s.*, p.TenPhim, pc.TenPhong 
                   FROM suatchieu s 
                   JOIN phim p ON s.MaPhim = p.MaPhim
                   JOIN phongchieu pc ON s.MaPhong = pc.MaPhong
                   ORDER BY s.ThoiGianBatDau DESC";
$showtimes = $db->query($showtimesQuery)->fetch_all(MYSQLI_ASSOC);

$movies = $db->query("SELECT MaPhim, TenPhim FROM phim ORDER BY TenPhim")->fetch_all(MYSQLI_ASSOC);

$rooms = $db->query("SELECT MaPhong, TenPhong, LoaiPhong FROM phongchieu ORDER BY TenPhong")->fetch_all(MYSQLI_ASSOC);
?>
<?php require_once 'header.php'; ?>

<link rel="stylesheet" href="quantri_suatchieu.css">

<div class="page-title">
    <div class="container">
        <h1>Quản Lý Suất Chiếu</h1>
    </div>
</div>

<div class="container">
    <div class="admin-links">
        <a href="quantri_trangchu.php">← Về trang chủ Admin</a>
    </div>

    <?php echo $message; ?>        
        <div style="margin-bottom: 20px;">
            <?php if (!isset($_GET['add']) && !isset($_GET['edit'])): ?>
                <a href="?add=1" class="btn" style="background: #dc3545; color: white; border: none; display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px;">+ Thêm Suất Chiếu Mới</a>
            <?php else: ?>
                <a href="quantri_suatchieu.php" class="btn" style="background: #6c757d; color: white; border: none; display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Hủy</a>
            <?php endif; ?>
        </div>

    
        <?php if (isset($_GET['add'])): ?>
        <div id="addShowtimeForm" class="form-section">
            <h2>Thêm Suất Chiếu Mới</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Phim:</label>
                        <select name="MaPhim" required>
                            <option value="">-- Chọn phim --</option>
                            <?php foreach ($movies as $movie): ?>
                                <option value="<?php echo $movie['MaPhim']; ?>">
                                    <?php echo h($movie['TenPhim']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phòng chiếu:</label>
                        <select name="MaPhong" required>
                            <option value="">-- Chọn phòng --</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room['MaPhong']; ?>">
                                    <?php echo h($room['TenPhong']); ?> (<?php echo h($room['LoaiPhong']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Thời gian bắt đầu:</label>
                        <input type="datetime-local" name="ThoiGianBatDau" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Thêm Suất Chiếu</button>
            </form>
        </div>
        <?php endif; ?>

    
        <?php 
        $editShowtime = null;
        if (isset($_GET['edit'])) {
            $editId = (int)$_GET['edit'];
            foreach ($showtimes as $showtime) {
                if ($showtime['MaSuatChieu'] == $editId) {
                    $editShowtime = $showtime;
                    break;
                }
            }
        }
        if ($editShowtime): 
        $dateTime = new DateTime($editShowtime['ThoiGianBatDau']);
        $formattedDateTime = $dateTime->format('Y-m-d\TH:i');
        ?>
        <div class="form-section">
            <h2>Sửa Suất Chiếu</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="MaSuatChieu" value="<?php echo $editShowtime['MaSuatChieu']; ?>">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Phim:</label>
                        <select name="MaPhim" required>
                            <option value="">-- Chọn phim --</option>
                            <?php foreach ($movies as $movie): ?>
                                <option value="<?php echo $movie['MaPhim']; ?>" <?php echo ($editShowtime['MaPhim'] == $movie['MaPhim']) ? 'selected' : ''; ?>>
                                    <?php echo h($movie['TenPhim']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Phòng chiếu:</label>
                        <select name="MaPhong" required>
                            <option value="">-- Chọn phòng --</option>
                            <?php foreach ($rooms as $room): ?>
                                <option value="<?php echo $room['MaPhong']; ?>" <?php echo ($editShowtime['MaPhong'] == $room['MaPhong']) ? 'selected' : ''; ?>>
                                    <?php echo h($room['TenPhong']); ?> (<?php echo h($room['LoaiPhong']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Thời gian bắt đầu:</label>
                        <input type="datetime-local" name="ThoiGianBatDau" value="<?php echo $formattedDateTime; ?>" required>
                    </div>
                </div>
                
                <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0; font-size: 14px; text-decoration: underline; margin-right: 15px;">Cập nhật</button>
            </form>
        </div>
        <?php endif; ?>
        <?php if (!isset($_GET['add']) && !isset($_GET['edit'])): ?>
        <div class="form-section">
            <h2>Danh Sách Suất Chiếu</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Phim</th>
                        <th>Phòng</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($showtimes as $showtime): ?>
                        <tr>
                            <td><?php echo $showtime['MaSuatChieu']; ?></td>
                            <td><?php echo h($showtime['TenPhim']); ?></td>
                            <td><?php echo h($showtime['TenPhong']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($showtime['ThoiGianBatDau'])); ?></td>
                            <td>
                                <a href="?edit=<?php echo $showtime['MaSuatChieu']; ?>" style="margin-right: 10px; text-decoration: underline; color: #dc3545;">Sửa</a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="MaSuatChieu" value="<?php echo $showtime['MaSuatChieu']; ?>">
                                    <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0; font-size: 14px; text-decoration: underline;" onclick="return confirm('Bạn có chắc muốn xóa suất chiếu này?');">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

<?php require_once 'footer.php'; ?>
