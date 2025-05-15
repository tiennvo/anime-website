<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Thể loại Phim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Thêm Thể loại Phim</h1>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Trở về</a>
        
        <?php
        include('../dbconnect.php');

        $message = "";
        $message_type = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $conn->real_escape_string($_POST['name']);

            if (!empty($name)) {
                $sql = "INSERT INTO genres (name) VALUES ('$name')";
                if ($conn->query($sql) === TRUE) {
                    $message = "Thể loại phim đã được thêm thành công.";
                    $message_type = "success";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'index.php';
                            }, 2000); // 2 giây để người dùng đọc thông báo
                          </script>";
                } else {
                    $message = "Có lỗi khi thêm thể loại phim: " . $conn->error;
                    $message_type = "danger";
                }
            } else {
                $message = "Tên thể loại không được để trống.";
                $message_type = "warning";
            }
        }
        
        if (!empty($message)) {
            echo "<div class='alert alert-$message_type'>$message</div>";
        }
        ?>

        <form action="create.php" method="post">
            <div class="form-group">
                <label for="name">Tên Thể loại</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
