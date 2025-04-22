<?php
session_start();
include('../dbconnect.php');

// Kiểm tra và lấy vai trò người dùng từ session
if (!isset($_SESSION['role'])) {
    echo "<div class='alert alert-danger'>Vai trò của bạn chưa được thiết lập. Vui lòng đăng nhập lại.</div>";
    exit();
}

$current_user_role = $_SESSION['role'];
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit();
}

// Lấy dữ liệu đánh giá từ cơ sở dữ liệu
$sql = "SELECT r.id, r.user_id, r.movie_id, r.rating, u.username, m.title 
        FROM ratings r 
        JOIN users u ON r.user_id = u.id 
        JOIN movies m ON r.movie_id = m.id";
$result = $conn->query($sql);

// Hàm tính toán số sao trung bình
function calculateStarRating($movie_id, $conn) {
    $query = "SELECT AVG(rating) as avg_rating FROM ratings WHERE movie_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $avg_rating = $row['avg_rating'];
    $stars = round($avg_rating); // Làm tròn số sao
    
    return $stars;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đánh giá</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Quản lý Đánh giá</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Người dùng</th>
                    <th>Phim</th>
                    <th>Đánh giá</th>
                    <th>Số sao trung bình</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $stars = calculateStarRating($row['movie_id'], $conn);
                        echo "<tr>
                                <td>{$row['username']}</td>
                                <td>{$row['title']}</td>
                                <td>{$row['rating']}</td>
                                <td>" . str_repeat('<i class="fas fa-star"></i>', $stars) . "</td>
                                <td>
                                    <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Xóa</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Không có đánh giá nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
