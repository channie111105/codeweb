<?php
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để thanh toán!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['selected_products'])) {
    $selected_products = $_POST['selected_products'];
    $product_ids = implode(",", array_map('intval', $selected_products));

    // Lấy thông tin sản phẩm đã chọn
    $sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id IN ($product_ids)";
    $result = mysqli_query($connect, $sql);

    $total_price = 0;
    $product_list = [];

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Danh sách sản phẩm bạn thanh toán:</h2>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Sản phẩm: " . htmlspecialchars($row['product_name']) . " - Giá: " . number_format($row['product_price'], 0, ',', '.') . " VND<br>";
            $total_price += $row['product_price'] * $row['quantity'];
            $product_list[] = $row['product_id'];
        }

        echo "<script>
            setTimeout(function() {
                let confirmPayment = confirm('Tổng số tiền bạn cần thanh toán là: " . number_format($total_price, 0, ',', '.') . " VND. Bạn có muốn tiếp tục thanh toán không?');
                if (confirmPayment) {
                    window.location.href = 'pay.php?total=" . $total_price . "&products=" . implode(",", $product_list) . "';
                } else {
                    window.location.href = 'cart.php';
                }
            }, 500);
        </script>";
    } else {
        echo "<script>alert('Không có sản phẩm nào được chọn!'); window.location.href='cart.php';</script>";
    }
} else {
    echo "<script>alert('Vui lòng chọn sản phẩm trước khi thanh toán!'); window.location.href='cart.php';</script>";
}

mysqli_close($connect);
?>
