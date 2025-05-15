<?php
session_start(); // Gọi session_start() ở đầu tập tin
include('../dbconnect.php');

// Kiểm tra và lấy vai trò người dùng từ session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập trang này.</div>";
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
    <title>Sửa Thể loại Phim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Sửa Thể loại Phim</h1>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Trở về</a>

        <?php
        $message = "";
        $message_type = "";

        // Xử lý dữ liệu từ form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = intval($_POST['id']);
            $name = $conn->real_escape_string($_POST['name']);

            if (!empty($name)) {
                $sql = "UPDATE genres SET name='$name' WHERE id=$id";
                if ($conn->query($sql) === TRUE) {
                    $message = "Thể loại phim đã được cập nhật thành công.";
                    $message_type = "success";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'index.php';
                            }, 2000); // 2 giây để người dùng đọc thông báo
                          </script>";
                } else {
                    $message = "Có lỗi khi cập nhật thể loại phim: " . $conn->error;
                    $message_type = "danger";
                }
            } else {
                $message = "Tên thể loại không được để trống.";
                $message_type = "warning";
            }
        }

        // Lấy thông tin thể loại từ cơ sở dữ liệu
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT name FROM genres WHERE id=$id";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $name = $row['name'];
            } else {
                $message = "Thể loại không tồn tại.";
                $message_type = "danger";
            }
        } else {
            $message = "ID thể loại không được xác định.";
            $message_type = "danger";
        }

        if (!empty($message)) {
            echo "<div class='alert alert-$message_type'>$message</div>";
        }
        ?>

        <?php if (isset($name)): ?>
        <form action="edit.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="name">Tên Thể loại</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
