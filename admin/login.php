<?php
session_start();
require_once 'dbconnect.php'; // Bao gồm file kết nối cơ sở dữ liệu

// Xử lý đăng xuất
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn để lấy thông tin người dùng từ cơ sở dữ liệu
    $stmt = $conn->prepare('SELECT id, username, password, role FROM users WHERE username = ?');
    if (!$stmt) {
        die("Lỗi chuẩn bị câu lệnh: " . $conn->error);
    }
    
    $stmt->bind_param('s', $username); // Bind parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Debug: Hiển thị thông tin người dùng từ cơ sở dữ liệu
        echo "<pre>";
        print_r($user);
        echo "</pre>";

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Lưu vai trò vào session
            header('Location: index.php'); // Chuyển hướng đến trang chính
            exit();
        } else {
            $error = 'Tên người dùng hoặc mật khẩu không chính xác!';
        }
    } else {
        $error = 'Tên người dùng không tồn tại!';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="view/style.css"> <!-- Thêm file CSS tùy chỉnh nếu cần -->
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Đăng Nhập</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="username">Tên người dùng:</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Mật khẩu:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="text-muted mb-0">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
