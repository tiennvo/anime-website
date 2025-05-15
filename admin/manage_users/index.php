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
    <title>Quản lý Người Dùng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var userRole = document.getElementById('userRole').textContent;
            if (userRole !== 'admin') {
                var actionButtons = document.querySelectorAll('.action-buttons');
                actionButtons.forEach(function(buttons) {
                    buttons.style.display = 'none';
                });
            }
        });
    </script>
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Danh sách Người Dùng</h1>

        <!-- Thẻ chứa vai trò người dùng -->
        <span id="userRole" style="display: none;"><?php echo htmlspecialchars($current_user_role); ?></span>

        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo username hoặc email..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
                </div>
            </div>
        </form>

        <?php
        // Lấy từ khóa tìm kiếm nếu có
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Cập nhật truy vấn SQL để lọc theo từ khóa tìm kiếm
        $sql = "SELECT id, username, email, role FROM users
                WHERE username LIKE '%" . $conn->real_escape_string($search) . "%' 
                OR email LIKE '%" . $conn->real_escape_string($search) . "%'";
        $result = $conn->query($sql);
        ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <?php if ($current_user_role === 'admin'): ?>
                        <th>Thao tác</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['role']}</td>";

                        // Hiển thị các nút thao tác nếu vai trò của người dùng hiện tại là admin
                        echo "<td class='action-buttons'>";
                        if ($current_user_role === 'admin') {
                            echo "<a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Chỉnh sửa Vai trò</a>
                                  <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Xóa</a>";
                        }
                        echo "</td></tr>";
                    }
                } else {
                    $colspan = ($current_user_role === 'admin') ? 4 : 3;
                    echo "<tr><td colspan='$colspan'>Không có dữ liệu</td></tr>";
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
