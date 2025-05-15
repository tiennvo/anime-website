<?php
session_start();
include('../dbconnect.php');

// Chỉ admin mới có quyền sửa setting
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

$message = "";
$message_type = "";

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $type = $conn->real_escape_string(trim($_POST['type']));
    $value = $conn->real_escape_string(trim($_POST['value']));

    if (isset($_POST['update'])) {
        if (!empty($type) && !empty($value)) {
            $sql = "UPDATE setting SET type='$type', value='$value' WHERE id=$id";
            if ($conn->query($sql) === TRUE) {
                $message = "Cài đặt đã được cập nhật thành công.";
                $message_type = "success";
                echo "<script>setTimeout(function() { window.location.href = 'index.php'; }, 2000);</script>";
            } else {
                $message = "Lỗi khi cập nhật: " . $conn->error;
                $message_type = "danger";
            }
        } else {
            $message = "Không được để trống trường nào.";
            $message_type = "warning";
        }
    } elseif (isset($_POST['delete'])) {
        $sql = "DELETE FROM setting WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            $message = "Cài đặt đã được xoá thành công.";
            $message_type = "success";
            echo "<script>setTimeout(function() { window.location.href = 'index.php'; }, 2000);</script>";
        } else {
            $message = "Lỗi khi xoá: " . $conn->error;
            $message_type = "danger";
        }
    }
}

// Lấy dữ liệu hiện tại
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM setting WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $type = $row['type'];
        $value = $row['value'];
    } else {
        $message = "Không tìm thấy cài đặt.";
        $message_type = "danger";
    }
} else {
    $message = "Thiếu ID cài đặt.";
    $message_type = "danger";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Cài đặt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content container mt-4">
        <h1>Sửa Cài đặt</h1>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Trở về</a>

        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (isset($type) && isset($value)): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label for="type">Loại (type)</label>
                <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($type); ?>" required>
            </div>
            <div class="form-group">
                <label for="value">Giá trị (value)</label>
                <input type="text" class="form-control" id="value" name="value" value="<?php echo htmlspecialchars($value); ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Cập nhật</button>
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá cài đặt này không?');"><i class="fas fa-trash-alt"></i> Xoá</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
