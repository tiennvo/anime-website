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

// Kiểm tra nếu có từ khóa tìm kiếm và gán giá trị cho biến
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Cập nhật truy vấn SQL để lọc theo từ khóa tìm kiếm
$sql = "SELECT id, title, `description`, release_year, director, actors, genre, rating, trailer_url, `type`, video_url, image_url, country_id 
        FROM movies 
        WHERE title LIKE '%" . $conn->real_escape_string($search) . "%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Phim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
</head>
<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Danh sách Phim</h1>
        <?php if ($current_user_role == 'admin' || $current_user_role == 'trans'): ?>
            <a href="create.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Thêm Phim</a>
        <?php endif; ?>

        <form method="GET" action="" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tiêu đề..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Mô tả</th>
                    <th>Rộng năm</th>
                    <th>Đạo diễn</th>
                    <th>Diễn viên</th>
                    <th>Thể loại</th>
                    <th>Đánh giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $description = htmlspecialchars($row['description']);
                        $trailer_url = htmlspecialchars($row['trailer_url']);
                        $video_url = htmlspecialchars($row['video_url']);
                        $type = $row['type'] === 'series' ? 'Phim Bộ' : 'Phim Lẻ';
                        
                        echo "<tr>
                                <td><img src='../view/img/{$row['image_url']}' alt='Image' style='width: 100px; height: auto;'></td>
                                <td>{$row['title']}</td>
                                <td>
                                    <button class='btn btn-info btn-sm' data-toggle='modal' data-target='#descriptionModal{$row['id']}'>Xem Mô Tả</button>
                                    <div class='modal fade' id='descriptionModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='descriptionModalLabel{$row['id']}' aria-hidden='true'>
                                        <div class='modal-dialog' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='descriptionModalLabel{$row['id']}'>Mô Tả</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    $description
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{$row['release_year']}</td>
                                <td>{$row['director']}</td>
                                <td>{$row['actors']}</td>
                                <td>$type</td>
                                <td>{$row['rating']}</td>
                                <td>
                                    <a href='edit.php?id={$row['id']}' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Sửa</a>
                                    <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Xóa</a>
                                    ". ($trailer_url ? "<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#trailerModal{$row['id']}'>Xem Trailer</button>" : "") ."
                                    ". ($video_url ? "<button class='btn btn-success btn-sm' data-toggle='modal' data-target='#videoModal{$row['id']}'>Xem Video</button>" : "") ."
                                    <div class='modal fade' id='trailerModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='trailerModalLabel{$row['id']}' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='trailerModalLabel{$row['id']}'>Xem Trailer</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <video controls width='100%'>
                                                        <source src='$trailer_url' type='video/mp4'>
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='modal fade' id='videoModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='videoModalLabel{$row['id']}' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered' role='document'>
                                            <div class='modal-content'>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='videoModalLabel{$row['id']}'>Xem Video</h5>
                                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                        <span aria-hidden='true'>&times;</span>
                                                    </button>
                                                </div>
                                                <div class='modal-body'>
                                                    <video controls width='100%'>
                                                        <source src='$video_url' type='video/mp4'>
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Không có dữ liệu</td></tr>";
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
