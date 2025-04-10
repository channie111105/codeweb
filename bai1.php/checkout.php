<?php
// Database connection
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to checkout!'); window.location.href='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle checkout process
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    if (!empty($_POST['selected_products'])) {
        $selected_products = array_map('intval', $_POST['selected_products']);
        $product_ids = implode(",", $selected_products);

        // Fetch product details
        $sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND product_id IN ($product_ids)";
        $result = mysqli_query($connect, $sql);
        if (!$result) {
            die("Error querying products: " . mysqli_error($connect));
        }

        $total_price = 0;
        $product_list = [];

        if (mysqli_num_rows($result) > 0) {
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>
                      <meta charset='UTF-8'>
                      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                      <title>Checkout Confirmation</title>
                      <style>
                          body { font-family: Arial, sans-serif; margin: 20px; background: #1a1a1a; color: #fff; text-align: center; }
                          h2 { color: #ff66b2; }
                          p { font-size: 16px; }
                          .total { font-weight: bold; color: #ff66b2; font-size: 18px; }
                          select, button { padding: 10px; margin: 10px; font-size: 16px; border-radius: 8px; border: none; }
                          select { background: #ff66b2; color: #fff; }
                          button { background: #ff1493; color: #fff; cursor: pointer; transition: 0.3s; }
                          button:hover { background: #ff007f; }
                          .container { background: #333; padding: 20px; border-radius: 10px; display: inline-block; }
                      </style>
                  </head>
                  <body>
                      <div class='container'>
                          <h2>🛒 Your Selected Products:</h2>";

            while ($row = mysqli_fetch_assoc($result)) {
                $subtotal = $row['product_price'] * $row['quantity'];
                $total_price += $subtotal;
                $product_list[] = $row['product_id'];
                echo "<p>📖 " . htmlspecialchars($row['product_name']) . " - " . number_format($subtotal, 0, ',', '.') . " VND (x{$row['quantity']})</p>";
            }

            echo "<p class='total'>💰 Total Price: <span id='total-price'>" . number_format($total_price, 0, ',', '.') . "</span> VND</p>

                  <h3>🎁 Choose a Discount Code:</h3>
                  <select id='voucher' onchange='updateTotal()'>
                      <option value=''>-- No Discount --</option>
                      <option value='SALE10'>SALE10 - 10% Off</option>
                      <option value='FREESHIP'>FREESHIP - Free Shipping</option>
                      <option value='DISCOUNT50'>DISCOUNT50 - 50% Off</option>
                  </select>

                  <button onclick='confirmCheckout()'>✅ Confirm Payment</button>
              </div>

              <script>
                  let originalTotal = $total_price;
                  let discountRates = { 'SALE10': 10, 'FREESHIP': 0, 'DISCOUNT50': 50 };

                  function updateTotal() {
                      let voucher = document.getElementById('voucher').value;
                      let discount = discountRates[voucher] ? (originalTotal * discountRates[voucher] / 100) : 0;
                      let newTotal = originalTotal - discount;
                      document.getElementById('total-price').innerText = newTotal.toLocaleString();
                  }

                  function confirmCheckout() {
                      let voucher = document.getElementById('voucher').value;
                      let newTotal = document.getElementById('total-price').innerText.replaceAll(',', '');

                      let confirmPayment = confirm('Total payable amount: ' + newTotal + ' VND. Proceed?');
                      if (confirmPayment) {
                          window.location.href = 'pay.php?total=' + newTotal + '&products=" . implode(",", $product_list) . "&voucher=' + voucher;
                      }
                  }
              </script>
              </body></html>";
        } else {
            echo "<script>alert('No selected products found!'); window.location.href='cart.php';</script>";
        }
    } else {
        echo "<script>alert('Please select products before checking out!'); window.location.href='cart.php';</script>";
    }
}

mysqli_close($connect);
?>