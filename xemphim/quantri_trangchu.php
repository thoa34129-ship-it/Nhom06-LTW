    <?php
    session_start();
    require_once 'cauhinh.php';
    requireAdmin();

    $db = Database::getInstance()->getConnection();

    $sqlQuery = "SELECT 
        (SELECT COUNT(*) FROM khachhang) as totalCustomers,
        (SELECT COUNT(*) FROM phim) as totalMovies,
        (SELECT COUNT(*) FROM datve_thanhtoan) as totalBookings,
        (SELECT SUM(TongTien) FROM datve_thanhtoan WHERE TrangThaiThanhToan = 'DaThanhToan') as totalRevenue";
    $sql = $db->query($sqlQuery)->fetch_assoc();
    ?>
<?php require_once 'header.php'; ?>

<link rel="stylesheet" href="quantri_trangchu.css">

<div class="container">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h3>Menu Quản Trị</h3>
            <ul>
                <li><a href="quantri_trangchu.php">Trang Chủ</a></li>
                <li><a href="quantri_phim.php">Quản Lý Phim</a></li>
                <li><a href="quantri_suatchieu.php">Quản Lý Suất Chiếu</a></li>
                <li><a href="quantri_khachhang.php">Quản Lý Khách Hàng</a></li>
                <li><a href="quantri_giohang.php">Xem Giỏ Hàng</a></li>
            </ul>
        </aside>
        
        <main class="admin-content">
            <h2>Đây là trang quản trị</h2>
            <p>Chọn một mục từ menu bên trái để bắt đầu quản lý.</p>
            <div style="margin-top: 30px;">
                <div style="padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="margin-top: 0;"> Thông tin</h3>
                    <ul>
                        <li><strong>Quản Lý Phim:</strong> Xem danh sách phim có thể Thêm, sửa, xóa thông tin phim</li>
                        <li><strong>Quản Lý Suất Chiếu:</strong> Quản lý lịch chiếu phim có thể Thêm, sửa, xóa Suất Chiếu </li>
                        <li><strong>Quản Lý Khách Hàng:</strong> Xem danh sách khách hàng có thể Thêm, sửa, xóa Khách hàng</li>
                        <li><strong>Xem Giỏ Hàng:</strong> Xem danh sách đặt vé của khách hàng</li>
                    </ul>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once 'footer.php'; ?>
