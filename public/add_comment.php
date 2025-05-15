<?php
session_start();
include_once 'dbconnect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $movie_id = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Kiểm tra tính hợp lệ của bình luận
    if ($movie_id > 0 && !empty($comment)) {
        // Chèn bình luận vào cơ sở dữ liệu
        $comment_sql = "INSERT INTO comments (user_id, movie_id, comment, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($comment_sql);
        $stmt->bind_param("iis", $user_id, $movie_id, $comment);
        
        if ($stmt->execute()) {
            // Chuyển hướng về trang playvideo.php với ID của phim
            header("Location: playvideo.php?id=" . $movie_id);
            exit();
        } else {
            echo "Lỗi: Không thể thêm bình luận. Vui lòng thử lại.";
        }
    } else {
        echo "Lỗi: Vui lòng nhập nội dung bình luận.";
    }
}
?>
