<?php
session_start();
include_once 'dbconnect.php';
$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? htmlspecialchars($_SESSION['username']) : '';

// Lấy ID phim từ URL
$movie_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy ID người dùng từ session
$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
$is_purchased = false;

// Truy vấn thông tin phim
$movie_sql = "SELECT id, title, `description`, release_year, director, actors, genre, rating, trailer_url, `type`, video_url, image_url, country_id, money
              FROM movies WHERE id = ?";
$stmt = $conn->prepare($movie_sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();

// Nếu không có phim, chuyển hướng đến trang lỗi
if (!$movie) {
    header('Location: 404.php');
    exit();
}

// Truy vấn để lấy tên quốc gia
$country_sql = "SELECT name FROM countries WHERE id = ?";
$stmt = $conn->prepare($country_sql);
$stmt->bind_param("i", $movie['country_id']);
$stmt->execute();
$country = $stmt->get_result()->fetch_assoc()['name'];

// Truy vấn để lấy thể loại phim
$genre_sql = "SELECT g.name
              FROM genres g
              JOIN movie_genres mg ON g.id = mg.genre_id
              WHERE mg.movie_id = ?";
$stmt = $conn->prepare($genre_sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$genres_result = $stmt->get_result();
$genres = [];
while ($row = $genres_result->fetch_assoc()) {
    $genres[] = $row['name'];
}
// Truy vấn để lấy ID và tên của thể loại phim
$genre_sql = "SELECT g.id, g.name
              FROM genres g
              JOIN movie_genres mg ON g.id = mg.genre_id
              WHERE mg.movie_id = ?";
$stmt = $conn->prepare($genre_sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$genres_result = $stmt->get_result();
$genres = [];
$genre_ids = [];
while ($row = $genres_result->fetch_assoc()) {
    $genres[] = $row['name'];
    $genre_ids[] = $row['id']; // Lưu lại ID của thể loại
}

function genre_links($genres, $genre_ids) {
    $links = [];
    foreach ($genres as $index => $genre) {
        $genre_id = $genre_ids[$index]; // Lấy ID tương ứng với thể loại
        $links[] = '<a style="color: #69e0ff" href="phimtheotheloai.php?id=' . intval($genre_id) . '">' . htmlspecialchars($genre) . '</a>';
    }
    return implode(', ', $links);
}


// Xử lý lưu phim
if (isset($_POST['save_movie']) && $user_id) {
    $check_sql = "SELECT id FROM saved_movies WHERE user_id = ? AND movie_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $user_id, $movie_id);
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;

    if (!$exists) {
        $save_sql = "INSERT INTO saved_movies (user_id, movie_id, saved_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($save_sql);
        $stmt->bind_param("ii", $user_id, $movie_id);
        $stmt->execute();
    }
}

//xử lí mua phim
if (isset($_POST['buy_movie']) && $user_id && !$is_purchased) {
    // Lấy giá phim
    $price_sql = "SELECT money FROM movies WHERE id = ?";
    $stmt = $conn->prepare($price_sql);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $movie_price = ($row = $result->fetch_assoc()) ? (int)$row['money'] : 0;

    // Lấy số dư người dùng
    $balance_sql = "SELECT money FROM users WHERE id = ?";
    $stmt = $conn->prepare($balance_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_money = ($row = $result->fetch_assoc()) ? (int)$row['money'] : 0;

    // Kiểm tra đủ tiền
    if ($user_money >= $movie_price) {
        // Trừ tiền
        $update_sql = "UPDATE users SET money = money - ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $movie_price, $user_id);
        $stmt->execute();

        // Ghi nhận đã mua
        $buy_sql = "INSERT INTO purchased_movies (user_id, movie_id) VALUES (?, ?)";
        $stmt = $conn->prepare($buy_sql);
        $stmt->bind_param("ii", $user_id, $movie_id);
        $stmt->execute();

        // Ghi lịch sử giao dịch
        $dongtien_sql = "INSERT INTO dongtien (sotientruoc, sotienthaydoi, sotiensau, thoigian, noidung, username) 
        VALUES (?, ?, ?, NOW(), ?, ?)";
        $stmt = $conn->prepare($dongtien_sql);
        $noidung = "Mua phim: " . $movie['title'];
        $username = $_SESSION['username'];
        $sotiensau = $user_money - $movie_price;
        $stmt->bind_param("iiiss", $user_money, $movie_price, $sotiensau, $noidung, $username);
        $stmt->execute();

        // Cập nhật trạng thái đã mua
        $is_purchased = true;

        // Có thể thêm thông báo flash hoặc popup thành công ở đây
    } else {
        echo "<script>alert('Bạn không đủ tiền để mua phim này!');</script>";
    }
}



// Kiểm tra nếu phim đã được lưu
$check_saved_sql = "SELECT id FROM saved_movies WHERE user_id = ? AND movie_id = ?";
$stmt = $conn->prepare($check_saved_sql);
$stmt->bind_param("ii", $user_id, $movie_id);
$stmt->execute();
$is_saved = $stmt->get_result()->num_rows > 0;

// Kiểm tra nếu phim đã được mua
$check_purchase_sql = "SELECT id FROM purchased_movies WHERE user_id = ? AND movie_id = ?";
$stmt = $conn->prepare($check_purchase_sql);
$stmt->bind_param("ii", $user_id, $movie_id);
$stmt->execute();
$is_purchased = $stmt->get_result()->num_rows > 0;

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

        .trailer-button {
            margin-right: 10px;
        }

        .movie-details {
            list-style-type: none;
            padding: 0;
        }

        .movie-details li {
            margin-bottom: 10px;
        }

        .trailer-img {
            width: 160px;
            height: 160px;
        }

        .star-rating {
            font-size: 1.5rem;
        }

        .star-rating .fa-star {
            color: gold;
        }
        
        .trailer-video {
            width: 100%;
            height: 315px;
        }
    </style>
</head>

<body>
    <!-- header -->
    <?php include_once 'header.php'; ?>

    <div class="container my-5">
        <!-- Phần Video Trailer -->
        <div class="info-section" id="video-trailer">
            <div class="d-flex align-items-center">
                <img src="/anime/admin/view/img/<?php echo htmlspecialchars($movie['image_url']); ?>" alt="Phim" class="img-thumbnail trailer-img" style="margin-right: 20px;">
                <div>
                    <h4 class="guide__title"><?php echo htmlspecialchars($movie['title']); ?></h4>
                    <?php if ($is_purchased): ?>
                        <a href="playvideo.php?id=<?php echo $movie['id']; ?>" class="btn btn-primary trailer-button">
                            <i class="fas fa-play"></i> Xem Phim
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn btn-warning trailer-button" data-bs-toggle="modal" data-bs-target="#confirmBuyModal">
                            <i class="fas fa-shopping-cart"></i> Mua Phim
                        </button>
                    <?php endif; ?>

                    <a href="#trailer-section" class="btn btn-secondary trailer-button" data-bs-toggle="collapse">
                        <i class="fas fa-video"></i> Xem Trailer
                    </a>
                    <form action="info.php?id=<?php echo $movie['id']; ?>" method="POST" style="display: inline;">
                        <button type="submit" name="save_movie" class="btn <?php echo $is_saved ? 'btn-success' : 'btn-outline-primary'; ?>">
                            <i class="fas fa-bookmark"></i> <?php echo $is_saved ? 'Đã lưu' : 'Lưu Phim'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="section-divider"></div>

        <!-- Phần Đánh Giá -->
        <div class="info-section">
            <h3 class="guide__title">Đánh Giá</h3>
            <ul class="movie-details">
            <li><strong>Thể loại:</strong> <?php echo genre_links($genres, $genre_ids); ?></li>
                <li><strong>Năm phát hành:</strong> <?php echo htmlspecialchars($movie['release_year']); ?></li>
                <li><strong>Đạo diễn:</strong> <?php echo htmlspecialchars($movie['director']); ?></li>
                <li><strong>Quốc gia:</strong> 
                    <a href="phimtheoquocgia.php?id=<?php echo $movie['country_id']; ?>">
                        <?php echo htmlspecialchars($country); ?>
                    </a>
                </li>
                <li><strong>Diễn viên:</strong> <?php echo htmlspecialchars($movie['actors']); ?></li>
                <li><strong>Đánh giá:</strong> 
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
                </li>
            </ul>
        </div>

        <div class="section-divider"></div>

        <!-- Phần Nội dung -->
        <div class="info-section">
            <h3 class="guide__title">Nội dung</h3>
            <p><?php echo htmlspecialchars($movie['description']); ?></p>
        </div>

        <div class="section-divider"></div>

        <!-- Phần Video Trailer bổ sung -->
        <div class="info-section" id="trailer-section">
            <h3 class="guide__title">Video Trailer</h3>
            <?php if (!empty($movie['trailer_url'])): ?>
                <iframe class="trailer-video" src="<?php echo htmlspecialchars($movie['trailer_url']); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <?php else: ?>
                <p>Không có video trailer cho phim này.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Modal Xác nhận Mua Phim -->
    <div class="modal fade" id="confirmBuyModal" tabindex="-1" aria-labelledby="confirmBuyLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <form method="POST" action="info.php?id=<?php echo $movie['id']; ?>">
                <div class="modal-header">
                <h5 class="modal-title" style="color: black;" id="confirmBuyLabel">Xác nhận mua phim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body" style="color: black;">
                Bạn có chắc chắn muốn mua phim <strong style="color: red;"><?php echo htmlspecialchars($movie['title']); ?></strong> không?
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" name="buy_movie" class="btn btn-warning">Xác nhận mua</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>

</html>


</html>
