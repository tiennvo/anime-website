<?php
include('../dbconnect.php');

if (isset($_GET['id'])) {
    $comment_id = $_GET['id'];

    // Chuẩn bị truy vấn để ẩn bình luận (giả sử bạn có một cột `is_hidden` để quản lý trạng thái ẩn)
    $sql = "UPDATE comments SET is_hidden = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $comment_id);
        
        if ($stmt->execute()) {
            echo "<div class='alert alert-success'>Bình luận đã được ẩn thành công.</div>";
        } else {
            echo "<div class='alert alert-danger'>Lỗi khi ẩn bình luận.</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Lỗi truy vấn: " . $conn->error . "</div>";
    }
}

$conn->close();

// Quay lại trang quản lý bình luận
header('Location: index.php');
exit();
?>
