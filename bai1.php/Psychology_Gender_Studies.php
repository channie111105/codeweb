<?php
$connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

// Fetch products in "Marketing - Sales" category
$result = $connect->query("SELECT * FROM products WHERE category = 'Psychology - Gender Studies'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Psychology - Gender Studies</title>
    <?php include 'header.php'; ?>

    <style>
/* Reset cơ bản và body */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #0d0d0d, #1a001a);
    font-family: 'Arial', sans-serif;
    overflow-x: hidden;
}

/* Khung chứa tiêu đề và danh sách sản phẩm */
.wrapper {
    width: 90%;
    max-width: 1300px;
    margin: 400px auto;
    padding: 20px;
    background: rgba(30, 30, 30, 0.8);
    border-radius: 20px;
    box-shadow: 0 0 15px rgba(255, 51, 153, 0.5); /* Hồng nhạt thay cho tím */
    text-align: center;
    margin-top: 100px; /* Điều chỉnh khoảng cách xuống */
}

/* Tiêu đề */
h2 {
    color: #ff3399; /* Hồng đậm */
    font-size: 32px;
    margin-bottom: 20px;
    text-align: center;
    animation: neon-glow 1.5s infinite alternate;
}

@keyframes neon-glow {
    from {
        text-shadow: 0 0 5px #ff3399, 0 0 15px #ff3399, 0 0 25px #ff3399; /* Hồng đậm */
    }
    to {
        text-shadow: 0 0 10px #ff66b3, 0 0 20px #ff66b3, 0 0 35px #ff66b3; /* Hồng nhạt hơn */
    }
}

/* Container sản phẩm */
.container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); /* Tăng min-width */
    gap: 25px; /* Tăng khoảng cách */
    padding: 25px;
}

/* Sản phẩm */
.product {
    background: #1e1e1e; /* Đen xám */
    padding: 20px; /* Tăng padding */
    border-radius: 12px;
    text-align: center;
    color: #fff;
    box-shadow: 0 0 15px rgba(255, 51, 153, 0.4); /* Hồng nhạt */
    transition: all 0.4s ease;
    width: 100%; /* Đảm bảo sản phẩm full width trong grid */
}

.product:hover {
    background: #292929; /* Màu nền sáng hơn */
    box-shadow: 0 0 25px rgba(255, 51, 153, 0.7); /* Hồng nhạt, tăng sáng */
    transform: scale(1.05); /* Phóng to nhẹ */
}

/* Căn chỉnh lại hình ảnh */
.product img {
    width: 200px;           /* Cố định chiều rộng */
    height: 280px;          /* Cố định chiều cao */
    object-fit: cover;      /* Giúp ảnh lấp đầy khung, không bị méo */
    border-radius: 10px;
    margin-bottom: 12px;
    display: block;
}


/* Tăng kích thước tiêu đề sản phẩm */
.product h3 {
    font-size: 18px; /* Tăng cỡ chữ */
    font-weight: bold;
    color: #ff99cc; /* Hồng nhạt */
    margin-bottom: 8px;
    text-transform: capitalize;
}

/* Căn chỉnh giá */
.product p {
    color: #ff3399; /* Hồng đậm */
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 15px;
    text-shadow: 0 0 5px rgba(255, 51, 153, 0.5); /* Hồng nhạt */
}

/* Nút Add to Cart */
.btn {
    display: inline-block;
    background: linear-gradient(45deg, #ff3399, #ff66b3); /* Gradient hồng đậm -> hồng nhạt */
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    border: none;
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 0 10px rgba(255, 51, 153, 0.5); /* Hồng nhạt */
    outline: none;
}

/* Hover effect */
.btn:hover {
    background: linear-gradient(45deg, #cc0066, #ff3399); /* Gradient hồng đậm hơn */
    transform: scale(1.1);
    box-shadow: 0 0 20px rgba(255, 51, 153, 0.8); /* Hồng nhạt, tăng sáng */
}

/* Hiệu ứng khi bấm */
.btn:active {
    transform: scale(0.95);
    box-shadow: 0 0 15px rgba(255, 51, 153, 0.9); /* Hồng nhạt */
}



    </style>
</head>
<body>

<body>
<div class="wrapper">
    <h2>Psychology - Gender Studies</h2>
    <div class="container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                <h3><?= $row['name'] ?></h3>
                <p>Price: <?= number_format($row['price'], 0, ',', '.') ?> ₫</p>

                <!-- Form thêm sản phẩm vào giỏ hàng -->
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="product_name" value="<?= $row['name'] ?>">
                    <input type="hidden" name="product_price" value="<?= $row['price'] ?>">
                    <input type="hidden" name="product_image" value="<?= $row['image'] ?>">
                    
                    <!-- Nút thêm sản phẩm -->
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php include 'footer.html'; ?>

</body>


</body>
</html>

<?php $connect->close(); ?>
