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

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'trans'])) {
    header('Location: login.php');
    exit();
}

// Truy vấn tổng số phim
$sql_total_movies = "SELECT COUNT(*) AS total FROM movies";
$result_total_movies = $conn->query($sql_total_movies);
$total_movies = $result_total_movies->fetch_assoc()['total'];

// Truy vấn tổng số người dùng
$sql_total_users = "SELECT COUNT(*) AS total FROM users";
$result_total_users = $conn->query($sql_total_users);
$total_users = $result_total_users->fetch_assoc()['total'];

// Truy vấn tổng số tập phim
$sql_total_episodes = "SELECT COUNT(*) AS total FROM episodes";
$result_total_episodes = $conn->query($sql_total_episodes);
$total_episodes = $result_total_episodes->fetch_assoc()['total'];

// Truy vấn tổng số bình luận
$sql_total_comments = "SELECT COUNT(*) AS total FROM comments";
$result_total_comments = $conn->query($sql_total_comments);
$total_comments = $result_total_comments->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="view/style.css">
    <style>
        .dashboard-card {
            margin-bottom: 20px;
        }
        .card-header {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-body {
            font-size: 1.5rem;
        }
        .card {
            border-radius: 10px;
        }
        .bg-movie {
            background-color: #007bff; /* Màu xanh dương */
            color: white;
        }
        .bg-user {
            background-color: #28a745; /* Màu xanh lá cây */
            color: white;
        }
        .bg-episode {
            background-color: #ffc107; /* Màu vàng */
            color: white;
        }
        .bg-comment {
            background-color: #dc3545; /* Màu đỏ */
            color: white;
        }
    </style>
</head>
<body>
    <?php include_once('sidebar.php'); ?>
    <div class="content">
        <h1>Chào mừng đến với Trang Admin</h1>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="card dashboard-card bg-movie">
                        <div class="card-header">
                            Tổng Phim Hiện Có
                        </div>
                        <div class="card-body">
                            <?php echo $total_movies; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card dashboard-card bg-user">
                        <div class="card-header">
                            Tổng Số Người Dùng Đăng Ký
                        </div>
                        <div class="card-body">
                            <?php echo $total_users; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card dashboard-card bg-episode">
                        <div class="card-header">
                            Tổng Số Tập Phim Hiện Có
                        </div>
                        <div class="card-body">
                            <?php echo $total_episodes; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card dashboard-card bg-comment">
                        <div class="card-header">
                            Tổng Bình Luận Trên Web
                        </div>
                        <div class="card-body">
                            <?php echo $total_comments; ?>
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
