<?php
session_start();
include('../dbconnect.php');

// Kiểm tra và lấy vai trò người dùng từ session
if (!isset($_SESSION['role'])) {
    echo "<div class='alert alert-danger'>Vai trò của bạn chưa được thiết lập. Vui lòng đăng nhập lại.</div>";
    exit();
}

// Kiểm tra xem người dùng có quyền admin không
if ($_SESSION['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Bạn không có quyền để thực hiện hành động này.</div>";
    exit();
}

// Kiểm tra xem ID có được truyền vào không
if (isset($_GET['id'])) {
    $comment_id = $_GET['id'];

    // Thực hiện câu truy vấn xóa bình luận
    $sql = "DELETE FROM comments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $comment_id);

    if ($stmt->execute()) {
        // Nếu xóa thành công, quay về trang quản lý bình luận
        header("Location: index.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Có lỗi xảy ra khi xóa bình luận.</div>";
    }

    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Không tìm thấy ID bình luận.</div>";
}

$conn->close();
?>
