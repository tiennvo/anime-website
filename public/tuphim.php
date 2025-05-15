<?php
session_start();
include_once 'dbconnect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Lấy thông tin người dùng
$user_id = $_SESSION['user_id'];
$user_query = "SELECT username, email, password FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
if ($user_result === false) {
    die('Error executing query: ' . $stmt->error);
}
$user = $user_result->fetch_assoc();

// Lấy danh sách phim đã lưu
$saved_movies_query = "SELECT movies.id, movies.title, movies.image_url, movies.money, movies.total_views FROM saved_movies 
    JOIN movies ON saved_movies.movie_id = movies.id 
    WHERE saved_movies.user_id = ?";
$stmt = $conn->prepare($saved_movies_query);
if ($stmt === false) {
    die('Error preparing saved movies statement: ' . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$saved_movies_result = $stmt->get_result();
if ($saved_movies_result === false) {
    die('Error executing saved movies query: ' . $stmt->error);
}
$saved_movies = [];
if ($saved_movies_result->num_rows > 0) {
    while ($row = $saved_movies_result->fetch_assoc()) {
        $saved_movies[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'image_url' => '/anime/admin/view/img/' . $row['image_url'],
            'money' => $row['money'],
            'total_views' => $row['total_views'],
        ];
    }
}

// Lấy danh sách phim đã mua
$buy_movies_query = "SELECT movies.id, movies.title, movies.image_url, movies.money, movies.total_views FROM purchased_movies 
    JOIN movies ON purchased_movies.movie_id = movies.id 
    WHERE purchased_movies.user_id = ?";
$stmt = $conn->prepare($buy_movies_query);
if ($stmt === false) {
    die('Error preparing buy movies statement: ' . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$buy_movies_result = $stmt->get_result();
if ($buy_movies_result === false) {
    die('Error executing buy movies query: ' . $stmt->error);
}
$buy_movies = [];
if ($buy_movies_result->num_rows > 0) {
    while ($row = $buy_movies_result->fetch_assoc()) {
        $buy_movies[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'image_url' => '/anime/admin/view/img/' . $row['image_url'],
            'money' => $row['money'],
            'total_views' => $row['total_views'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tủ phim của bạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- header -->
    <?php include_once 'header.php'; ?>

    <div class="container my-5">
        <!-- Phim đã mua -->
        <div id="saved-movies" class="section mb-5">
            <h3 class="guide__title">Phim đã mua</h3>
            <div class="row">
                <?php if (!empty($buy_movies)): ?>
                    <?php foreach ($buy_movies as $movie): ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card">
                                <a href="info.php?id=<?php echo $movie['id']; ?>">
                                    <img src="<?php echo $movie['image_url']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h5>
                                    <p class="card-text">
                                        <img src="/anime/image/ngthach.png" style="all: unset; vertical-align: middle;; width: 25px;">
                                            Giá: <?php echo htmlspecialchars(format_cash($movie['money']));?>đ
                                        </p>
                                        <p class="card-text">Lượt xem: <?php echo htmlspecialchars($movie['total_views']);?></p>
                                        <a href="info.php?id=<?php echo $movie['id']; ?>" class="btn btn-primary">Xem ngay</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nạp tiền mua phim đi Bro !</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Phim đã lưu -->
        <div id="saved-movies" class="section mb-5">
            <h3 class="guide__title">Phim đã lưu</h3>
            <div class="row">
                <?php if (!empty($saved_movies)): ?>
                    <?php foreach ($saved_movies as $movie): ?>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card">
                                <a href="info.php?id=<?php echo $movie['id']; ?>">
                                    <img src="<?php echo $movie['image_url']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h5>
                                    <p class="card-text">
                                        <img src="/anime/image/ngthach.png" style="all: unset; vertical-align: middle;; width: 25px;">
                                            Giá: <?php echo htmlspecialchars(format_cash($movie['money']));?>đ
                                        </p>
                                        <p class="card-text">Lượt xem: <?php echo htmlspecialchars($movie['total_views']);?></p>
                                        <a href="info.php?id=<?php echo $movie['id']; ?>" class="btn btn-primary">Mua ngay</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không có phim nào được lưu.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
