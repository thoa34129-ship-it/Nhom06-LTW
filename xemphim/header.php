<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Đặt Vé Xem Phim'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1><a href="trangchu.php" style="color: #16d8ff; text-decoration: none;">Đặt Vé Xem Phim</a></h1>
            <nav>
                <a href="trangchu.php">Trang chủ</a>
                <a href="theloai.php">Thể loại</a>
                <?php if (isAdmin()): ?>
                    <a href="quantri_trangchu.php" style="background: #667eea; padding: 5px 10px; border-radius: 5px;">Quản trị</a>
                    <a href="#">Xin chào, <?php echo h($_SESSION['admin_name']); ?></a>
                    <a href="dangxuat.php" class="btn-danger">Đăng xuất</a>
                <?php elseif (isLoggedIn()): ?>
                    <a href="vedachon.php">Giỏ hàng</a>
                    <a href="lichsu_datve.php">Lịch sử đặt vé</a>
                    <a href="profile.php">Xin chào, <?php echo h($_SESSION['user_name']); ?></a>
                    <a href="dangxuat.php" class="btn-danger">Đăng xuất</a>
                <?php else: ?>
                    <a href="vedachon.php">Giỏ hàng</a>
                    <a href="lichsu_datve.php">Lịch sử đặt vé </a>
                    <a href="dangnhap.php">Đăng nhập</a>
                    <a href="dangky.php" class="btn-primary">Đăng ký</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
