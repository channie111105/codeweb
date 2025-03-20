<?php
// Kết nối CSDL
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

session_start(); // Bắt đầu session
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!'); window.location.href='login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];


// Xử lý thêm sản phẩm vào giỏ hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = mysqli_real_escape_string($connect, $_POST['product_id']);
    $product_name = mysqli_real_escape_string($connect, $_POST['product_name']);
    $product_price = mysqli_real_escape_string($connect, $_POST['product_price']);
    $product_image = mysqli_real_escape_string($connect, $_POST['product_image']);


    // Kiểm tra sản phẩm đã có trong giỏ hàng của user chưa
    $check_sql = "SELECT * FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($connect, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        // Nếu có, cập nhật số lượng
        $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE product_id = '$product_id' AND user_id = '$user_id'";
        mysqli_query($connect, $update_sql);
    } else {
        // Nếu chưa có, thêm sản phẩm vào giỏ hàng
        $insert_sql = "INSERT INTO cart (user_id, product_id, product_image, product_name, product_price, quantity) 
                       VALUES ('$user_id', '$product_id', '$product_image', '$product_name', '$product_price', 1)";
        mysqli_query($connect, $insert_sql);
    }
}


// Lấy danh sách sản phẩm trong giỏ hàng
$sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
$result = mysqli_query($connect, $sql);



//
mysqli_close($connect);

?>




<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h4>Giỏ hàng của bạn</h4>
    
    <form id="cart-form" action="checkout.php" method="POST">
        <table border="1">
            <tr>
                <th>Chọn</th> <!-- Thêm cột chọn -->
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng tiền</th>
                <th>Hành động</th>
            </tr>
            <?php
                $connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
                
                if (!isset($_SESSION['user_id'])) {
                    echo "<script>alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng!'); window.location.href='login.php';</script>";
                    exit();
                }
                
                $user_id = $_SESSION['user_id'];
                $sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
                $result = mysqli_query($connect, $sql);
                $total = 0;
                
                while ($row = mysqli_fetch_assoc($result)) {
                    $subtotal = $row['product_price'] * $row['quantity'];
                    $total += $subtotal;
                    echo "<tr>
                        <td><input type='checkbox' name='selected_products[]' value='{$row['product_id']}' class='product-checkbox'></td>
                        <td><img src='{$row['product_image']}' width='80' height='80'></td>
                        <td>{$row['product_name']}</td>
                        <td>" . number_format($row['product_price'], 0, ',', '.') . " VND</td>
                        <td>
                            <form action='update_cart.php' method='POST' style='display: flex; align-items: center; gap: 5px;'>
                                <input type='hidden' name='product_id' value='{$row['product_id']}'>
                                <button type='submit' name='action' value='decrease'>➖</button>
                                <input type='number' name='quantity' value='{$row['quantity']}' min='1' style='width: 50px; text-align: center;'>
                                <button type='submit' name='action' value='increase'>➕</button>
                                <button type='submit' name='action' value='update' style='background-color: purple; color: white; border: none; padding: 5px 10px; cursor: pointer;'>Cập nhật</button>
                            </form>
                        </td>
                        <td>" . number_format($subtotal, 0, ',', '.') . " VND</td>
                        <td><a href='remove_cart.php?product_id={$row['product_id']}&user_id={$user_id}' style='color: red;'>Xóa</a></td>
                    </tr>";
                }
            ?>
            <tr>
                <td colspan="5"><b>Tổng cộng</b></td>
                <td><b><?= number_format($total, 0, ',', '.') ?> VND</b></td>
                <td>
                    <button type="submit" id="checkout-btn">Thanh toán</button>
                </td>
            </tr>
        </table>
        <input type="hidden" name="total_price" value="<?= $total ?>">
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("cart-form");
            const checkboxes = document.querySelectorAll(".product-checkbox");
            const submitButton = document.getElementById("checkout-btn");

            form.addEventListener("submit", function (event) {
                let selected = false;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selected = true;
                    }
                });
                if (!selected) {
                    alert("Vui lòng chọn ít nhất một sản phẩm trước khi thanh toán!");
                    event.preventDefault();
                }
            });
        });
    </script>
    <a href="index.php">Quay lại mua sắm</a>
</body>
</html>
