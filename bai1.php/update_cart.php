<?php
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

session_start();
if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập để sử dụng giỏ hàng.");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];
    
    // Lấy số lượng hiện tại
    $query = "SELECT quantity FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    $current_quantity = $row['quantity'];

    if ($action == 'increase') {
        $new_quantity = $current_quantity + 1;
    } elseif ($action == 'decrease') {
        $new_quantity = max(1, $current_quantity - 1);
    } elseif ($action == 'update' && isset($_POST['quantity'])) {
        $new_quantity = max(1, intval($_POST['quantity'])); // Cập nhật số lượng từ input
    }

    // Cập nhật số lượng trong database
    $update_sql = "UPDATE cart SET quantity = '$new_quantity' WHERE product_id = '$product_id' AND user_id = '$user_id'";
    mysqli_query($connect, $update_sql);
}

// Quay lại trang giỏ hàng
header("Location: cart.php");
exit();
?>
