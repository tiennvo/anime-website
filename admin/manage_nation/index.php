<?php
session_start(); // Gọi session_start() ở đầu tập tin
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
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Quốc gia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Danh sách Quốc gia</h1>
        <?php if ($current_user_role === 'admin'): ?>
            <a href="create.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Thêm Quốc gia</a>
        <?php endif; ?>
        <?php
        // Lấy danh sách quốc gia từ cơ sở dữ liệu
        $sql = "SELECT * FROM countries";
        $result = $conn->query($sql);
        ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tên Quốc gia</th>
                    <?php if ($current_user_role === 'admin'): ?>
                        <th>Thao tác</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['name']}</td>";
                                
                        if ($current_user_role === 'admin') {
                            echo "<td>
                                    <a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Sửa</a>
                                    <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Xóa</a>
                                </td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . ($current_user_role === 'admin' ? "2" : "1") . "'>Không có dữ liệu</td></tr>";
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
