<?php
session_start();
require_once 'cauhinh.php';
requireLogin();

$db = Database::getInstance()->getConnection();
$message = '';
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoVaTen = trim($_POST['HoVaTen']);
    $email = trim($_POST['Email']);
    $soDienThoai = trim($_POST['SoDienThoai']);
    $matKhauCu = $_POST['MatKhauCu'] ?? '';
    $matKhauMoi = $_POST['MatKhauMoi'] ?? '';
    $xacNhanMatKhau = $_POST['XacNhanMatKhau'] ?? '';
    
    $sql = $db->prepare("SELECT * FROM khachhang WHERE MaKhachHang = ?");
    $sql->bind_param('i', $userId);
    $sql->execute();
    $currentInfo = $sql->get_result()->fetch_assoc();
    
    $hasChanges = ($hoVaTen !== $currentInfo['HoVaTen'] || 
                   $email !== $currentInfo['Email'] || 
                   $soDienThoai !== $currentInfo['SoDienThoai'] ||
                   !empty($matKhauMoi));
    
    if (!$hasChanges) {
        $message = '<div class="alert alert-danger">Không có thông tin nào thay đổi!</div>';
    } else {

        if ($email !== $currentInfo['Email']) {
            $sql = $db->prepare("SELECT MaKhachHang FROM khachhang WHERE Email = ? AND MaKhachHang != ?");
            $sql->bind_param('si', $email, $userId);
            $sql->execute();
            
            if ($sql->get_result()->num_rows > 0) {
                $message = '<div class="alert alert-danger">Email đã được sử dụng bởi tài khoản khác!</div>';
                $hasChanges = false;
            }
        }
        
        if ($hasChanges) {

            if (!empty($matKhauMoi)) {
                if (empty($matKhauCu)) {
                    $message = '<div class="alert alert-danger">Vui lòng nhập mật khẩu cũ!</div>';
                } elseif ($matKhauMoi !== $xacNhanMatKhau) {
                    $message = '<div class="alert alert-danger">Mật khẩu mới không khớp!</div>';
                } else {

                    $sql = $db->prepare("SELECT MatKhau FROM khachhang WHERE MaKhachHang = ?");
                    $sql->bind_param('i', $userId);
                    $sql->execute();
                    $result = $sql->get_result()->fetch_assoc();
                    
                    if (!password_verify($matKhauCu, $result['MatKhau'])) {
                        $message = '<div class="alert alert-danger">Mật khẩu cũ không đúng!</div>';
                    } else {

                        $hashedPassword = password_hash($matKhauMoi, PASSWORD_DEFAULT);
                        $sql = $db->prepare("UPDATE khachhang SET HoVaTen=?, Email=?, SoDienThoai=?, MatKhau=? WHERE MaKhachHang=?");
                        $sql->bind_param('ssssi', $hoVaTen, $email, $soDienThoai, $hashedPassword, $userId);
                        
                        if ($sql->execute()) {
                            $_SESSION['user_name'] = $hoVaTen;
                            $message = '<div class="alert alert-success">Cập nhật thông tin thành công!</div>';
                        } else {
                            $message = '<div class="alert alert-danger">Lỗi: ' . $sql->error . '</div>';
                        }
                    }
                }
            } else {
                $sql = $db->prepare("UPDATE khachhang SET HoVaTen=?, Email=?, SoDienThoai=? WHERE MaKhachHang=?");
                $sql->bind_param('sssi', $hoVaTen, $email, $soDienThoai, $userId);
                
                if ($sql->execute()) {
                    $_SESSION['user_name'] = $hoVaTen;
                    $message = '<div class="alert alert-success">Cập nhật thông tin thành công!</div>';
                } else {
                    $message = '<div class="alert alert-danger">Lỗi: ' . $sql->error . '</div>';
                }
            }
        }
    }
}


$sql = $db->prepare("SELECT * FROM khachhang WHERE MaKhachHang = ?");
$sql->bind_param('i', $userId);
$sql->execute();
$userInfo = $sql->get_result()->fetch_assoc();

$page_title = 'Chỉnh Sửa Hồ Sơ';
require_once 'header.php';
?>

<style>
    .profile-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
    }
    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .page-title {
        font-size: 28px;
        margin-bottom: 30px;
        color: #333;
    }
    .form-section {
        margin-bottom: 30px;
    }
    .form-section h3 {
        color: #333;
        font-size: 20px;
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }
    .form-group input {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
    }
    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .form-group small {
        display: block;
        margin-top: 5px;
        color: #666;
        font-size: 13px;
    }
    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
    }
    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        transition: all 0.3s;
    }
    .btn-primary {
        background: #667eea;
        color: white;
    }
    .btn-primary:hover {
        background: #5568d3;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background: #5a6268;
    }
</style>

<div class="profile-container">
    <h2 class="page-title">Chỉnh Sửa Hồ Sơ</h2>
    
    <?php echo $message; ?>
    
    <form method="POST">

        <div class="form-section">
            <h3>Thông Tin Cá Nhân</h3>
            
            <div class="form-group">
                <label>Họ Và Tên:</label>
                <input type="text" name="HoVaTen" value="<?php echo h($userInfo['HoVaTen']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="Email" value="<?php echo h($userInfo['Email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Số Điện Thoại:</label>
                <input type="tel" name="SoDienThoai" value="<?php echo h($userInfo['SoDienThoai']); ?>">
            </div>
        </div>
        
        <div class="form-section">
            <h3>Đổi Mật Khẩu</h3>
             
            <div class="form-group">
                <label>Mật Khẩu Cũ:</label>
                <input type="password" name="MatKhauCu" placeholder="Nhập mật khẩu hiện tại">
                <small>Bắt buộc nếu bạn muốn đổi mật khẩu</small>
            </div>
            
            <div class="form-group">
                <label>Mật Khẩu Mới:</label>
                <input type="password" name="MatKhauMoi" placeholder="Nhập mật khẩu mới">
                <small>Tối thiểu 6 ký tự</small>
            </div>
            
            <div class="form-group">
                <label>Xác Nhận Mật Khẩu Mới:</label>
                <input type="password" name="XacNhanMatKhau" placeholder="Nhập lại mật khẩu mới">
            </div>
        </div>
        
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="trangchu.php" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>
