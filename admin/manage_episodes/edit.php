<?php
session_start(); // Gọi session_start() ở đầu tập tin
include('../dbconnect.php');

// Kiểm tra và lấy vai trò người dùng từ session
if (!isset($_SESSION['role'])) {
    echo "<div class='alert alert-danger'>Vai trò của bạn chưa được thiết lập. Vui lòng đăng nhập lại.</div>";
    exit();
}

$current_user_role = $_SESSION['role'];
if ($current_user_role != 'admin' && $current_user_role != 'trans') {
    echo "<div class='alert alert-danger'>Bạn không có quyền truy cập trang này.</div>";
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit();
}

// Xử lý khi gửi biểu mẫu
$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $episode_id = intval($_POST['episode_id']);
    $movie_id = intval($_POST['movie_id']);
    $episode_number = intval($_POST['episode_number']);
    
    // Xử lý video_url để chỉ lấy URL từ iframe
    $video_url_raw = !empty($_POST['video_url']) ? $_POST['video_url'] : NULL;
    $video_url = NULL;
    if ($video_url_raw) {
        // Sử dụng biểu thức chính quy để trích xuất URL từ iframe
        if (preg_match('/src=["\'](.*?)["\']/', $video_url_raw, $matches)) {
            $video_url = $matches[1]; // Lấy URL từ nhóm 1 của biểu thức chính quy
        }
    }

    if (!empty($episode_id) && !empty($movie_id) && !empty($episode_number)) {
        // Cập nhật tập phim vào cơ sở dữ liệu
        $sql = "UPDATE episodes SET movie_id = $movie_id, episode_number = $episode_number, video_url = '$video_url'
                WHERE id = $episode_id";

        if ($conn->query($sql) === TRUE) {
            $message = "Tập phim đã được cập nhật thành công.";
            $message_type = "success";
            echo "<script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000); // 2 giây để người dùng đọc thông báo
          </script>";
        } else {
            $message = "Có lỗi khi cập nhật tập phim: " . $conn->error;
            $message_type = "danger";
        }
    } else {
        $message = "Vui lòng điền đầy đủ thông tin.";
        $message_type = "warning";
    }
}

// Lấy thông tin tập phim hiện tại
$episode_id = intval($_GET['id']);
$sql_episode = "SELECT * FROM episodes WHERE id = $episode_id";
$result_episode = $conn->query($sql_episode);
$episode = $result_episode->fetch_assoc();

if (!$episode) {
    echo "<div class='alert alert-danger'>Tập phim không tồn tại.</div>";
    exit();
}

if (!empty($message)) {
    echo "<div class='alert alert-$message_type'>$message</div>";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Tập Phim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Chỉnh Sửa Tập Phim</h1>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Trở về</a>

        <form action="edit.php?id=<?php echo $episode_id; ?>" method="post">
            <input type="hidden" name="episode_id" value="<?php echo $episode_id; ?>">
            <div class="form-group">
                <label for="movie_id">Chọn Phim</label>
                <select class="form-control" id="movie_id" name="movie_id" required>
                    <option value="">Chọn Phim</option>
                    <?php
                    // Lấy danh sách phim bộ từ cơ sở dữ liệu
                    $sql_movies = "SELECT id, title FROM movies WHERE type = 'series'";
                    $result_movies = $conn->query($sql_movies);
                    if ($result_movies->num_rows > 0) {
                        while ($row = $result_movies->fetch_assoc()) {
                            $selected = $row['id'] == $episode['movie_id'] ? 'selected' : '';
                            echo "<option value='{$row['id']}' $selected>{$row['title']}</option>";
                        }
                    } else {
                        echo "<option value=''>Không có phim bộ</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="episode_number">Số Tập</label>
                <input type="number" class="form-control" id="episode_number" name="episode_number" value="<?php echo $episode['episode_number']; ?>" required>
            </div>
            <div class="form-group">
                <label for="video_url">URL Video</label>
                <input type="text" class="form-control" id="video_url" name="video_url" value="<?php echo htmlspecialchars($episode['video_url']); ?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
