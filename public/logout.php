<?php
session_start();

// Xóa tất cả các biến phiên
$_SESSION = array();

// Nếu muốn xóa cookie liên quan đến phiên
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hủy phiên
session_destroy();

// Chuyển hướng về trang chính
header("Location: /anime/index.php");
exit;
?>
