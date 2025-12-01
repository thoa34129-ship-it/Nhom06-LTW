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
                $hoVaTen = trim($_POST['HoVaTen']);
                $email = trim($_POST['Email']);
                $soDienThoai = trim($_POST['SoDienThoai']);
                $matKhau = $_POST['MatKhau'];
                
                $sql = "SELECT MaKhachHang FROM khachhang WHERE Email = '$email'";
                $result = $db->query($sql);
                if ($result && $result->num_rows > 0) {
                    $message = '<div class="alert alert-danger">Email đã tồn tại!</div>';
                } else {
                    $hashedPassword = password_hash($matKhau, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO khachhang (HoVaTen, Email, SoDienThoai, MatKhau, HoatDong) VALUES ('$hoVaTen', '$email', '$soDienThoai', '$hashedPassword', 1)";
                    if ($db->query($sql)) {
                        $message = '<div class="alert alert-success">Thêm khách hàng thành công!</div>';
                    } else {
                        $message = '<div class="alert alert-danger">Lỗi: ' . $db->error . '</div>';
                    }
                }
                break;
                
            case 'delete':
                $maKhachHang = $_POST['MaKhachHang'];
                

                $sql = "SELECT COUNT(*) as count FROM datve_thanhtoan WHERE MaKhachHang = '$maKhachHang'";
                $result = $db->query($sql)->fetch_assoc();
                
                if ($result['count'] > 0) {
                    $message = '<div class="alert alert-danger">Không thể xóa khách hàng đã có đơn hàng!</div>';
                } else {
                    $sql = "DELETE FROM khachhang WHERE MaKhachHang = '$maKhachHang'";
                    if ($db->query($sql)) {
                        $message = '<div class="alert alert-success">Xóa khách hàng thành công!</div>';
                    } else {
                        $message = '<div class="alert alert-danger">Lỗi: Không thể xóa khách hàng!</div>';
                    }
                }
                break;
                
            case 'update':
                $maKhachHang = $_POST['MaKhachHang'];
                $hoVaTen = trim($_POST['HoVaTen']);
                $email = trim($_POST['Email']);
                $soDienThoai = trim($_POST['SoDienThoai']);
                $matKhau = $_POST['MatKhau'] ?? '';
                
      
                $sql = $db->prepare("SELECT MaKhachHang FROM khachhang WHERE Email = ? AND MaKhachHang != ?");
                $sql->bind_param('si', $email, $maKhachHang);
                $sql->execute();
                if ($sql->get_result()->num_rows > 0) {
                    $message = '<div class="alert alert-danger">Email đã tồn tại!</div>';
                } else {
                    if (!empty($matKhau)) {
                        $hashedPassword = password_hash($matKhau, PASSWORD_DEFAULT);
                        $sql = $db->prepare("UPDATE khachhang SET HoVaTen=?, Email=?, SoDienThoai=?, MatKhau=? WHERE MaKhachHang=?");
                        $sql->bind_param('ssssi', $hoVaTen, $email, $soDienThoai, $hashedPassword, $maKhachHang);
                    } else {
                        $sql = $db->prepare("UPDATE khachhang SET HoVaTen=?, Email=?, SoDienThoai=? WHERE MaKhachHang=?");
                        $sql->bind_param('sssi', $hoVaTen, $email, $soDienThoai, $maKhachHang);
                    }
                    
                    if ($sql->execute()) {
                        $message = '<div class="alert alert-success">Cập nhật khách hàng thành công!</div>';
                    } else {
                        $message = '<div class="alert alert-danger">Lỗi: ' . $sql->error . '</div>';
                    }
                }
                break;
        }
    }
}

$customers = $db->query("SELECT * FROM khachhang ORDER BY NgayTao DESC")->fetch_all(MYSQLI_ASSOC);
?>
<?php require_once 'header.php'; ?>

<link rel="stylesheet" href="quantri_khachhang.css">

<div class="page-title">
    <div class="container">
        <h2>Quản Lý Khách Hàng</h2>
    </div>
</div>

<div class="container">
    <div class="admin-links">
        <a href="quantri_trangchu.php">← Về trang chủ Admin</a>
    </div>
    
    <?php echo $message; ?>
    

    <div style="margin-bottom: 20px;">
        <?php if (!isset($_GET['add']) && !isset($_GET['edit'])): ?>
            <a href="?add=1" class="btn" style="background: #dc3545; color: white; border: none; display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px;">+ Thêm Khách Hàng Mới</a>
        <?php else: ?>
            <a href="quantri_khachhang.php" class="btn" style="background: #6c757d; color: white; border: none; display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 5px;">← Hủy</a>
        <?php endif; ?>
    </div>
    
    <?php if (isset($_GET['add'])): ?>
    <div id="addCustomerForm" class="form-section">
        <h3>Thêm Khách Hàng Mới</h3>
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Họ Và Tên:</label>
                    <input type="text" name="HoVaTen" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="Email" required>
                </div>
                <div class="form-group">
                    <label>Số Điện Thoại:</label>
                    <input type="tel" name="SoDienThoai">
                </div>
                <div class="form-group">
                    <label>Mật Khẩu:</label>
                    <input type="password" name="MatKhau" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Thêm Khách Hàng</button>
        </form>
    </div>
    <?php endif; ?>
    

    <?php 
    $editCustomer = null;
    if (isset($_GET['edit'])) {
        $editId = (int)$_GET['edit'];
        foreach ($customers as $customer) {
            if ($customer['MaKhachHang'] == $editId) {
                $editCustomer = $customer;
                break;
            }
        }
    }
    if ($editCustomer): 
    ?>
    <div class="form-section">
        <h3>Sửa Thông Tin Khách Hàng</h3>
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="MaKhachHang" value="<?php echo $editCustomer['MaKhachHang']; ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Họ Và Tên:</label>
                    <input type="text" name="HoVaTen" value="<?php echo h($editCustomer['HoVaTen']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="Email" value="<?php echo h($editCustomer['Email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Số Điện Thoại:</label>
                    <input type="tel" name="SoDienThoai" value="<?php echo h($editCustomer['SoDienThoai']); ?>">
                </div>
                <div class="form-group">
                    <label>Mật Khẩu Mới: <small>(Để trống nếu không đổi)</small></label>
                    <input type="password" name="MatKhau">
                </div>
            </div>
            
            <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0; font-size: 14px; text-decoration: underline; margin-right: 15px;">Cập Nhật</button>
        </form>
    </div>
    <?php endif; ?>
    
    <?php if (!isset($_GET['add']) && !isset($_GET['edit'])): ?>
    <div class="form-section">
        <h3>Danh Sách Khách Hàng</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ Và Tên</th>
                    <th>Email</th>
                    <th>Số Điện Thoại</th>
                    <th>Ngày Tạo</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?php echo $customer['MaKhachHang']; ?></td>
                    <td><?php echo h($customer['HoVaTen']); ?></td>
                    <td><?php echo h($customer['Email']); ?></td>
                    <td><?php echo h($customer['SoDienThoai']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($customer['NgayTao'])); ?></td>
                    <td>
                        <a href="?edit=<?php echo $customer['MaKhachHang']; ?>" style="margin-right: 10px; text-decoration: underline; color: #dc3545;">Sửa</a>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="MaKhachHang" value="<?php echo $customer['MaKhachHang']; ?>">
                            <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; padding: 0; font-size: 14px; text-decoration: underline;" onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?');">Xóa</button>
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
