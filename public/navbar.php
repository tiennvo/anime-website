<?php
include_once 'dbconnect.php';


$query = "SELECT id, title, image_url
          FROM movies
          ORDER BY id DESC
          LIMIT 10";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<div class="col-lg-3">
    <div class="list-group">
        <h4 class="rainbow-text">⭐ Phim mới cập nhật</h4>
        <?php while ($movie = mysqli_fetch_assoc($result)) { ?>
            <a href="info.php?id=<?php echo $movie['id']; ?>" class="list-group-item list-group-item-action">
                <img src="/anime/admin/view/img/<?php echo htmlspecialchars($movie['image_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                <span><?php echo htmlspecialchars($movie['title']); ?></span>
            </a>
        <?php } ?>
    </div>
</div>
