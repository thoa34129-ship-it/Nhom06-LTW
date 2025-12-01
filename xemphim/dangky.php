<?php
session_start();
require_once 'cauhinh.php';

if (isLoggedIn()) {
    redirect('trangchu.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoTen = trim($_POST['hoTen'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $soDienThoai = trim($_POST['soDienThoai'] ?? '');
    $matKhau = $_POST['matKhau'] ?? '';
    $xacNhanMatKhau = $_POST['xacNhanMatKhau'] ?? '';
    
    if (empty($hoTen) || empty($email) || empty($matKhau)) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
    } elseif ($matKhau !== $xacNhanMatKhau) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } elseif (strlen($matKhau) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } else {
        $db = Database::getInstance()->getConnection();
        $sql = "SELECT MaKhachHang FROM khachhang WHERE Email = '$email'";
        $result = $db->query($sql);
        if ($result && $result->num_rows > 0) {
            $error = 'Email đã được sử dụng.';
        } else {
            $hashedPassword = password_hash($matKhau, PASSWORD_DEFAULT);
            $sql = "INSERT INTO khachhang (HoVaTen, Email, SoDienThoai, MatKhau) VALUES ('$hoTen', '$email', '$soDienThoai', '$hashedPassword')";
            $result = $db->query($sql);
            if ($result) {
                $success = 'Đăng ký thành công! Đang chuyển hướng...';
                header('refresh:2;url=dangnhap.php');
            } else {
                $error = 'Có lỗi xảy ra. Vui lòng thử lại.';
            }
        }
    }
}
?>
<?php require_once 'header.php'; ?>

    <div class="form-container">
        <h2>Đăng ký tài khoản</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo h($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo h($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="hoTen">Họ và Tên *</label>
                <input type="text" id="hoTen" name="hoTen" required value="<?php echo h($_POST['hoTen'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?php echo h($_POST['email'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="soDienThoai">Số điện thoại</label>
                <input type="tel" id="soDienThoai" name="soDienThoai" value="<?php echo h($_POST['soDienThoai'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="matKhau">Mật khẩu *</label>
                <input type="password" id="matKhau" name="matKhau" required>
            </div>

            <div class="form-group">
                <label for="xacNhanMatKhau">Xác nhận mật khẩu *</label>
                <input type="password" id="xacNhanMatKhau" name="xacNhanMatKhau" required>
            </div>

            <button type="submit" class="btn">Đăng ký</button>
        </form>

        <p class="text-center" style="margin-top: 20px;">
            Đã có tài khoản? <a href="dangnhap.php">Đăng nhập ngay</a>
        </p>
    </div>

<?php require_once 'footer.php'; ?>
