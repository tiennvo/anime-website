<?php
include_once 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Kiểm tra xem tên tài khoản đã tồn tại chưa
    $checkUsernameQuery = "SELECT * FROM users WHERE username = '$username'";
    $usernameResult = mysqli_query($conn, $checkUsernameQuery);
    
    // Kiểm tra xem email đã tồn tại chưa
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $emailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($usernameResult) > 0) {
        // Tên tài khoản đã tồn tại
        echo "<script>
            alert('Tên tài khoản đã được sử dụng.');
            window.location.href = '/anime/index.php'; // Hoặc trang đăng ký của bạn
        </script>";
    } elseif (mysqli_num_rows($emailResult) > 0) {
        // Email đã tồn tại
        echo "<script>
            alert('Email đã được sử dụng.');
            window.location.href = '/anime/index.php'; // Hoặc trang đăng ký của bạn
        </script>";
    } else {
        // Tạo tài khoản mới
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Băm mật khẩu
        $insertQuery = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashedPassword', 'member')";
        if (mysqli_query($conn, $insertQuery)) {
            // Đăng ký thành công
            echo "<script>
                alert('Đăng ký thành công. Bạn có thể đăng nhập ngay.');
                window.location.href = '/anime/index.php'; // Hoặc trang đăng nhập của bạn
            </script>";
        } else {
            // Lỗi khi thực hiện truy vấn
            echo "<script>
                alert('Đã xảy ra lỗi. Vui lòng thử lại.');
                window.location.href = '/anime/index.php'; // Hoặc trang đăng ký của bạn
            </script>";
        }
    }
}
?>
