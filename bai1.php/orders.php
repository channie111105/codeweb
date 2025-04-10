<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');

if (!$connect) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập để xem đơn hàng.");
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($connect, $sql);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của bạn</title>
</head>
<body>
    <h2>Danh sách đơn hàng</h2>
    <table border="1">
        <tr>
            <th>Mã đơn hàng</th>
            <th>Ngày đặt</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>#<?= $row['id'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= number_format($row['total_price'], 0, ',', '.') ?> VND</td>
                <td><?= ucfirst($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="index.php">Quay lại trang chủ</a>
</body>
</html>

<?php mysqli_close($connect); ?>