<?php
include_once 'dbconnect.php';

// Xác định số phim trên mỗi trang
$moviesPerPage = 12;

// Lấy số trang từ URL hoặc mặc định là trang 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $moviesPerPage;

// Lấy id thể loại từ URL và bảo mật truy vấn
$genreId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$genreId = mysqli_real_escape_string($conn, $genreId);

// Truy vấn để lấy tên thể loại từ bảng genres
$genre_sql = "SELECT name FROM genres WHERE id = ?";
$stmt = $conn->prepare($genre_sql);
if ($stmt === false) {
    die('Lỗi trong câu lệnh SQL (genre): ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $genreId);
if (!$stmt->execute()) {
    die('Lỗi khi thực thi câu lệnh SQL (genre): ' . htmlspecialchars($stmt->error));
}
$genre_result = $stmt->get_result();

// Kiểm tra nếu có kết quả và lấy tên thể loại
$genreName = '';
if ($genre_row = $genre_result->fetch_assoc()) {
    $genreName = $genre_row['name'];
}

// Truy vấn phim theo thể loại
$query = "SELECT m.id, m.title, m.description, m.release_year, m.director, m.actors, m.genre, m.rating, m.trailer_url, m.type, m.video_url, m.image_url, m.money, m.total_views, c.name AS country_name
          FROM movies m
          JOIN movie_genres mg ON m.id = mg.movie_id
          JOIN genres g ON mg.genre_id = g.id
          JOIN countries c ON m.country_id = c.id
          WHERE g.id = ? 
          GROUP BY m.id
          LIMIT $offset, $moviesPerPage";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $genreId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phim Theo Thể Loại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styleimg.css">
</head>

<body>
    <!-- header -->
    <?php include_once 'header.php'; ?>

    <div class="container my-5">
        <div class="row">
            <!-- Main content -->
            <div class="col-lg-9">
                <!-- Phim theo thể loại -->
                <div id="phim-theo-the-loai" class="section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="guide__title">Phim Theo Thể Loại: <?php echo $genreName; ?></h3>
                    </div>
                    <div class="row">
                        <?php while ($movie = mysqli_fetch_assoc($result)) { ?>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card">
                                <a href="info.php?id=<?php echo $movie['id']; ?>">
                                    <img src="/anime/admin/view/img/<?php echo htmlspecialchars($movie['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="info.php?id=<?php echo $movie['id']; ?>" class="stretched-link">
                                                <?php echo htmlspecialchars($movie['title']); ?>
                                            </a>
                                        </h5>
                                        <p class="card-text">Quốc gia: <?php echo htmlspecialchars($movie['country_name']); ?></p>
                                        <p class="card-text">
                                        <img src="/anime/image/ngthach.png" style="all: unset; vertical-align: middle;; width: 25px;">
                                            Giá: <?php echo htmlspecialchars(format_cash($movie['money']));?>đ
                                        </p>
                                        <p class="card-text">Lượt xem: <?php echo htmlspecialchars($movie['total_views']);?></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <?php
                            // Tính tổng số phim và số trang
                            $countQuery = "SELECT COUNT(DISTINCT m.id) AS total
                                           FROM movies m
                                           JOIN movie_genres mg ON m.id = mg.movie_id
                                           JOIN genres g ON mg.genre_id = g.id
                                           WHERE g.id = ?";
                            $countStmt = $conn->prepare($countQuery);
                            $countStmt->bind_param('i', $genreId);
                            $countStmt->execute();
                            $countResult = $countStmt->get_result();
                            $countRow = mysqli_fetch_assoc($countResult);
                            $totalMovies = $countRow['total'];
                            $totalPages = ceil($totalMovies / $moviesPerPage);

                            // Hiển thị liên kết phân trang
                            if ($currentPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?id=' . $genreId . '&page=' . ($currentPage - 1) . '">Previous</a></li>';
                            }

                            for ($i = 1; $i <= $totalPages; $i++) {
                                echo '<li class="page-item' . ($i == $currentPage ? ' active' : '') . '"><a class="page-link" href="?id=' . $genreId . '&page=' . $i . '">' . $i . '</a></li>';
                            }

                            if ($currentPage < $totalPages) {
                                echo '<li class="page-item"><a class="page-link" href="?id=' . $genreId . '&page=' . ($currentPage + 1) . '">Next</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Sidebar -->
            <?php include_once 'navbar.php'; ?> 
        </div>
    </div>

    <!-- Footer -->
    <?php include_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
