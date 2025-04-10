<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . mysqli_connect_error()]));
}

if (!isset($_SESSION['user_id'])) {
    die(json_encode(["status" => "error", "message" => "User not logged in"]));
}
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = mysqli_real_escape_string($connect, $_POST['product_id']);
    $quantity = (int)$_POST['quantity'];

    if ($quantity < 1) {
        echo json_encode(["status" => "error", "message" => "Quantity must be at least 1"]);
        exit();
    }

    $update_sql = "UPDATE cart SET quantity = '$quantity' WHERE product_id = '$product_id' AND user_id = '$user_id'";
    if (mysqli_query($connect, $update_sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($connect)]);
    }
    exit();
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
mysqli_close($connect);
?>