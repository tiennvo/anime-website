<?php
session_start();
include('../dbconnect.php');

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
    <title>Thêm Phim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../view/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var typeSelect = document.getElementById('type');
            var videoUrlGroup = document.getElementById('videoUrlGroup');

            function toggleVideoUrlField() {
                if (typeSelect.value === 'movie') {
                    videoUrlGroup.style.display = 'block';
                } else {
                    videoUrlGroup.style.display = 'none';
                }
            }

            toggleVideoUrlField();
            typeSelect.addEventListener('change', toggleVideoUrlField);
        });
    </script>
</head>

<body>
    <?php include_once('../sidebar.php'); ?>
    <div class="content">
        <h1>Thêm Phim</h1>
        <a href="index.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Trở về</a>

        <?php
include('../dbconnect.php');

$message = "";
$message_type = "";

$sql_genres = "SELECT id, name FROM genres";
$result_genres = $conn->query($sql_genres);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $release_year = intval($_POST['release_year']);
    $director = $conn->real_escape_string($_POST['director']);
    $actors = $conn->real_escape_string($_POST['actors']);
    $rating = floatval($_POST['rating']);
    
    // Xử lý trailer_url để chỉ lấy URL từ iframe
    $trailer_url_raw = !empty($_POST['trailer_url']) ? $_POST['trailer_url'] : NULL;
    $trailer_url = NULL;
    if ($trailer_url_raw) {
        // Sử dụng biểu thức chính quy để trích xuất URL từ iframe
        if (preg_match('/src=["\'](.*?)["\']/', $trailer_url_raw, $matches)) {
            $trailer_url = $matches[1]; // Lấy URL từ nhóm 1 của biểu thức chính quy
        }
    }

    // Xử lý video_url để chỉ lấy URL từ iframe
    $video_url_raw = !empty($_POST['video_url']) ? $_POST['video_url'] : NULL;
    $video_url = NULL;
    if ($video_url_raw) {
        // Sử dụng biểu thức chính quy để trích xuất URL từ iframe
        if (preg_match('/src=["\'](.*?)["\']/', $video_url_raw, $matches)) {
            $video_url = $matches[1]; // Lấy URL từ nhóm 1 của biểu thức chính quy
        }
    }

    $country_id = intval($_POST['country_id']);
    $type = $conn->real_escape_string($_POST['type']);
    $money = $conn->real_escape_string($_POST['money']);
    if (!empty($title) && !empty($description) && !empty($release_year) && !empty($director) && !empty($actors) && !empty($rating) && !empty($country_id) && !empty($type)) {
        $image_url = "";
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../view/img/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $message = "Chỉ cho phép tải lên hình ảnh JPG, JPEG, PNG & GIF.";
                $message_type = "warning";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $message = "Hình ảnh không được tải lên.";
                $message_type = "danger";
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image_url = basename($_FILES["image"]["name"]);
                } else {
                    $message = "Có lỗi khi tải lên hình ảnh.";
                    $message_type = "danger";
                }
            }
        }

        if (!empty($image_url) || !empty($video_url)) {
            $sql = "INSERT INTO movies (title, description, release_year, director, actors, rating, trailer_url, video_url, image_url, country_id, type, money)
            VALUES ('$title', '$description', $release_year, '$director', '$actors', $rating, '$trailer_url', '$video_url', '$image_url', $country_id, '$type', '$money')";

            if ($conn->query($sql) === TRUE) {
                $movie_id = $conn->insert_id;

                if (!empty($_POST['genres'])) {
                    foreach ($_POST['genres'] as $genre_id) {
                        $sql_genre = "INSERT INTO movie_genres (movie_id, genre_id) VALUES ($movie_id, $genre_id)";
                        $conn->query($sql_genre);
                    }
                }

                $message = "Phim đã được thêm thành công.";
                $message_type = "success";
            } else {
                $message = "Có lỗi khi thêm phim: " . $conn->error;
                $message_type = "danger";
            }
        } else {
            $message = "Vui lòng chọn hình ảnh hoặc cập nhật video.";
            $message_type = "warning";
        }
    } else {
        $message = "Vui lòng điền đầy đủ thông tin.";
        $message_type = "warning";
    }
}

if (!empty($message)) {
    echo "<div class='alert alert-$message_type'>$message</div>";
}
?>

        <form action="create.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Mô tả</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="release_year">Năm phát hành</label>
                <input type="number" class="form-control" id="release_year" name="release_year" required>
            </div>
            <div class="form-group">
                <label for="director">Đạo diễn</label>
                <input type="text" class="form-control" id="director" name="director" required>
            </div>
            <div class="form-group">
                <label for="actors">Diễn viên</label>
                <input type="text" class="form-control" id="actors" name="actors" required>
            </div>
            <div class="form-group">
                <label for="rating">Xếp hạng</label>
                <input type="number" step="0.1" class="form-control" id="rating" name="rating" required>
            </div>
            <div class="form-group">
                <label for="trailer_url">URL Trailer</label>
                <input type="text" class="form-control" id="trailer_url" name="trailer_url">
            </div>
            <div class="form-group" id="videoUrlGroup">
                <label for="video_url">URL Video</label>
                <input type="text" class="form-control" id="video_url" name="video_url">
            </div>
            <div class="form-group">
                <label for="image">Hình ảnh</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="country_id">Quốc gia</label>
                <select class="form-control" id="country_id" name="country_id" required>
                    <?php
                    $sql_countries = "SELECT id, name FROM countries";
                    $result_countries = $conn->query($sql_countries);
                    if ($result_countries->num_rows > 0) {
                        while ($row = $result_countries->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Thể loại</label>
                <div>
                    <?php
                    if ($result_genres->num_rows > 0) {
                        while ($row = $result_genres->fetch_assoc()) {
                            echo "<div class='form-check'>
                                    <input type='checkbox' class='form-check-input' id='genre{$row['id']}' name='genres[]' value='{$row['id']}'>
                                    <label class='form-check-label' for='genre{$row['id']}'>{$row['name']}</label>
                                  </div>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="type">Loại</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="movie">Phim lẻ</option>
                    <option value="series">Phim bộ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="money">Giá tiền</label>
                <input type="text" class="form-control" id="money" name="money" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Phim</button>
        </form>
    </div>

</body>

</html>