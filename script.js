<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

document.addEventListener("DOMContentLoaded", function() {
    // Chọn tất cả các phần chứa phim
    const sections = document.querySelectorAll('.section .row');

    sections.forEach(section => {
        const movies = section.querySelectorAll('.col-md-3');
        // Kiểm tra nếu có nhiều hơn 8 phim, thì ẩn các phim thừa
        if (movies.length > 8) {
            movies.forEach((movie, index) => {
                if (index >= 8) {
                    movie.style.display = 'none';
                }
            });
        }
    });
});


