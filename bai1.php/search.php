<?php
// Connect to database
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get search keyword from GET
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search != '') {
    // Query to search products by name
    $sql = "SELECT * FROM products WHERE name LIKE '%" . mysqli_real_escape_string($connect, $search) . "%'";
    $result = mysqli_query($connect, $sql);
} else {
    $result = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <?php include 'header.php'; ?>
</head>
<body>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #1a0000, #330033, #0d0d0d);
            font-family: 'Poppins', sans-serif;
            color: #fff;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Main Wrapper */
        .wrapper {
            width: 90%;
            max-width: 1400px;
            margin: 100px auto;
            padding: 30px;
            background: rgba(10, 10, 10, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(255, 51, 153, 0.2);
            text-align: center;
        }

        /* Title */
        h2 {
            color: #ff3399;
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: glow 2s ease-in-out infinite alternate;
            margin-top: 90px;
            text-align: center;
        }

        @keyframes glow {
            from {
                text-shadow: 0 0 10px rgba(255, 51, 153, 0.8), 0 0 20px rgba(255, 51, 153, 0.5);
            }
            to {
                text-shadow: 0 0 20px rgba(255, 51, 153, 1), 0 0 40px rgba(255, 51, 153, 0.7);
            }
        }

        /* Product Container */
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px;
        }

        /* Product */
        .product {
            background: #1a1a1a;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(255, 51, 153, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(255, 51, 153, 0.3);
        }

        /* Product Image */
        .product img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        .product:hover img {
            transform: scale(1.05);
        }

        /* Product Title */
        .product h3 {
            font-size: 20px;
            font-weight: 600;
            color: #ff99cc;
            margin-bottom: 10px;
            text-transform: capitalize;
        }

        /* Product Price */
        .product p {
            color: #ff3399;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 0 5px rgba(255, 51, 153, 0.5);
        }

        /* Add to Cart Button */
        .product button {
            display: inline-block;
            background: linear-gradient(135deg, #ff3399, #cc0066);
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 51, 153, 0.4);
        }

        .product button:hover {
            background: linear-gradient(135deg, #cc0066, #ff3399);
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(255, 51, 153, 0.6);
        }

    </style>
    <h2>Search results for: "<?php echo htmlspecialchars($search); ?>"</h2>
    <div class="product-list">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product'>";
                echo "<img src='" . $row['image'] . "' alt='" . htmlspecialchars($row['name']) . "'>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p>Price: " . number_format($row['price'], 0, ',', '.') . "đ</p>";
                
                // Add to cart form
                echo "<form method='POST' action='cart.php'>";
                echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                echo "<input type='hidden' name='product_name' value='" . htmlspecialchars($row['name']) . "'>";
                echo "<input type='hidden' name='product_price' value='" . $row['price'] . "'>";
                echo "<input type='hidden' name='product_image' value='" . $row['image'] . "'>";
                echo "<button type='submit'>Add to Cart</button>";
                echo "</form>";
                
                echo "</div>";
            }
        } else {
            echo "<p>No products found!</p>";
        }
        ?>
    </div>
    <?php include 'footer.html'; ?>

</body>
</html>

