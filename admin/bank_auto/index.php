<?php
session_start();
include('../dbconnect.php');

// Kiểm tra session role
if (!isset($_SESSION['role'])) {
    echo "<div class='alert alert-danger'>Vai trò của bạn chưa được thiết lập. Vui lòng đăng nhập lại.</div>";
    exit();
}
$current_user_role = $_SESSION['role'];

// Xử lý đăng xuất
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
    <title>Quản lý Cài đặt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>

    <div class="content container mt-4">
        <h1 class="mb-4">Danh sách Cài đặt (setting)</h1>

        <!-- <?php if ($current_user_role === 'admin'): ?>
            <a href="create.php" class="btn btn-primary mb-3">
                <i class="fas fa-plus"></i> Thêm Cài đặt
            </a>
        <?php endif; ?> -->

        <?php
        $sql = "SELECT * FROM setting";
        $result = $conn->query($sql);
        ?>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Loại (type)</th>
                    <th>Giá trị (value)</th>
                    <?php if ($current_user_role === 'admin'): ?>
                        <th>Thao tác</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['type']); ?></td>
                            <td><?php echo htmlspecialchars($row['value']); ?></td>
                            <?php if ($current_user_role === 'admin'): ?>
                                <td>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <!-- <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </a> -->
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?php echo ($current_user_role === 'admin') ? 4 : 3; ?>" class="text-center">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
