

<div class="sidebar">
    <h4 class="text-white">Quản lý Trang</h4>
    <a href="/anime/index.php">Cook PHP</a>
    <a href="/anime/admin/index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/anime/admin/manage_movies/index.php"><i class="fas fa-film"></i> Quản lý Phim</a>
    <a href="/anime/admin/manage_episodes/index.php"><i class="fas fa-tv"></i> Quản lý Tập</a>
    <a href="/anime/admin/manage_genres/index.php"><i class="fas fa-tags"></i> Quản lý Thể loại</a>
    <a href="/anime/admin/manage_nation/index.php"><i class="fas fa-tags"></i> Quản lý Quốc Gia Phim</a>
    <a href="/anime/admin/manage_users/index.php"><i class="fas fa-users"></i> Quản lý Người dùng</a>
    <a href="/anime/admin/manage_comments/index.php"><i class="fas fa-comments"></i> Quản lý Bình luận</a>
    <a href="/anime/admin/manage_ratings/index.php"><i class="fas fa-star"></i> Quản lý Đánh giá</a>
    <a href="/anime/admin/bank_auto/index.php"><i class="fas fa-star"></i> Setting Bank Auto</a>

    <!-- Thêm nút đăng nhập hoặc đăng xuất -->
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Đăng Nhập</a>
    <?php else: ?>
        <div>
            <p class="text-white">Xin chào, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <form method="post" style="display:inline;">
                <button type="submit" name="logout" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>
