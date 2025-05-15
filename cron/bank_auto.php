<?php
session_start();
require_once 'dbconnect.php'; // Bao gồm file kết nối cơ sở dữ liệu

$settings = [];
$sql = "SELECT type, value FROM setting";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $settings[$row['type']] = $row['value'];
}

function parse_order_id($des)
{
    $pattern = '/nt\s+(\d+(?:\.\d+)?)/i';

    // Sử dụng preg_match để tìm kết quả phù hợp với biểu thức chính quy
    preg_match($pattern, $des, $matches);

    // Nếu có kết quả, trả về số tiền
    if (isset($matches[1])) {
        return $matches[1];
    } else {
        return null; // Trả về null nếu không tìm thấy
    }
}

// Ví dụ dùng:
$stk = $settings['stk_bank'];
$ctk = $settings['ctk_bank'];
$api = $settings['api_bank'];
$token = $settings['token_bank'];

$ch = curl_init('https://app.apingon.com/historyapibidv/' . $token);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$lsgd = json_decode($response, true);

// echo '<pre>';
// var_dump($lsgd['txnList']);
// echo '</pre>';
/*if($response['status'] != 'success')
{
    die('Lấy dữ liệu thất bại');
}*/
foreach($lsgd['txnList'] as $data)
{
    $des = $data['txnRemark'];
    $amount = $data['amount'];
    $tid = $data['refNo'];
    $user_id  = parse_order_id($des);
    //echo $user_id;
    if($data['txnType'] == '+')
    {
        if ($user_id) {
            // Lấy user theo ID
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($user = $result->fetch_assoc()) {

                // Kiểm tra trùng giao dịch
                $check = $conn->prepare("SELECT id FROM bank_auto WHERE tid = ?");
                $check->bind_param("s", $tid);
                $check->execute();
                $check_result = $check->get_result();
                if ($check_result->num_rows === 0) {

                    // Lưu vào bảng bank_auto
                    $stmt = $conn->prepare("INSERT INTO bank_auto (tid, description, amount, time, username) VALUES (?, ?, ?, NOW(), ?)");
                    $stmt->bind_param("ssis", $tid, $des, $amount, $user['username']);
                    $stmt->execute();

                    // Cộng tiền (nếu có chiết khấu thì cộng thêm)
                    $ck_bank = 0; // % chiết khấu có thể lấy từ bảng setting nếu có
                    $real_amount = $amount;

                    // Cập nhật số dư
                    $stmt = $conn->prepare("UPDATE users SET money = money + ?, total_money = total_money + ? WHERE id = ?");
                    $stmt->bind_param("ddi", $real_amount, $real_amount, $user_id);
                    $stmt->execute();

                    // Ghi log lịch sử (tạo bảng dongtien nếu chưa có)
                    $stmt = $conn->prepare("INSERT INTO dongtien (sotientruoc, sotienthaydoi, sotiensau, thoigian, noidung, username) VALUES (?, ?, ?, NOW(), ?, ?)");
                    $sotientruoc = $user['money'];
                    $sotiensau = $sotientruoc + $real_amount;
                    $noidung = 'BIDV | ' . $tid;
                    $stmt->bind_param("ddsss", $sotientruoc, $real_amount, $sotiensau, $noidung, $user['username']);
                    $stmt->execute();
                }
            }
        }
    }
}
?>