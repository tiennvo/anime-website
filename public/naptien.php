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
$user_query = "SELECT username, email, password, money FROM users WHERE id = ?";
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

// Lấy thông setting
$settings = [];
$sql = "SELECT type, value FROM setting";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $settings[$row['type']] = $row['value'];
}

// Ví dụ dùng:
$stk = $settings['stk_bank'];
$ctk = $settings['ctk_bank'];
$api = $settings['api_bank'];
$token = $settings['token_bank'];

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Người Dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- header -->
    <?php include_once 'header.php'; ?>

    <div class="container my-5">
        <!-- Chỉnh sửa thông tin -->
        <div id="edit-info" class="section mb-5">
            <h3 class="guide__title">Nạp tiền tự động ATM/MOMO</h3>
            <?php if (isset($successMsg)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
            <?php endif; ?>
            <?php if (isset($errorMsg)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($errorMsg); ?></div>
            <?php endif; ?>
            <form>
                <div class="guide__title">
                    <label for="stk" class="form-label">STK</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="stk" name="stk" value=<?php echo htmlspecialchars($stk); ?> readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('stk')">Sao chép</button>
                    </div>
                </div>

                <div class="guide__title">
                    <label for="ctk" class="form-label">CTK</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="ctk" name="ctk" value="VO TRAN TIEN" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('ctk')">Sao chép</button>
                    </div>
                </div>

                <div class="guide__title">
                    <label for="username" class="form-label">Nội dung CK</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="username" name="username" value="nt <?php echo htmlspecialchars($user_id); ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('username')">Sao chép</button>
                    </div>
                </div>
            </form>
        </div>
        <p class="guide__title">THÔNG TIN TÀI KHOẢN NGÂN HÀNG (Quét QR để nạp)</p>
            <img src="https://img.vietqr.io/image/970418-TIENNVONET-print.png?amount=<AMOUNT>&addInfo=nt <?php echo htmlspecialchars($user_id); ?>&accountName=VO TRAN TIEN" width="400" height="500">
        </div>
    </div>

    <!-- Footer -->
    <?php include_once 'footer.php'; ?>
    <script>
    function copyToClipboard(id) {
        const input = document.getElementById(id);
        input.select();
        input.setSelectionRange(0, 99999); // Cho mobile
        document.execCommand("copy");
        alert("Đã sao chép: " + input.value);
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
