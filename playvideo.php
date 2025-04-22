<?php
session_start();
include_once 'dbconnect.php';

// Lấy ID phim từ URL
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn thông tin phim
$movie_sql = "SELECT id, title, description, release_year, director, actors, genre, rating, trailer_url, type, video_url, image_url, country_id
              FROM movies WHERE id = ?";
$stmt = $conn->prepare($movie_sql);
if ($stmt === false) {
    die('Lỗi trong câu lệnh SQL (movies): ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $movie_id);
if (!$stmt->execute()) {
    die('Lỗi khi thực thi câu lệnh SQL (movies): ' . htmlspecialchars($stmt->error));
}
$movie = $stmt->get_result()->fetch_assoc();

// Nếu không có phim, chuyển hướng đến trang lỗi
if (!$movie) {
    header('Location: 404.php');
    exit();
}

// Khởi tạo biến để lưu danh sách tập phim và video hiện tại
$episodes = [];
$current_video_url = $movie['video_url']; // Video mặc định

if ($movie['type'] === 'series') {
    $episodes_sql = "SELECT id, episode_number, video_url 
                     FROM episodes 
                     WHERE movie_id = ?
                     ORDER BY episode_number ASC";
    $stmt = $conn->prepare($episodes_sql);
    if ($stmt === false) {
        die('Lỗi trong câu lệnh SQL (episodes): ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $movie_id);
    if (!$stmt->execute()) {
        die('Lỗi khi thực thi câu lệnh SQL (episodes): ' . htmlspecialchars($stmt->error));
    }
    $episodes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Nếu có ít nhất một tập, chọn video của tập 1
    if (!empty($episodes)) {
        $current_video_url = $episodes[0]['video_url'];
    }
}

// Truy vấn bình luận
$comments_sql = "SELECT c.comment, u.username 
                 FROM comments c
                 JOIN users u ON c.user_id = u.id
                 WHERE c.movie_id = ?";
$stmt = $conn->prepare($comments_sql);
if ($stmt === false) {
    die('Lỗi trong câu lệnh SQL (comments): ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $movie_id);
if (!$stmt->execute()) {
    die('Lỗi khi thực thi câu lệnh SQL (comments): ' . htmlspecialchars($stmt->error));
}
$comments_result = $stmt->get_result();

// Truy vấn phim mới cập nhật
$new_movies_sql = "SELECT id, title, image_url 
                   FROM movies 
                   WHERE type = 'movie'
                   ORDER BY release_year DESC 
                   LIMIT 4";
$new_movies_result = $conn->query($new_movies_sql);
if (!$new_movies_result) {
    die('Lỗi trong câu lệnh SQL (new movies): ' . htmlspecialchars($conn->error));
}

// Truy vấn các bộ mới cập nhật
$new_series_sql = "SELECT id, title, image_url 
                   FROM movies 
                   WHERE type = 'series'
                   ORDER BY release_year DESC 
                   LIMIT 4";
$new_series_result = $conn->query($new_series_sql);
if (!$new_series_result) {
    die('Lỗi trong câu lệnh SQL (new series): ' . htmlspecialchars($conn->error));
}
?>




<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .section-divider {
            border-top: 2px solid #ddd;
            margin: 20px 0;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .star-rating {
            font-size: 1.5rem;
        }

        .star-rating .fa-star {
            color: gold;
        }

        .comment-section textarea {
            width: 100%;
            height: 100px;
        }

        .related-movies img {
            width: 120px;
            height: 180px;
        }

        .video-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .video-container iframe {
            width: 100%;
            max-width: 800px;
            height: 450px;
        }

        .video-controls {
            text-align: center;
            margin: 10px 0;
        }

        .video-controls button {
            margin: 0 5px;
        }

        .dark-mode {
            background-color: #000;
            color: #fff;
        }

        .expand-video {
            width: 100% !important;
            max-width: 1200px !important;
            height: 600px !important;
        }

        .episode-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            /* Giảm khoảng cách giữa các tập phim */
            padding: 5px;
            /* Giảm padding của khung */
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .episode-item {
            flex: 0 1 auto;
            /* Điều chỉnh flex để kích thước phù hợp với nội dung */
            padding: 5px 10px;
            /* Giảm padding bên trong mỗi tập phim */
            background-color: #f8f9fa;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            white-space: nowrap;
            /* Ngăn không cho nội dung bị gãy dòng */
            overflow: hidden;
            /* Ẩn phần nội dung vượt quá kích thước khung */
            text-overflow: ellipsis;
            /* Thêm dấu "..." nếu nội dung quá dài */
        }

        .episode-item a {
            display: block;
            /* Đảm bảo link chiếm toàn bộ diện tích khung */
            text-decoration: none;
            /* Xóa gạch chân của liên kết */
            color: #000;
            /* Màu chữ của liên kết */
        }

        .episode-item:hover {
            background-color: #e9ecef;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php include_once 'header.php'; ?>

    <div class="container my-5">
        <!-- Phần Video -->
        <div class="info-section video-container">
            <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe id="videoPlayer" class="embed-responsive-item" src="<?php echo htmlspecialchars($current_video_url); ?>" allowfullscreen></iframe>
            </div>
        </div>

        <!-- Phần danh sách tập phim -->
        <?php if ($movie['type'] === 'series' && !empty($episodes)): ?>
            <div class="info-section">
                <h3>Danh Sách Tập Phim</h3>
                <div class="episode-list">
                    <?php foreach ($episodes as $episode): ?>
                        <div class="episode-item">
                            <a href="#" class="episode-link" data-video-url="<?php echo htmlspecialchars($episode['video_url']); ?>">
                                Tập <?php echo htmlspecialchars($episode['episode_number']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Video Controls -->
        <div class="video-controls">
            <button id="autoPlayToggle" class="btn btn-outline-secondary">Tự động chuyển tập: On</button>
            <button id="expandToggle" class="btn btn-outline-secondary">Mở rộng</button>
            <button id="darkModeToggle" class="btn btn-outline-secondary">Tắt đèn</button>
            <button id="errorReport" class="btn btn-outline-danger">Báo lỗi</button>
        </div>
    </div>

    <div class="section-divider"></div>

    <!-- Phần Tên Phim - Nội dung - Đánh giá -->
    <div class="info-section">
        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
        <p><strong>Nội dung:</strong> <?php echo htmlspecialchars($movie['description']); ?></p>
        <p><strong>Đánh giá:</strong>
        <div class="star-rating">
            <?php
            $rating = $movie['rating'];
            for ($i = 0; $i < floor($rating); $i++) {
                echo '<i class="fas fa-star"></i>';
            }
            if ($rating - floor($rating) >= 0.5) {
                echo '<i class="fas fa-star-half-alt"></i>';
            }
            for ($i = ceil($rating); $i < 5; $i++) {
                echo '<i class="far fa-star"></i>';
            }
            ?>
        </div>
        </p>
    </div>

    <div class="section-divider"></div>

    <!-- Phần Bình luận -->
    <div class="info-section">
        <h3>Bình luận</h3>
        <div class="comment-section">
            <form method="POST" action="add_comment.php">
                <div class="mb-3">
                    <textarea class="form-control" name="comment" placeholder="Viết bình luận của bạn..."></textarea>
                </div>
                <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                <button type="submit" class="btn btn-primary">Gửi Bình luận</button>
            </form>
            <!-- Hiển thị các bình luận -->
            <div class="mt-3">
                <h5>Bình luận gần đây:</h5>
                <?php while ($comment = $comments_result->fetch_assoc()) : ?>
                    <div class="border p-2 mb-2">
                        <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment']); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <div class="section-divider"></div>

    <!-- Phần Phim Mới Cập Nhật -->
    <div class="info-section">
        <h3>Phim Lẻ Mới Cập Nhật</h3>
        <div class="row">
            <?php while ($new_movie = $new_movies_result->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="admin/view/img/<?php echo htmlspecialchars($new_movie['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($new_movie['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($new_movie['title']); ?></h5>
                            <a href="playvideo.php?id=<?php echo $new_movie['id']; ?>" class="btn btn-primary">Xem ngay</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="section-divider"></div>

    <!-- Phần Series Mới Cập Nhật -->
    <div class="info-section">
        <h3>Phim Bộ Mới Cập Nhật</h3>
        <div class="row">
            <?php while ($new_series = $new_series_result->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card">
                        <img src="admin/view/img/<?php echo htmlspecialchars($new_series['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($new_series['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($new_series['title']); ?></h5>
                            <a href="playvideo.php?id=<?php echo $new_series['id']; ?>" class="btn btn-primary">Xem ngay</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Modal báo lỗi -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Báo lỗi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có muốn báo cáo lỗi không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary">Gửi báo cáo</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const autoPlayToggle = document.getElementById('autoPlayToggle');
    const expandToggle = document.getElementById('expandToggle');
    const darkModeToggle = document.getElementById('darkModeToggle');
    const errorReport = document.getElementById('errorReport');
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    const videoPlayer = document.getElementById('videoPlayer');

    // Toggle tự động chuyển tập
    autoPlayToggle.addEventListener('click', function() {
        const isOn = autoPlayToggle.textContent.includes('On');
        autoPlayToggle.textContent = `Tự động chuyển tập: ${isOn ? 'Off' : 'On'}`;
    });

    // Toggle mở rộng video
    expandToggle.addEventListener('click', function() {
        videoPlayer.classList.toggle('expand-video');
    });

    // Toggle chế độ tối
    darkModeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
    });

    // Mở modal báo lỗi
    errorReport.addEventListener('click', function() {
        errorModal.show();
    });

    // Thay đổi video khi chọn tập phim
    document.querySelectorAll('.episode-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const videoUrl = this.getAttribute('data-video-url');
            videoPlayer.src = videoUrl;
        });
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const episodeLinks = document.querySelectorAll('.episode-link');
    episodeLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết
            const videoUrl = this.getAttribute('data-video-url');
            const videoPlayer = document.getElementById('videoPlayer');
            videoPlayer.src = videoUrl;
        });
    });
});

</script>


</body>

</html>