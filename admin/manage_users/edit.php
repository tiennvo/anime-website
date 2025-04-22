<?php
session_start(); // Gọi session_start() ở đầu tập tin
include('../dbconnect.php');

// Kiểm tra và lấy vai trò người dùng từ session
if (!isset($_SESSION['role'])) {
    echo "<div class='alert alert-danger'>Vai trò của bạn chưa được thiết lập. Vui lòng đăng nhập lại.</div>";
    exit();
}

$current_user_role = $_SESSION['role'];

// Kiểm tra quyền truy cập
if ($current_user_role !== 'admin') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

$message = "";
$message_type = "";

// Xử lý yêu cầu cập nhật vai trò
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    if (!empty($user_id) && !empty($new_role)) {
        // Cập nhật vai trò người dùng trong cơ sở dữ liệu
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $new_role, $user_id);

        if ($stmt->execute()) {
            $message = "Vai trò đã được cập nhật thành công.";
            $message_type = "success";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000); // 2 giây để người dùng đọc thông báo
                  </script>";
        } else {
            $message = "Có lỗi xảy ra khi cập nhật vai trò: " . $conn->error;
            $message_type = "danger";
        }

        $stmt->close();
    } else {
        $message = "Dữ liệu không hợp lệ.";
        $message_type = "warning";
    }
}

// Lấy thông tin người dùng cần chỉnh sửa
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $sql = "SELECT id, username, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $message = "Người dùng không tồn tại.";
        $message_type = "danger";
        $user = null; // Đảm bảo biến user không được sử dụng khi không có dữ liệu
    }
    $stmt->close();
} else {
    $message = "ID người dùng không được cung cấp.";
    $message_type = "danger";
}

if (!empty($message)) {
    echo "<div class='alert alert-$message_type'>$message</div>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Vai Trò</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Chỉnh Sửa Vai Trò</h1>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Trở về</a>

        <?php if (isset($user)): ?>
        <form action="edit.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="role">Vai trò:</label>
                <select id="role" name="role" class="form-control">
                    <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="trans" <?php if ($user['role'] === 'trans') echo 'selected'; ?>>Người dịch</option>
                    <option value="user" <?php if ($user['role'] === 'user') echo 'selected'; ?>>Người xem</option>
                </select>
            </div>
            <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
