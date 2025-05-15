<?php
session_start();
include_once 'public/dbconnect.php';

// Xử lý thông báo
$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';

if ($error === 'username_exists') {
    $errorMsg = 'Tên tài khoản đã được sử dụng.';
} elseif ($error === 'email_exists') {
    $errorMsg = 'Email đã được sử dụng.';
} elseif ($error === 'registration_failed') {
    $errorMsg = 'Đăng ký không thành công. Vui lòng thử lại.';
} elseif ($success === 'registration') {
    $successMsg = 'Đăng ký thành công. Bạn có thể đăng nhập ngay.';
}

// Truy vấn để lấy thông tin quốc gia
$country_sql = "SELECT id, name FROM countries";
$country_result = $conn->query($country_sql);
$countries = [];
if ($country_result->num_rows > 0) {
    while ($row = $country_result->fetch_assoc()) {
        $countries[$row['id']] = $row['name'];
    }
}

// Truy vấn để lấy tổng số lượt xem của tất cả các tập phim theo mỗi bộ phim
$views_sql = "SELECT movie_id, SUM(views) AS total_views FROM episodes GROUP BY movie_id";
$views_result = $conn->query($views_sql);

// Kiểm tra nếu truy vấn thành công
if ($views_result) {
    // Kiểm tra xem có dữ liệu trả về không
    if ($views_result->num_rows > 0) {
        $views = [];
        while ($row = $views_result->fetch_assoc()) {
            $views[$row['movie_id']] = $row['total_views']; // Lưu tổng số views của mỗi bộ phim
        }
    } else {
        // Nếu không có dữ liệu trong bảng episodes
        echo "Không có dữ liệu lượt xem trong bảng episodes.";
    }
} else {
    // Nếu truy vấn không thành công
    echo "Lỗi truy vấn: " . $conn->error;
}
$update_sql = "
    UPDATE movies m
    JOIN (
        SELECT movie_id, SUM(views) AS total_views
        FROM episodes
        GROUP BY movie_id
    ) e ON m.id = e.movie_id
    SET m.total_views = e.total_views
";
$update_totalview = $conn->query($update_sql);


// Truy vấn để lấy thông tin phim cập nhật mới nhất
$latest_movies_sql = "SELECT id, title, image_url, country_id, `type`, money FROM movies ORDER BY release_year DESC LIMIT 8";
$latest_movies_result = $conn->query($latest_movies_sql);
$latest_movies = [];
if ($latest_movies_result->num_rows > 0) {
    while ($row = $latest_movies_result->fetch_assoc()) {
        $latest_movies[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'image_url' => 'admin/view/img/' . $row['image_url'],
            'country' => isset($countries[$row['country_id']]) ? $countries[$row['country_id']] : 'Không xác định',
            'type' => $row['type'],
            'views' => isset($views[$row['id']]) ? $views[$row['id']] : 0,
            'money' => $row['money']
        ];
    }
}

// Truy vấn để lấy thông tin phim
$movie_types = ['series' => 'Phim bộ mới cập nhật', 'movie' => 'Phim lẻ mới cập nhật'];
$movies = [];
foreach ($movie_types as $type_key => $type_name) {
    $movie_sql = "SELECT id, title, image_url, country_id, money FROM movies WHERE `type` = '$type_key' ORDER BY release_year DESC LIMIT 8";
    $movie_result = $conn->query($movie_sql);
    $movies[$type_key] = [];
    if ($movie_result->num_rows > 0) {
        while ($row = $movie_result->fetch_assoc()) {
            $movies[$type_key][] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'image_url' => 'admin/view/img/' . $row['image_url'],
                'country' => isset($countries[$row['country_id']]) ? $countries[$row['country_id']] : 'Không xác định',
                'views' => isset($views[$row['id']]) ? $views[$row['id']] : 0,
                'money' => $row['money']
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/style.css">
</head>

<body>
    <!-- header -->
    <?php include_once 'public/header.php';?>

    <!-- Nội dung chính -->
    <div class="container my-5">
        <!-- Phần Phim cập nhật mới nhất -->
        <div id="latest-movies" class="section mb-5">
            <center style="text-align: -webkit-center;">
                    <img src="image/gi3.png" class="city__icon" style="vertical-align: middle; border-style: none;">
                </center>
            <div class="d-flex justify-content-center align-items-center mb-3">
                <h3 class="guide__title">Phim cập nhật mới nhất</h3>
            </div>
            <div class="row">
                <?php foreach ($latest_movies as $movie): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card">
                            <a href="public/info.php?id=<?php echo $movie['id']; ?>">
                                <img src="<?php echo $movie['image_url']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="public/info.php?id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a>
                                </h5>
                                <p class="card-text">Quốc gia: 
                                    <a href="public/phimtheoquocgia.php?id=<?php echo array_search($movie['country'], $countries); ?>">
                                        <?php echo htmlspecialchars($movie['country']); ?>
                                    </a>
                                </p>
                                
                                <p class="card-text">
                                <img src="image/ngthach.png" style="all: unset; vertical-align: middle;; width: 25px;">
                                    Giá: <?php echo htmlspecialchars(format_cash($movie['money']));?>đ
                                </p>
                                <p class="card-text">Lượt xem: <?php echo htmlspecialchars($movie['views']);?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Phần Phim lẻ mới cập nhật -->
        <div id="phim-le" class="section">
        <center style="text-align: -webkit-center;">
                    <img src="image/gi2.png" class="city__icon" style="vertical-align: middle; border-style: none;">
                </center>
            <div class="d-flex justify-content-center align-items-center mb-3">
                <a href="public/phimle.php" class="guide__title">Phim lẻ mới cập nhật</a>
            </div>
            <div class="row">
                <?php foreach ($movies['movie'] as $movie): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card">
                            <a href="public/info.php?id=<?php echo $movie['id']; ?>">
                                <img src="<?php echo $movie['image_url']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="public/info.php?id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a>
                                </h5>
                                <p class="card-text">Quốc gia: 
                                    <a href="public/phimtheoquocgia.php?id=<?php echo array_search($movie['country'], $countries); ?>">
                                        <?php echo htmlspecialchars($movie['country']); ?>
                                    </a>
                                </p>
                                <p class="card-text">
                                <img src="image/ngthach.png" style="all: unset; vertical-align: middle;; width: 25px;">
                                    Giá: <?php echo htmlspecialchars(format_cash($movie['money']));?>đ
                                </p>
                                <p class="card-text">Lượt xem: <?php echo htmlspecialchars($movie['views']);?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Phần Phim bộ mới cập nhật -->
        <div id="phim-bo" class="section">
        <center style="text-align: -webkit-center;">
                    <img src="image/gi1.png" class="city__icon" style="vertical-align: middle; border-style: none;">
                </center>
            <div class="d-flex justify-content-center align-items-center mb-3">
                <a href="public/phimbo.php" class="guide__title">Phim bộ mới cập nhật</a>
            </div>
            <div class="row">
                <?php foreach ($movies['series'] as $movie): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card">
                            <a href="public/info.php?id=<?php echo $movie['id']; ?>">
                                <img src="<?php echo $movie['image_url']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="public/info.php?id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a>
                                </h5>
                                <p class="card-text">Quốc gia: 
                                    <a href="public/phimtheoquocgia.php?id=<?php echo array_search($movie['country'], $countries); ?>">
                                        <?php echo htmlspecialchars($movie['country']); ?>
                                    </a>
                                </p>
                                <p class="card-text">
                                <img src="image/ngthach.png" style="all: unset; vertical-align: middle;; width: 25px;">
                                    Giá: <?php echo htmlspecialchars(format_cash($movie['money']));?>đ
                                </p>
                                <p class="card-text">Lượt xem: <?php echo htmlspecialchars($movie['views']);?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
    <!-- Footer -->
    <?php include_once 'public/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
