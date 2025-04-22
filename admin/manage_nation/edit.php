<?php
session_start(); // Gọi session_start() ở đầu tập tin
include('../dbconnect.php');

// Kiểm tra và lấy vai trò người dùng từ session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

$message = "";
$message_type = "";

// Xử lý dữ liệu từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $id = intval($_POST['id']);
        $name = $conn->real_escape_string($_POST['name']);

        if (!empty($name)) {
            $sql = "UPDATE countries SET name='$name' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                $message = "Quốc gia đã được cập nhật thành công.";
                $message_type = "success";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 2000); // 2 giây để người dùng đọc thông báo
                      </script>";
            } else {
                $message = "Có lỗi khi cập nhật quốc gia: " . $conn->error;
                $message_type = "danger";
            }
        } else {
            $message = "Tên quốc gia không được để trống.";
            $message_type = "warning";
        }
    } elseif (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        $sql = "DELETE FROM countries WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            $message = "Quốc gia đã được xoá thành công.";
            $message_type = "success";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000); // 2 giây để người dùng đọc thông báo
                  </script>";
        } else {
            $message = "Có lỗi khi xoá quốc gia: " . $conn->error;
            $message_type = "danger";
        }
    }
}

// Lấy thông tin quốc gia từ cơ sở dữ liệu
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT name FROM countries WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
    } else {
        $message = "Quốc gia không tồn tại.";
        $message_type = "danger";
    }
} else {
    $message = "ID quốc gia không được xác định.";
    $message_type = "danger";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Quốc gia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Sửa Quốc gia</h1>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Trở về</a>

        <?php if (!empty($message)): ?>
            <div class='alert alert-<?php echo htmlspecialchars($message_type); ?>'><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (isset($name)): ?>
        <form action="edit.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="name">Tên Quốc gia</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá quốc gia này không?');"><i class="fas fa-trash"></i> Xoá</button>
        </form>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
