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

// Lấy danh sách phim bộ
$sql_movies = "SELECT id, title FROM movies WHERE type = 'series'";
$result_movies = $conn->query($sql_movies);

if ($result_movies === FALSE) {
    die("Lỗi truy vấn: " . $conn->error);
}

// Chọn một phim bộ để hiển thị
$selected_movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

// Nếu không có phim bộ được chọn, chọn phim bộ đầu tiên
if ($selected_movie_id == 0 && $result_movies->num_rows > 0) {
    $first_movie = $result_movies->fetch_assoc();
    $selected_movie_id = $first_movie['id'];
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Tập Phim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Quản lý Tập Phim</h1>
        <?php if ($current_user_role == 'admin' || $current_user_role == 'trans'): ?>
            <a href="create.php?movie_id=<?php echo $selected_movie_id; ?>" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Thêm Tập Phim</a>
        <?php endif; ?>

        <!-- Danh sách phim bộ -->
        <div class="form-group">
            <label for="movie_select">Chọn Phim Bộ:</label>
            <select class="form-control" id="movie_select" onchange="window.location.href='index.php?movie_id=' + this.value;">
                <?php
                // Reset the result set pointer
                $result_movies->data_seek(0);
                while ($row = $result_movies->fetch_assoc()) {
                    $selected = ($row['id'] == $selected_movie_id) ? 'selected' : '';
                    echo "<option value='{$row['id']}' $selected>{$row['title']}</option>";
                }
                ?>
            </select>
        </div>

        <?php
        // Lấy danh sách tập phim của phim bộ được chọn
        $sql_episodes = "SELECT id, episode_number, video_url FROM episodes WHERE movie_id = $selected_movie_id";
        $result_episodes = $conn->query($sql_episodes);

        if ($result_episodes === FALSE) {
            die("Lỗi truy vấn: " . $conn->error);
        }
        ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Số tập</th>
                    <th>Video</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_episodes->num_rows > 0) {
                    while($row = $result_episodes->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['episode_number']}</td>
                                <td>
                                    <button class='btn btn-primary' data-toggle='modal' data-target='#videoModal{$row['id']}'>Xem Video</button>
                                    <!-- Modal -->
                                    <div class='modal fade' id='videoModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='exampleModalLabel'>Xem Video</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <video width='100%' controls>
                                                        <source src='{$row['video_url']}' type='video/mp4'>
                                                        Trình duyệt của bạn không hỗ trợ video tag.
                                                    </video>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Đóng</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Sửa</a>
                                    <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Xóa</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Không có tập phim nào</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // Nếu không có phim bộ nào được chọn
        if ($selected_movie_id == 0) {
            echo "<div class='alert alert-info'>Vui lòng chọn một phim bộ từ danh sách trên để xem các tập phim.</div>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
