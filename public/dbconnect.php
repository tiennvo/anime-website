<?php


$servername = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "movie";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
function format_cash($price)
{
    return str_replace(",", ".", number_format($price));
}
?>
