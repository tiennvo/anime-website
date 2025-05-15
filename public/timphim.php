<?php
include_once 'dbconnect.php';

// Xác định số phim trên mỗi trang
$moviesPerPage = 12;

// Lấy số trang từ URL hoặc mặc định là trang 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $moviesPerPage;

// Lấy các tiêu chí tìm kiếm từ URL
$searchTitle = isset($_GET['title']) ? mysqli_real_escape_string($conn, $_GET['title']) : '';
$searchCountry = isset($_GET['country']) ? (int)$_GET['country'] : 0;
$searchGenre = isset($_GET['genre']) ? (int)$_GET['genre'] : 0;
$searchActor = isset($_GET['actor']) ? mysqli_real_escape_string($conn, $_GET['actor']) : '';
$searchDirector = isset($_GET['director']) ? mysqli_real_escape_string($conn, $_GET['director']) : '';
$searchYear = isset($_GET['year']) ? (int)$_GET['year'] : 0;

// Xây dựng câu lệnh SQL với các tiêu chí tìm kiếm
$query = "SELECT m.id, m.title, m.description, m.release_year, m.director, m.actors, m.genre, m.rating, m.trailer_url, m.type, m.video_url, m.image_url, m.money, m.total_views, c.name AS country_name
          FROM movies m
          JOIN countries c ON m.country_id = c.id
          WHERE 1=1";

if ($searchTitle) {
    $query .= " AND m.title LIKE '%$searchTitle%'";
}
if ($searchCountry) {
    $query .= " AND m.country_id = $searchCountry";
}
if ($searchGenre) {
    $query .= " AND m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = $searchGenre)";
}
if ($searchActor) {
    $query .= " AND m.actors LIKE '%$searchActor%'";
}
if ($searchDirector) {
    $query .= " AND m.director LIKE '%$searchDirector%'";
}
if ($searchYear) {
    $query .= " AND m.release_year = $searchYear";
}

// Thêm phân trang
$query .= " LIMIT $offset, $moviesPerPage";

// Thực thi câu lệnh SQL
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Tính tổng số phim cho phân trang
$countQuery = "SELECT COUNT(*) AS total FROM movies m WHERE 1=1";

if ($searchTitle) {
    $countQuery .= " AND m.title LIKE '%$searchTitle%'";
}
if ($searchCountry) {
    $countQuery .= " AND m.country_id = $searchCountry";
}
if ($searchGenre) {
    $countQuery .= " AND m.id IN (SELECT movie_id FROM movie_genres WHERE genre_id = $searchGenre)";
}
if ($searchActor) {
    $countQuery .= " AND m.actors LIKE '%$searchActor%'";
}
if ($searchDirector) {
    $countQuery .= " AND m.director LIKE '%$searchDirector%'";
}
if ($searchYear) {
    $countQuery .= " AND m.release_year = $searchYear";
}

$countResult = mysqli_query($conn, $countQuery);
if (!$countResult) {
    die("Query failed: " . mysqli_error($conn));
}
$countRow = mysqli_fetch_assoc($countResult);
$totalMovies = $countRow['total'];
$totalPages = ceil($totalMovies / $moviesPerPage);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tìm Phim</title>
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
                <!-- Tìm kiếm phim -->
                <div id="tim-phim" class="section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="text-warning">Tìm Phim</h3>
                    </div>
                    <!-- Form tìm kiếm -->
                    <form method="GET" action="timphim.php" class="mb-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <input type="text" name="title" class="form-control" placeholder="Tên phim" value="<?php echo htmlspecialchars($searchTitle); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <select name="country" class="form-select">
                                    <option value="">Chọn quốc gia</option>
                                    <?php
                                    $countriesQuery = "SELECT id, name FROM countries";
                                    $countriesResult = mysqli_query($conn, $countriesQuery);
                                    while ($country = mysqli_fetch_assoc($countriesResult)) {
                                        echo '<option value="' . $country['id'] . '"' . ($country['id'] == $searchCountry ? ' selected' : '') . '>' . htmlspecialchars($country['name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <select name="genre" class="form-select">
                                    <option value="">Chọn thể loại</option>
                                    <?php
                                    $genresQuery = "SELECT id, name FROM genres";
                                    $genresResult = mysqli_query($conn, $genresQuery);
                                    while ($genre = mysqli_fetch_assoc($genresResult)) {
                                        echo '<option value="' . $genre['id'] . '"' . ($genre['id'] == $searchGenre ? ' selected' : '') . '>' . htmlspecialchars($genre['name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="actor" class="form-control" placeholder="Diễn viên" value="<?php echo htmlspecialchars($searchActor); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="text" name="director" class="form-control" placeholder="Đạo diễn" value="<?php echo htmlspecialchars($searchDirector); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <input type="number" name="year" class="form-control" placeholder="Năm phát hành" value="<?php echo htmlspecialchars($searchYear); ?>">
                            </div>
                            <div class="col-12 mb-3">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                    <!-- Kết quả tìm kiếm -->
                    <div class="row">
                        <?php while ($movie = mysqli_fetch_assoc($result)) { ?>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <div class="card">
                                    <a href="info.php?id=<?php echo $movie['id']; ?>">
                                        <img src="../admin/view/img/<?php echo htmlspecialchars($movie['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="info.php?id=<?php echo $movie['id']; ?>">
                                                <?php echo htmlspecialchars($movie['title']); ?>
                                            </a>
                                        </h5>
                                        <p class="card-text">
                                            Quốc gia: <?php echo htmlspecialchars($movie['country_name']); ?>
                                        </p>
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
                                echo '<li class="page-item"><a class="page-link" href="?title=' . urlencode($searchTitle) . '&country=' . $searchCountry . '&genre=' . $searchGenre . '&actor=' . urlencode($searchActor) . '&director=' . urlencode($searchDirector) . '&year=' . $searchYear . '&page=' . ($currentPage - 1) . '">Previous</a></li>';
                            }
                            for ($i = 1; $i <= $totalPages; $i++) {
                                echo '<li class="page-item' . ($i == $currentPage ? ' active' : '') . '"><a class="page-link" href="?title=' . urlencode($searchTitle) . '&country=' . $searchCountry . '&genre=' . $searchGenre . '&actor=' . urlencode($searchActor) . '&director=' . urlencode($searchDirector) . '&year=' . $searchYear . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                            if ($currentPage < $totalPages) {
                                echo '<li class="page-item"><a class="page-link" href="?title=' . urlencode($searchTitle) . '&country=' . $searchCountry . '&genre=' . $searchGenre . '&actor=' . urlencode($searchActor) . '&director=' . urlencode($searchDirector) . '&year=' . $searchYear . '&page=' . ($currentPage + 1) . '">Next</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
            </div>
            
        </div>
    </div>
<!-- footer -->
<?php include_once 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>

<?php
// Đóng kết nối
mysqli_close($conn);
?>
