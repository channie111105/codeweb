<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['total']) || !isset($_GET['products'])) {
    echo "<script>alert('Có lỗi xảy ra! Vui lòng thử lại.'); window.location.href='cart.php';</script>";
    exit();
}

$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
$user_id = $_SESSION['user_id'];
$total_price = floatval($_GET['total']);
$product_ids = explode(",", $_GET['products']);

// Xử lý thanh toán (ở đây giả lập thanh toán thành công)
foreach ($product_ids as $product_id) {
    $product_id = intval($product_id);
    $delete_sql = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    mysqli_query($connect, $delete_sql);
}

echo "<script>
    alert('Thanh toán thành công! Cảm ơn khách hàng đã sử dụng dịch vụ của Bookstore.');
    window.location.href = 'index.php';
</script>";

mysqli_close($connect);
?>
