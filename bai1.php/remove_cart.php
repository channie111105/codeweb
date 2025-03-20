<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

if (isset($_GET['product_id']) && isset($_SESSION['user_id'])) {
    $product_id = mysqli_real_escape_string($connect, $_GET['product_id']);
    $user_id = $_SESSION['user_id'];

    $delete_sql = "DELETE FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'";
    mysqli_query($connect, $delete_sql);
}

mysqli_close($connect);
header("Location: cart.php");
exit();
?>
