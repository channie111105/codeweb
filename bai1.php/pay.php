<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['total']) || !isset($_GET['products'])) {
    echo "<script>alert('An error occurred! Please try again.'); window.location.href='cart.php';</script>";
    exit();
}

$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
$user_id = $_SESSION['user_id'];
$total_price = floatval($_GET['total']);
$product_ids = explode(",", $_GET['products']);
$voucher = isset($_GET['voucher']) ? $_GET['voucher'] : '';

$valid_vouchers = [
    "SALE10" => 10,
    "FREESHIP" => 0,
    "DISCOUNT50" => 50
];

// Apply discount if a valid voucher is used
if (isset($valid_vouchers[$voucher])) {
    $discount = ($total_price * $valid_vouchers[$voucher]) / 100;
    $total_price -= $discount;
}

// Remove purchased products from the cart after successful payment
foreach ($product_ids as $product_id) {
    $product_id = intval($product_id);
    $delete_sql = "DELETE FROM cart WHERE user_id = '$user_id' AND product_id = '$product_id'";
    mysqli_query($connect, $delete_sql);
}

echo "<script>
    alert('✅ Payment successful!');
    window.location.href = 'index.php';
</script>";

mysqli_close($connect);
?>
