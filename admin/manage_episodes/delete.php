<?php
session_start();
include('../dbconnect.php');

// Kiểm tra và lấy vai trò người dùng từ session
if (!isset($_SESSION['role'])) {
    echo "<div class='alert alert-danger'>Vai trò của bạn chưa được thiết lập. Vui lòng đăng nhập lại.</div>";
    exit();
}

$current_user_role = $_SESSION['role'];

if ($current_user_role != 'admin' && $current_user_role != 'trans') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

if (isset($_GET['id'])) {
    $episode_id = intval($_GET['id']);
    
    $sql = "DELETE FROM episodes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $episode_id);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Xóa tập phim thành công.</div>";
    } else {
        echo "<div class='alert alert-danger'>Lỗi: " . $stmt->error . "</div>";
    }
    
    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>ID tập phim không được chỉ định.</div>";
    exit();
}
?>
<a href="index.php" class="btn btn-primary">Quay lại danh sách tập phim</a>
