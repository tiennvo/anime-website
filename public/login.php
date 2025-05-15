<?php
session_start();
include_once 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn để kiểm tra thông tin đăng nhập
    $query = "SELECT id, username, password, money FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra mật khẩu
    if ($user && password_verify($password, $user['password'])) {
        // Lưu thông tin người dùng vào phiên
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['money'] = $user['money'];
        // Chuyển hướng về trang chính
        header("Location: /anime/index.php");
        exit;
    } else {
        // Thông báo lỗi nếu đăng nhập không thành công
        $errorMsg = "Tài khoản hoặc mật khẩu không đúng.";
    }
}

// Truy vấn thể loại
$genresQuery = "SELECT id, name FROM genres";
$genresResult = mysqli_query($conn, $genresQuery);

// Kiểm tra lỗi truy vấn
if (!$genresResult) {
    die("Query failed: " . mysqli_error($conn));
}

// Truy vấn quốc gia
$countriesQuery = "SELECT id, name FROM countries";
$countriesResult = mysqli_query($conn, $countriesQuery);

// Kiểm tra lỗi truy vấn
if (!$countriesResult) {
    die("Query failed: " . mysqli_error($conn));
}
?>

