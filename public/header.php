<?php
include_once 'dbconnect.php';


// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn để kiểm tra thông tin đăng nhập
    $query = "SELECT id, username, password, money FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra mật khẩu
    if ($user && password_verify($password, $user['password'])) {
        // Lưu thông tin người dùng vào phiên
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['money'] = $user['money'];
        // Chuyển hướng về trang chính
        header("Location: index.php");
        exit;
    } else {
        // Thông báo lỗi nếu đăng nhập không thành công
        $errorMsg = "Tài khoản hoặc mật khẩu không đúng.";
    }
}

// Truy vấn thể loại
$genresQuery = "SELECT id, name FROM genres";
$genresResult = mysqli_query($conn, $genresQuery);

// Kiểm tra lỗi truy vấn
if (!$genresResult) {
    die("Query failed: " . mysqli_error($conn));
}

// Truy vấn quốc gia
$countriesQuery = "SELECT id, name FROM countries";
$countriesResult = mysqli_query($conn, $countriesQuery);

// Kiểm tra lỗi truy vấn
if (!$countriesResult) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: rgb(23 21 44 / 48%);">
    <div class="container-fluid">
        <a href="/anime/index.php"><img width="50" height="50" src="/anime/image/logo.jpg" class="v-logo"></a>
        <a class="navbar-brand" href="/anime/index.php">Cook PHP</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/anime/public/phimle.php">Phim Lẻ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/anime/public/phimbo.php">Phim Bộ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTheLoai" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Thể Loại
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownTheLoai">
                        <?php while ($genre = mysqli_fetch_assoc($genresResult)) { ?>
                            <li><a class="dropdown-item" href="/anime/public/phimtheotheloai.php?id=<?php echo $genre['id']; ?>"><?php echo htmlspecialchars($genre['name']); ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownQuocGia" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Quốc Gia
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownQuocGia">
                        <?php while ($country = mysqli_fetch_assoc($countriesResult)) { ?>
                            <li><a class="dropdown-item" href="/anime/public/phimtheoquocgia.php?id=<?php echo $country['id']; ?>"><?php echo htmlspecialchars($country['name']); ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/anime/public/tuphim.php">Tủ Phim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/anime/public/naptien.php">Nạp Tiền</a>
                </li>
            </ul>
            <!-- Search form -->
            <form class="d-flex me-auto" role="search" action="/anime/public/timphim.php" method="get">
                <input class="form-control me-2" type="search" name="query" placeholder="Tìm kiếm phim..." aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Tìm</button>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item d-flex align-items-center">
                    <?php if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) { ?>
                        <div class="d-flex align-items-center">
                            <a class="navbar-text me-2" href="/anime/public/info_user.php?id=<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <a class="nav-link" href="/anime/public/logout.php" title="Đăng Xuất">
                                <i class="bi bi-box-arrow-right"></i>
                            </a>
                        </div>
                    <?php } else { ?>
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng Nhập</a>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel"  style="color: black">Đăng Ký</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($errorMsg)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
                <?php endif; ?>
                <?php if (isset($successMsg)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
                <?php endif; ?>
                <form action="/anime/public/register.php" method="post">
                    <div class="mb-3">
                        <label for="regUsername" class="form-label" style="color: black">Tài khoản</label>
                        <input type="text" class="form-control" id="regUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="regEmail" class="form-label"  style="color: black">Email</label>
                        <input type="email" class="form-control" id="regEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="regPassword" class="form-label"  style="color: black">Mật khẩu</label>
                        <input type="password" class="form-control" id="regPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng Ký</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="btn btn-link">Đăng Nhập</a>
            </div>
        </div>
    </div>
</div>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel" style="color: black">Đăng Nhập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/anime/public/login.php" method="post">
                    <div class="mb-3">
                        <label for="loginUsername" class="form-label" style="color: black">Tài khoản</label>
                        <input type="text" class="form-control" id="loginUsername" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label" style="color: black">Mật khẩu</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" class="btn btn-link">Đăng Ký</a>
            </div>
        </div>
    </div>
</div>
