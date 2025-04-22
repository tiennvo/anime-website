<?php
include('../dbconnect.php');

if (isset($_GET['id'])) {
    $movie_id = intval($_GET['id']);

    // Xóa thể loại của phim
    $sql_delete_genres = "DELETE FROM movie_genres WHERE movie_id = $movie_id";
    $conn->query($sql_delete_genres);

    // Xóa phim khỏi cơ sở dữ liệu
    $sql_delete_movie = "DELETE FROM movies WHERE id = $movie_id";
    if ($conn->query($sql_delete_movie) === TRUE) {
        $message = "Phim đã được xóa thành công.";
        $message_type = "success";
    } else {
        $message = "Có lỗi khi xóa phim: " . $conn->error;
        $message_type = "danger";
    }
} else {
    $message = "ID phim không hợp lệ.";
    $message_type = "warning";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa Phim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <div class="container">
        <div class="alert alert-<?php echo $message_type; ?>" role="alert">
            <?php echo $message; ?>
        </div>
        <a href="index.php" class="btn btn-primary">Quay lại danh sách phim</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
