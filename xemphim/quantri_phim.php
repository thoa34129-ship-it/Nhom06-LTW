<?php
session_start();
require_once 'cauhinh.php';

if (!isset($_SESSION['admin_id'])) {
    redirect('dangnhap.php');
}

$db = Database::getInstance()->getConnection();
$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $tenPhim = trim($_POST['TenPhim']);
                $theLoaiIds = $_POST['TheLoai'] ?? [];
                $daoDien = trim($_POST['DaoDien']);
                $dienVien = trim($_POST['DienVien']);
                $thoiLuong = (int)$_POST['ThoiLuong'];
                $ngayKhoiChieu = $_POST['NgayKhoiChieu'];
                $moTa = trim($_POST['Mota'] ?? '');
                $posterURL = trim($_POST['PosterURL']);
                $trailerURL = trim($_POST['TrailerURL']);
                $trangThai = $_POST['TrangThai'];
                
                $db->begin_transaction();
                try {
                    $sql = "INSERT INTO phim (TenPhim, DaoDien, DienVien, ThoiLuong, NgayKhoiChieu, Mota, PosterURL, TrailerURL, TrangThai) VALUES ('$tenPhim', '$daoDien', '$dienVien', '$thoiLuong', '$ngayKhoiChieu', '$moTa', '$posterURL', '$trailerURL', '$trangThai')";
                    $db->query($sql);
                    $maPhim = $db->insert_id;
                    
                    if (!empty($theLoaiIds)) {
                        foreach ($theLoaiIds as $maTL) {
                            $sql = "INSERT INTO phim_theloai (MaPhim, MaTL) VALUES ('$maPhim', '$maTL')";
                            $db->query($sql);
                        }
                    }
                    
                    $db->commit();
                    $message = '<div class="alert alert-success">Thêm phim thành công!</div>';
                } catch (Exception $e) {
                    $db->rollback();
                    $message = '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
                }
                break;
                
            case 'delete':
                $maPhim = $_POST['MaPhim'];
                
                $db->begin_transaction();
                try {
                   
                    $sql = "DELETE FROM phim_theloai WHERE MaPhim = '$maPhim'";
                    $db->query($sql);
                    
                  
                    $sql = "DELETE FROM phim WHERE MaPhim = '$maPhim'";
                    $db->query($sql);
                    
                    $db->commit();
                    $message = '<div class="alert alert-success">Xóa phim thành công!</div>';
                } catch (Exception $e) {
                    $db->rollback();
                    $message = '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
                }
                break;
                
            case 'update':
                $maPhim = $_POST['MaPhim'];
                $tenPhim = trim($_POST['TenPhim']);
                $theLoaiIds = $_POST['TheLoai'] ?? [];
                $daoDien = trim($_POST['DaoDien']);
                $dienVien = trim($_POST['DienVien']);
                $thoiLuong = (int)$_POST['ThoiLuong'];
                $ngayKhoiChieu = $_POST['NgayKhoiChieu'];
                $moTa = trim($_POST['Mota'] ?? '');
                $posterURL = trim($_POST['PosterURL']);
                $trailerURL = trim($_POST['TrailerURL']);
                $trangThai = $_POST['TrangThai'];
                
                $db->begin_transaction();
                try {
                    $sql = "UPDATE phim SET TenPhim='$tenPhim', DaoDien='$daoDien', DienVien='$dienVien', ThoiLuong='$thoiLuong', NgayKhoiChieu='$ngayKhoiChieu', Mota='$moTa', PosterURL='$posterURL', TrailerURL='$trailerURL', TrangThai='$trangThai' WHERE MaPhim='$maPhim'";
                    $db->query($sql);
                    
                    $sql = "DELETE FROM phim_theloai WHERE MaPhim = '$maPhim'";
                    $db->query($sql);
                    
                
                    if (!empty($theLoaiIds)) {
                        foreach ($theLoaiIds as $maTL) {
                            $sql = "INSERT INTO phim_theloai (MaPhim, MaTL) VALUES ('$maPhim', '$maTL')";
                            $db->query($sql);
                        }
                    }
                    
                    $db->commit();
                    $message = '<div class="alert alert-success">Cập nhật phim thành công!</div>';
                } catch (Exception $e) {
                    $db->rollback();
                    $message = '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
                }
                break;
        }
    }
}

    $movies = $db->query("
        SELECT p.*, 
            GROUP_CONCAT(t.TenTL ORDER BY t.TenTL SEPARATOR ', ') as TheLoaiList
        FROM phim p
        LEFT JOIN phim_theloai pt ON p.MaPhim = pt.MaPhim
        LEFT JOIN theloai t ON pt.MaTL = t.MaTL
        GROUP BY p.MaPhim
        ORDER BY p.MaPhim DESC
    ")->fetch_all(MYSQLI_ASSOC);

    $theloai = $db->query("SELECT * FROM theloai WHERE AnHien = 1 ORDER BY ThuTu")->fetch_all(MYSQLI_ASSOC);
?>
<?php require_once 'header.php'; ?>
<link rel="stylesheet" href="quantri_phim.css">
<div class="page-title">
    <div class="container">
        <h2>Quản Lý Phim</h2>
    </div>
</div>

<div class="container">
    <div class="admin-links">
        <a href="quantri_trangchu.php">← Về trang chủ Admin</a>
    </div>

    <?php echo $message; ?>

        <div style="margin-bottom: 20px;">
            <?php if (!isset($_GET['add']) && !isset($_GET['edit'])): ?>
                <a href="?add=1" class="btn" style="background: #dc3545; color: white; border: none; display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px;">+ Thêm Phim Mới</a>
            <?php else: ?>
                <a href="quantri_phim.php" class="btn" style="background: #6c757d; color: white; border: none; display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Hủy</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['add'])): ?>
        <div id="addMovieForm" class="form-section">
            <h2>Thêm Phim Mới</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-row">
                    <div class="form-group">
                        <label>Tên phim: *</label>
                        <input type="text" name="TenPhim" required>
                    </div>
                    <div class="form-group">
                        <label>Thời lượng (phút): *</label>
                        <input type="number" name="ThoiLuong" min="1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thể loại: * (Chọn ít nhất 1)</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 15px; background: #f8f9fa; border-radius: 5px; border: 1px solid #ddd;">
                        <?php foreach($theloai as $tl): ?>
                            <label style="display: flex; align-items: center; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="TheLoai[]" value="<?php echo $tl['MaTL']; ?>" style="width: auto; margin-right: 8px;">
                                <?php echo h($tl['TenTL']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Đạo diễn:</label>
                        <input type="text" name="DaoDien">
                    </div>
                    <div class="form-group">
                        <label>Diễn viên:</label>
                        <input type="text" name="DienVien">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ngày khởi chiếu: *</label>
                        <input type="date" name="NgayKhoiChieu" required>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái: *</label>
                        <select name="TrangThai" required>
                            <option value="Đang chiếu">Đang chiếu</option>
                            <option value="Đã kết thúc">Đã kết thúc</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Poster URL:</label>
                        <input type="text" name="PosterURL" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label>Trailer URL:</label>
                        <input type="text" name="TrailerURL" placeholder="https://youtube.com/...">
                    </div>
                </div>
                <div class="form-group">
                    <label>Mô tả:</label>
                    <textarea name="Mota"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Thêm Phim</button>
            </form>
        </div>
        <?php endif; ?>

        <?php 
        $editMovie = null;
        $selectedGenres = [];
        if (isset($_GET['edit'])) {
            $editId = (int)$_GET['edit'];
            foreach ($movies as $movie) {
                if ($movie['MaPhim'] == $editId) {
                    $editMovie = $movie;
                    break;
                }
            }
            
            if ($editMovie) {
                $sql = $db->prepare("SELECT MaTL FROM phim_theloai WHERE MaPhim = ?");
                $sql->bind_param('i', $editId);
                $sql->execute();
                $result = $sql->get_result();
                while ($row = $result->fetch_assoc()) {
                    $selectedGenres[] = $row['MaTL'];
                }
            }
        }
        if ($editMovie): 
        ?>
        <div class="form-section">
            <h2>Sửa Phim</h2>
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="MaPhim" value="<?php echo $editMovie['MaPhim']; ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Tên phim: *</label>
                        <input type="text" name="TenPhim" value="<?php echo h($editMovie['TenPhim']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Thời lượng (phút): *</label>
                        <input type="number" name="ThoiLuong" value="<?php echo h($editMovie['ThoiLuong']); ?>" min="1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Thể loại: * (Chọn ít nhất 1)</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 15px; background: #f8f9fa; border-radius: 5px; border: 1px solid #ddd;">
                        <?php foreach($theloai as $tl): ?>
                            <label style="display: flex; align-items: center; cursor: pointer; font-weight: normal;">
                                <input type="checkbox" name="TheLoai[]" value="<?php echo $tl['MaTL']; ?>" 
                                    <?php echo in_array($tl['MaTL'], $selectedGenres) ? 'checked' : ''; ?>
                                    style="width: auto; margin-right: 8px;">
                                <?php echo h($tl['TenTL']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Đạo diễn:</label>
                        <input type="text" name="DaoDien" value="<?php echo h($editMovie['DaoDien']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Diễn viên:</label>
                        <input type="text" name="DienVien" value="<?php echo h($editMovie['DienVien']); ?>">
                    </div>
                </div>
                    <div class="form-group">
                        <label>Ngày khởi chiếu: *</label>
                        <input type="date" name="NgayKhoiChieu" value="<?php echo $editMovie['NgayKhoiChieu']; ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Poster URL:</label>
                        <input type="text" name="PosterURL" value="<?php echo h($editMovie['PosterURL']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Trailer URL:</label>
                        <input type="text" name="TrailerURL" value="<?php echo h($editMovie['TrailerURL']); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Trạng thái: *</label>
                        <select name="TrangThai" required>
                            <option value="Đang chiếu" <?php echo ($editMovie['TrangThai'] == 'Đang chiếu') ? 'selected' : ''; ?>>Đang chiếu</option>
                            <option value="Đã kết thúc" <?php echo ($editMovie['TrangThai'] == 'Đã kết thúc') ? 'selected' : ''; ?>>Đã kết thúc</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mô tả:</label>
                    <textarea name="Mota"><?php echo h($editMovie['Mota']); ?></textarea>
                </div>
                
                <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0; font-size: 14px; text-decoration: underline; margin-right: 15px;">Cập nhật</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if (!isset($_GET['add']) && !isset($_GET['edit'])): ?>
        <div class="form-section">
            <h2>Danh Sách Phim</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Poster</th>
                        <th>Tên phim</th>
                        <th>Thể loại</th>
                        <th>Thời lượng</th>
                        <th>Ngày KC</th>
                        <th>Trạng thái</th>
                        <th>Trailer</th>
                        <th>Mô tả</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($movies as $movie): ?>
                        <tr>
                            <td><?php echo $movie['MaPhim']; ?></td>
                            <td>
                                <?php if ($movie['PosterURL']): ?>
                                    <img src="<?php echo h($movie['PosterURL']); ?>" class="movie-poster" alt="Poster">
                                <?php else: ?>
                                    <div style="width: 60px; height: 90px; background: #ccc; border-radius: 5px;"></div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo h($movie['TenPhim']); ?></strong></td>
                            <td><?php echo h($movie['TheLoaiList'] ?: $movie['TheLoai']); ?></td>
                            <td><?php echo $movie['ThoiLuong']; ?> phút</td>
                            <td><?php echo date('d/m/Y', strtotime($movie['NgayKhoiChieu'])); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $movie['TrangThai'] == 'Đang chiếu' ? 'showing' : ($movie['TrangThai'] == 'Sắp chiếu' ? 'upcoming' : 'ended'); ?>">
                                    <?php echo h($movie['TrangThai']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($movie['TrailerURL'])): ?>
                                    <a href="<?php echo h($movie['TrailerURL']); ?>" target="_blank" style="text-decoration: underline; color: #333;">
                                        Xem Trailer
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">Chưa có</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo h(substr($movie['Mota'], 0, 50)) . (strlen($movie['Mota']) > 50 ? '...' : ''); ?></td>
                            <td>
                                <a href="?edit=<?php echo $movie['MaPhim']; ?>" style="margin-right: 10px; text-decoration: underline; color: #dc3545;">Sửa</a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="MaPhim" value="<?php echo $movie['MaPhim']; ?>">
                                    <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0; font-size: 14px; text-decoration: underline;" onclick="return confirm('Bạn có chắc muốn xóa phim này?');">Xóa</button>
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

