<?php
session_start();
include_once 'dbconnect.php';

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

// Truy vấn để lấy thông tin phim cập nhật mới nhất
$latest_movies_sql = "SELECT id, title, image_url, country_id, `type` FROM movies ORDER BY release_year DESC LIMIT 8";
$latest_movies_result = $conn->query($latest_movies_sql);
$latest_movies = [];
if ($latest_movies_result->num_rows > 0) {
    while ($row = $latest_movies_result->fetch_assoc()) {
        $latest_movies[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'image_url' => 'admin/view/img/' . $row['image_url'],
            'country' => isset($countries[$row['country_id']]) ? $countries[$row['country_id']] : 'Không xác định',
            'type' => $row['type']
        ];
    }
}

// Truy vấn để lấy thông tin phim
$movie_types = ['series' => 'Phim bộ mới cập nhật', 'movie' => 'Phim lẻ mới cập nhật'];
$movies = [];
foreach ($movie_types as $type_key => $type_name) {
    $movie_sql = "SELECT id, title, image_url, country_id FROM movies WHERE `type` = '$type_key' ORDER BY release_year DESC LIMIT 8";
    $movie_result = $conn->query($movie_sql);
    $movies[$type_key] = [];
    if ($movie_result->num_rows > 0) {
        while ($row = $movie_result->fetch_assoc()) {
            $movies[$type_key][] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'image_url' => 'admin/view/img/' . $row['image_url'],
                'country' => isset($countries[$row['country_id']]) ? $countries[$row['country_id']] : 'Không xác định'
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
    <title>Vạn Giới Phim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- header -->
    <?php include_once 'header.php'; ?>

    <!-- Nội dung chính -->
    <div class="container my-5">
        <!-- Phần Phim cập nhật mới nhất -->
        <div id="latest-movies" class="section mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text-warning">Phim cập nhật mới nhất</h3>
            </div>
            <div class="row">
                <?php foreach ($latest_movies as $movie): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card">
                            <a href="info.php?id=<?php echo $movie['id']; ?>">
                                <img src="<?php echo $movie['image_url']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="info.php?id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a>
                                </h5>
                                <p class="card-text">Quốc gia: 
                                    <a href="phimtheoquocgia.php?id=<?php echo array_search($movie['country'], $countries); ?>">
                                        <?php echo htmlspecialchars($movie['country']); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Phần Phim lẻ mới cập nhật -->
        <div id="phim-le" class="section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text-warning">Phim lẻ mới cập nhật</h3>
                <a href="phimle.php" class="btn btn-outline-warning">Xem tất cả</a>
            </div>
            <div class="row">
                <?php foreach ($movies['movie'] as $movie): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card">
                            <a href="info.php?id=<?php echo $movie['id']; ?>">
                                <img src="<?php echo $movie['image_url']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="info.php?id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a>
                                </h5>
                                <p class="card-text">Quốc gia: 
                                    <a href="phimtheoquocgia.php?id=<?php echo array_search($movie['country'], $countries); ?>">
                                        <?php echo htmlspecialchars($movie['country']); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Phần Phim bộ mới cập nhật -->
        <div id="phim-bo" class="section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="text-warning">Phim bộ mới cập nhật</h3>
                <a href="phimbo.php" class="btn btn-outline-warning">Xem tất cả</a>
            </div>
            <div class="row">
                <?php foreach ($movies['series'] as $movie): ?>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card">
                            <a href="info.php?id=<?php echo $movie['id']; ?>">
                                <img src="<?php echo $movie['image_url']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="info.php?id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a>
                                </h5>
                                <p class="card-text">Quốc gia: 
                                    <a href="phimtheoquocgia.php?id=<?php echo array_search($movie['country'], $countries); ?>">
                                        <?php echo htmlspecialchars($movie['country']); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
    <!-- Footer -->
    <?php include_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
