<?php
include_once 'dbconnect.php';

// Xác định số phim trên mỗi trang
$moviesPerPage = 12;

// Lấy số trang từ URL hoặc mặc định là trang 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $moviesPerPage;

// Lấy ID quốc gia từ URL và bảo mật truy vấn
$countryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$countryId = mysqli_real_escape_string($conn, $countryId);

// Truy vấn phim theo quốc gia từ cơ sở dữ liệu và lấy tên quốc gia
$query = "SELECT m.id, m.title, m.description, m.release_year, m.director, m.actors, m.genre, m.rating, m.trailer_url, m.type, m.video_url, m.image_url, m.money, m.total_views, c.name AS country_name
          FROM movies m
          JOIN countries c ON m.country_id = c.id
          WHERE m.country_id = $countryId
          LIMIT $offset, $moviesPerPage";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Truy vấn tổng số phim theo quốc gia để tính số trang
$countQuery = "SELECT COUNT(*) AS total FROM movies WHERE country_id = $countryId";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalMovies = $countRow['total'];
$totalPages = ceil($totalMovies / $moviesPerPage);

// Truy vấn tên quốc gia để hiển thị trên tiêu đề trang
$countryQuery = "SELECT name FROM countries WHERE id = $countryId";
$countryResult = mysqli_query($conn, $countryQuery);
$countryRow = mysqli_fetch_assoc($countryResult);
$countryName = $countryRow ? htmlspecialchars($countryRow['name']) : 'Unknown';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phim Theo Quốc Gia - <?php echo $countryName; ?></title>
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
                <!-- Phần phim theo quốc gia -->
                <div id="phim-theo-quoc-gia" class="section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="guide__title">Phim Theo Quốc Gia: <?php echo $countryName; ?></h3>
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
                            // Hiển thị liên kết phân trang
                            if ($currentPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?id=' . $countryId . '&page=' . ($currentPage - 1) . '">Previous</a></li>';
                            }

                            for ($i = 1; $i <= $totalPages; $i++) {
                                echo '<li class="page-item' . ($i == $currentPage ? ' active' : '') . '"><a class="page-link" href="?id=' . $countryId . '&page=' . $i . '">' . $i . '</a></li>';
                            }

                            if ($currentPage < $totalPages) {
                                echo '<li class="page-item"><a class="page-link" href="?id=' . $countryId . '&page=' . ($currentPage + 1) . '">Next</a></li>';
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
