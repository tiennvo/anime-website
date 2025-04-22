<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quốc Gia</title>
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
                <!-- Phần phim theo mùa -->
                <div id="phim-theo-mua" class="section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="text-warning">Quốc Gia</h3>
                    </div>
                    <div class="row">
                        <!-- Phim 1 -->
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card">
                                <img src="https://img.phimmoichillv.net/images/info/the-roundup-punishment.jpg"
                                    class="card-img-top" alt="Phim 1">
                                <div class="card-body">
                                    <h5 class="card-title">Tên Phim 1</h5>
                                    <p class="card-text">Quốc gia:</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="list-group">
                    <h4 class="rainbow-text">⭐ Phim mới cập nhật</h4>
                    <a href="#" class="list-group-item list-group-item-action">
                        <img src="https://img.phimmoichillv.net/images/info/the-roundup-punishment.jpg" alt="Phim 1">
                        <span>1.</span> Tên Phim 1
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <img src="https://img.phimmoichillv.net/images/info/the-roundup-punishment.jpg" alt="Phim 2">
                        <span>2.</span> Tên Phim 2
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <img src="https://img.phimmoichillv.net/images/info/the-roundup-punishment.jpg" alt="Phim 3">
                        <span>3.</span> Tên Phim 3
                    </a>
                    <!-- Tiếp tục thêm đến 10 phim -->
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript để giới hạn số lượng phim hiển thị -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const movies = document.querySelectorAll('.col-md-3');
            if (movies.length > 12) {
                movies.forEach((movie, index) => {
                    if (index >= 12) {
                        movie.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>

</html>
