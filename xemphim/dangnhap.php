<?php
session_start();
require_once 'cauhinh.php';

if (isLoggedIn()) {
    redirect('trangchu.php');
}
if (isAdmin()) {
    redirect('quantri_trangchu.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $matKhau = $_POST['matKhau'] ?? '';
    
    if (empty($email) || empty($matKhau)) {
        $error = ' Vui Lòng Nhập Email và Mật Khẩu.';
    } else {
        $db = Database::getInstance()->getConnection();

        $sql = "SELECT MaAdmin, HoVaTen, MatKhau FROM admin WHERE Email = '$email'";
        $result = $db->query($sql);

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($matKhau, $admin['MatKhau'])) {
                $_SESSION['admin_id'] = $admin['MaAdmin'];
                $_SESSION['admin_name'] = $admin['HoVaTen'];
                redirect('quantri_trangchu.php');
            } else {
                $error = 'Email hoặc mật khẩu không đúng.';
            }
        } else {
            $sql = "SELECT MaKhachHang, HoVaTen, MatKhau FROM khachhang WHERE Email = '$email'";
            $result = $db->query($sql);
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($matKhau, $user['MatKhau'])) {
                    $_SESSION['user_id'] = $user['MaKhachHang'];
                    $_SESSION['user_name'] = $user['HoVaTen'];
                    redirect('trangchu.php');
                } else {
                    $error = 'Email hoặc mật khẩu không đúng.';
                }
            } else {
                $error = 'Email hoặc mật khẩu không đúng.';
            }
        }
        // ...existing code...
    }
}
?>
<?php require_once 'header.php'; ?>

<div class="container" style="max-width: 500px; margin: 50px auto;">
    <div class="form-container">
        <h2>Đăng nhập</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo h($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="matKhau">Mật khẩu</label>
                <input type="password" id="matKhau" name="matKhau" required>
            </div>

            <button type="submit" class="btn">Đăng nhập</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Chưa có tài khoản? <a href="dangky.php">Đăng ký ngay</a>
        </p>
    </div>
</div>

<?php require_once 'footer.php'; ?>
