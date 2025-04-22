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

// Xử lý yêu cầu xóa người dùng
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    if (!empty($user_id)) {
        // Xóa người dùng khỏi cơ sở dữ liệu
        $sql = "DELETE FROM users WHERE id=$user_id";
        if ($conn->query($sql) === TRUE) {
            $message = "Người dùng đã được xóa thành công.";
            $message_type = "success";
        } else {
            $message = "Có lỗi khi xóa người dùng: " . $conn->error;
            $message_type = "danger";
        }
    } else {
        $message = "ID người dùng không hợp lệ.";
        $message_type = "warning";
    }
} else {
    $message = "ID người dùng không được xác định.";
    $message_type = "danger";
}

// Hiển thị thông báo
if (!empty($message)) {
    echo "<div class='alert alert-$message_type'>$message</div>";
    // Quay lại trang danh sách người dùng sau 2 giây
    echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000); // 2 giây để người dùng đọc thông báo
          </script>";
}

$conn->close();
?>
