<?php
// Database connection
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to add products to the cart!'); window.location.href='login.php';</script>";
    exit();
}
$user_id = $_SESSION['user_id'];

// Handle adding products to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = mysqli_real_escape_string($connect, $_POST['product_id']);
    $product_name = mysqli_real_escape_string($connect, $_POST['product_name']);
    $product_price = floatval($_POST['product_price']); // Ensure price is numeric
    $product_image = mysqli_real_escape_string($connect, $_POST['product_image']);

    // Check if the product already exists in the cart
    $check_sql = "SELECT quantity FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($connect, $check_sql);

    if (!$check_result) {
        die("Query error: " . mysqli_error($connect));
    }

    if (mysqli_num_rows($check_result) > 0) {
        // Update quantity if the product already exists
        $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE product_id = '$product_id' AND user_id = '$user_id'";
        if (!mysqli_query($connect, $update_sql)) {
            die("Error updating quantity: " . mysqli_error($connect));
        }
    } else {
        // Add new product to the cart
        $insert_sql = "INSERT INTO cart (user_id, product_id, product_image, product_name, product_price, quantity) 
                       VALUES ('$user_id', '$product_id', '$product_image', '$product_name', $product_price, 1)";
        if (!mysqli_query($connect, $insert_sql)) {
            die("Error adding product: " . mysqli_error($connect));
        }
    }

    // Redirect to refresh the page
    header("Location: cart.php");
    exit();
}

// Fetch the list of products in the cart
$sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
$result = mysqli_query($connect, $sql);
if (!$result) {
    die("Error querying cart: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h4 {
            color: #ed47c4;
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
            animation: neon-glow 1.5s infinite alternate;
        }

        @keyframes neon-glow {
            from {
                text-shadow: 0 0 5px #ff00ff, 0 0 15px #ff00ff, 0 0 25px #ff00ff;
            }
            to {
                text-shadow: 0 0 10px #ff66ff, 0 0 20px #ff66ff, 0 0 35px #ff66ff;
            }
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color:rgb(225, 18, 211);
            color: white;
            font-size: 16px;
        }
        td img {
            border-radius: 5px;
        }
        td input[type="number"] {
            width: 50px;
            padding: 5px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }
        button[name="action"][value="decrease"], 
        button[name="action"][value="increase"] {
            background-color: #f0f0f0;
            color: black;
        }
        button[name="action"][value="update"] {
            background-color:rgb(145, 15, 108);
            color: white;
        }
        button:hover {
            opacity: 0.8;
        }
        td a {
            text-decoration: none;
            color: red;
            font-weight: bold;
        }
        td a:hover {
            text-decoration: underline;
        }
        #checkout-btn {
            background-color:rgb(148, 7, 122);
            color: white;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
        }
        #checkout-btn:hover {
            background-color:rgb(155, 13, 114);
        }
        a.back-link {
            display: block;
            text-align: center;
            margin: 20px auto;
            font-size: 18px;
            color:rgb(171, 26, 118);
            font-weight: bold;
            text-decoration: none;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h4>Your Shopping Cart</h4>
    
    <form id="cart-form" action="checkout.php" method="POST">
        <table border="1">
            <tr>
                <th>Select</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $subtotal = $row['product_price'] * $row['quantity'];
                $total += $subtotal;
                echo "<tr>
                    <td><input type='checkbox' name='selected_products[]' value='{$row['product_id']}' class='product-checkbox'></td>
                    <td><img src='{$row['product_image']}' width='80' height='80'></td>
                    <td>{$row['product_name']}</td>
                    <td data-price='{$row['product_price']}'>" . number_format($row['product_price'], 0, ',', '.') . " VND</td>
                    <td>
                        <input type='hidden' class='product-id' value='{$row['product_id']}'>
                        <button type='button' class='decrease-btn' data-action='decrease'>➖</button>
                        <input type='number' class='quantity-input' value='{$row['quantity']}' min='1'>
                        <button type='button' class='increase-btn' data-action='increase'>➕</button>
                        <button type='button' class='update-btn' data-action='update'>Update</button>
                    </td>
                    <td class='subtotal' data-subtotal='{$subtotal}'>" . number_format($subtotal, 0, ',', '.') . " VND</td>
                    <td><a href='remove_cart.php?product_id={$row['product_id']}&user_id={$user_id}'>Remove</a></td>
                </tr>";
            }
            ?>
            <tr>
                <td colspan="5"><b>Total</b></td>
                <td id="total-price"><b><?= number_format($total, 0, ',', '.') ?> VND</b></td>
                <td><button type="submit" id="checkout-btn" name="checkout">Checkout</button></td>
            </tr>
        </table>
        <input type="hidden" name="total_price" value="<?= $total ?>">
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Handle checkout form submission
            const cartForm = document.getElementById("cart-form");
            const checkboxes = document.querySelectorAll(".product-checkbox");

            cartForm.addEventListener("submit", function (event) {
                let selected = false;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selected = true;
                    }
                });
                if (!selected) {
                    alert("Please select at least one product before checking out!");
                    event.preventDefault();
                }
            });

            // Function to format numbers for display
            function formatPrice(number) {
                return Number(number).toLocaleString("vi-VN", { minimumFractionDigits: 0, maximumFractionDigits: 0 }) + " VND";
            }

            // Function to update subtotal and total
            function updateCart() {
                let total = 0;
                document.querySelectorAll("tr").forEach(row => {
                    const quantityInput = row.querySelector(".quantity-input");
                    if (quantityInput) {
                        const pricePerUnit = parseFloat(row.querySelector("td:nth-child(4)").getAttribute("data-price"));
                        const quantity = parseInt(quantityInput.value);
                        const subtotal = pricePerUnit * quantity;
                        row.querySelector(".subtotal").setAttribute("data-subtotal", subtotal);
                        row.querySelector(".subtotal").textContent = formatPrice(subtotal);
                        total += subtotal;
                    }
                });
                document.getElementById("total-price").innerHTML = "<b>" + formatPrice(total) + "</b>";
                document.querySelector("input[name='total_price']").value = total;
            }

            // Initial update of cart totals
            updateCart();

            // Handle quantity updates via AJAX
            document.querySelectorAll(".decrease-btn, .increase-btn, .update-btn").forEach(button => {
                button.addEventListener("click", function () {
                    const row = this.closest("tr");
                    const productId = row.querySelector(".product-id").value;
                    const quantityInput = row.querySelector(".quantity-input");
                    let quantity = parseInt(quantityInput.value);
                    const action = this.getAttribute("data-action");

                    // Adjust quantity based on action
                    if (action === "decrease" && quantity > 1) {
                        quantity--;
                    } else if (action === "increase") {
                        quantity++;
                    } else if (action === "update") {
                        if (quantity < 1) {
                            alert("Quantity must be at least 1!");
                            return;
                        }
                    }

                    // Update the input field
                    quantityInput.value = quantity;

                    // Send AJAX request to update quantity in the database
                    fetch("update_cart.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: new URLSearchParams({
                            product_id: productId,
                            quantity: quantity,
                            action: "update"
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            // Update subtotal and total price on the page
                            updateCart();
                        } else {
                            alert("Error updating quantity: " + data.message);
                        }
                    })
                    .catch(err => {
                        console.error("Error:", err);
                        alert("An error occurred while updating the quantity!");
                    });
                });
            });
        });
    </script>
    <a href="index.php" class="back-link">Continue Shopping</a>
</body>
</html>

<?php
mysqli_close($connect);
?>