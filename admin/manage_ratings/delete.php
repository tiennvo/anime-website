<?php
session_start();
include('../dbconnect.php');

// Kiểm tra xem người dùng có đăng nhập chưa và có quyền xóa không
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Bạn không có quyền xóa đánh giá này.</div>";
    exit();
}

// Kiểm tra xem có ID của đánh giá cần xóa không
if (isset($_GET['id'])) {
    $rating_id = $_GET['id'];

    // Chuẩn bị câu lệnh SQL để xóa đánh giá
    $sql = "DELETE FROM ratings WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $rating_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<div class='alert alert-success'>Đánh giá đã được xóa thành công.</div>";
        } else {
            echo "<div class='alert alert-danger'>Không thể xóa đánh giá. Vui lòng thử lại.</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Có lỗi xảy ra khi chuẩn bị câu lệnh xóa.</div>";
    }

    // Chuyển hướng về trang quản lý đánh giá sau khi xóa
    header('Location: index.php');
    exit();
} else {
    echo "<div class='alert alert-danger'>Không tìm thấy ID của đánh giá cần xóa.</div>";
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
