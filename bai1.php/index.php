<?php
// Kết nối CSDL
$connect = mysqli_connect('localhost', 'root', '', 'se07102_sdlc');
if (!$connect) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy danh sách sản phẩm từ CSDL
$sql = "SELECT * FROM products";
$result = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - BookStore</title>
    <link rel="stylesheet" href="index.css">
    <script src="script.js"></script>
</head>
<body>
    <header>
        <!-- ====TOP BAR==== -->
        <div class="topbar">
            <div class="topbar-left">
                <span>📞 <b>19006401</b></span>
                <a href="mailto:Changggfpt@gmail.com?subject=Hello&body=I%20want%20to%20ask%20about...">Changggfpt@gmail.com</a>
                <span>📍 13 Trinh Van Bo, Phuong Canh, Nam Tu Liem, Hanoi</span>
            </div>
            <?php
            session_start();
            ?>
            <?php
                $cart_count = 0;

                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $sql_cart = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = '$user_id'";
                    $result_cart = mysqli_query($connect, $sql_cart);
                    if ($result_cart) {
                        $row_cart = mysqli_fetch_assoc($result_cart);
                        $cart_count = $row_cart['total_items'] ?? 0;
                    }
                }
            ?>
            <div class="topbar-right">
                <?php if (isset($_SESSION['role'])): ?>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="manageproducts.php"> Manage</a>
                    <?php endif; ?>
                    <a href="logout.php"> Log Out</a> 
                <?php else: ?>
                    <a href="login.php"> Log In</a>
                    <a href="register.php"> Register</a>
                <?php endif; ?>
            </div>

        </div>

        <!-- LOGO & SEARCH -->
        <div class="header">
            <div class="logo">
                <a href="index.php"><span style="color: pink;">Book</span><span style="color: white;">store</span><span style="color: pink;">.com</span></a>
            </div>
            <form action="search.php" method="GET" class="search-box">
                <input type="text" name="search" placeholder="Search Products..." required>
                <button type="submit">Search</button>
            </form>


            <div class="hotline"> 
            <?php if (isset($_SESSION['username'])): ?>
                <span>👋 Hello,</span>
                <b>
                    <?php 
                        echo ($_SESSION['role'] === 'admin') 
                            ? 'Admin' 
                            : htmlspecialchars($_SESSION['username']);
                    ?>
                </b>
            <?php else: ?>
                <span>📞 Sales consulting</span>
                <b>19006401</b>
            <?php endif; ?>
        </div>
            
            <div class="cart">
                <a href="cart.php">🛒 <span class="cart-count"><?= $cart_count ?></span></a>
            </div>

        </div>
    </header>
    <section id="books">
    <div class="container">
            <!-- ========SLIDE SHOW===== -->
        <div class="slideshow-container">
            <div class="slide fade">
                <img src="https://theme.hstatic.net/200000845405/1001223012/14/home_slider_image_2.jpg?v=424" alt="Slide 1">
            </div>
            <div class="slide fade">
                <img src="https://theme.hstatic.net/200000845405/1001223012/14/home_slider_image_1.jpg?v=424" alt="Slide 2">
            </div>
            <div class="slide fade">
                <img src="https://theme.hstatic.net/200000845405/1001223012/14/home_slider_image_4.jpg?v=424" alt="Slide 3">
            </div>

            <!-- Nút điều hướng -->
            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>

            <!-- Chấm chỉ số -->
            <div class="dots-container">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
        </div>
<!-- ====PRODUCT CATEGORY===== -->
<div class="sidebar">
    <h1 class="sidebar-title">Categories</h1>

    <div class="menu-item">
        Economics Books ▶
        <div class="submenu">
            <a href="marketing_sales.php">Marketing - Sales</a>
            <a href="Human_Resources_Employment.php">Human Resources & Employment</a>
            <a href="Business_Figures_Lessons.php">Business Figures & Lessons</a>
            <a href="Economic_Analysis_Environment.php">Economic Analysis & Environment</a>
            <a href="Management_Leadership.php">Management & Leadership</a>
            <a href="Finance_Currency.php">Finance & Currency</a>
            <a href="Entrepreneurship_Work_Skills.php">Entrepreneurship & Work Skills</a>
        </div>
    </div>

    <div class="menu-item">
        Domestic Literature Books ▶
        <div class="submenu">
            <a href="Literary_Historical_Figures.php">Literary/Historical Figures</a>
            <a href="Novels.php">Novels</a>
            <a href="Historical_Novels.php">Historical Novels</a>
            <a href="Folk_Tales_Poetry.php">Folk Tales & Poetry</a>
            <a href="Short_Stories_Essays.php">Short Stories - Essays</a>
            <a href="Childrens_Stories.php">Children's Stories</a>
        </div>
    </div>

    <div class="menu-item">
        Foreign Literature Books ▶
        <div class="submenu">
            <a href="Reports_Chronicles.php">Reports, Chronicles</a>
            <a href="Books_About_Literary_Historical_Figures.php">Books About Literary/Historical Figures</a>
            <a href="Poetry.php">Poetry</a>
            <a href="Historical_Stories.php">Historical Stories</a>
            <a href="Short_Stories.php">Short Stories</a>
        </div>
    </div>

    <div class="menu-item">
        Lifestyle Books ▶
        <div class="submenu">
            <a href="Family_Parenting.php">Family, Parenting</a>
            <a href="Psychology_Gender_Studies.php">Psychology - Gender Studies</a>
            <a href="Home_Economics.php">Home Economics</a>
        </div>
    </div>

    <div class="menu-item">
        Children's Books ▶
        <div class="submenu">
            <a href="Natural_Sciences.php">Natural Sciences</a>
            <a href="Social_Sciences.php">Social Sciences</a>
            <a href="Fine_Arts_Music.php">Fine Arts, Music</a>
        </div>
    </div>
</div>


        <?php
        $connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');
        if ($connect->connect_error) {
            die("Kết nối thất bại: " . $connect->connect_error);
        }

        $result = $connect->query("SELECT * FROM products WHERE category = 'Prominent book'");        ?>

        <h2>Prominent book</h2>
        <div class="book-list">
            <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <div class="book">
                        <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                        <h3><?= $product['name'] ?></h3>
                        <p>Giá: <?= number_format($product['price'], 0, ',', '.') ?> VND</p>
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="product_name" value="<?= $product['name'] ?>">
                            <input type="hidden" name="product_price" value="<?= $product['price'] ?>">
                            <input type="hidden" name="product_image" value="<?= $product['image'] ?>">
                            <button type="submit">Add cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
        </div>
        <?php
$connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');
if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

// Danh mục sách cần hiển thị
$categories = [
    'Literary book' => 'vanhoc',
    'Psychological books - Life skills' => 'tamlykynangsong',
    'Economic book' => 'kinhte',
    'Children book' => 'thieunhi',
    'Psychological books - Gender' => 'tamlygioitinh'
];

foreach ($categories as $category => $class) {
    $result = $connect->query("SELECT * FROM products WHERE category = '$category'");
?>
    <div class="slider-header">
        <h7><?= $category ?></h7>
        <div class="slider-buttons">
            <button class="prev-btn" data-slider="<?= $class ?>">❮</button>
            <button class="next-btn" data-slider="<?= $class ?>">❯</button>
        </div>
    </div>

    <div class="<?= $class ?>-slider-container slider-container">
        <div class="slider-track" id="<?= $class ?>-track">
            <?php while ($book = mysqli_fetch_assoc($result)): ?>
                <div class="books <?= $class ?>-books">
                    <div class="discount-badge">-10%</div>
                    <img src="<?= $book['image'] ?>" alt="<?= $book['name'] ?>">
                    <h8><?= $book['name'] ?></h8>
                    <p><?= $book['description'] ?></p>
                    <p class="price">
                        <span class="discount-price"><?= number_format($book['price'] * 0.9, 0, ',', '.') ?>đ</span>
                        <span class="old-price"><?= number_format($book['price'], 0, ',', '.') ?>đ</span>
                    </p>
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $book['id'] ?>">
                        <input type="hidden" name="product_name" value="<?= $book['name'] ?>">
                        <input type="hidden" name="product_price" value="<?= $book['price'] ?>">
                        <input type="hidden" name="product_image" value="<?= $book['image'] ?>">
                        <button type="submit">Add Cart</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php
}
?>

<!-- Đánh giá từ người dùng Section -->
 <style>
.user-reviews {
    background-color: #111;
    color: #fff;
    padding: 15px;
    border-radius: 20px; /* Bo tròn toàn bộ */
    margin: 20px 0;
    width: 23%;
    margin-left: 10px;
    margin-top: -4080px;
}

.user-reviews h5 {
    color: #ff69b4;
    font-size: 20px;
    text-align: center;
    margin-bottom: 10px;
}

.review-container {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.review {
    background-color: #222;
    padding: 12px;
    border-radius: 15px; /* Bo tròn từng review */
    box-shadow: 0 0 5px rgba(255, 105, 180, 0.5);
}

.name {
    font-weight: bold;
    font-size: 16px;
    color: #ff69b4;
}

.rating {
    font-size: 16px;
    color: #ffcc00;
    margin: 3px 0;
}

.comment {
    font-size: 14px;
    line-height: 1.2;
    color: #ddd;
}



 </style>
    <div class="user-reviews">
        <h5>User Reviews</h5>
        <div class="review-container">
            <div class="review">
                <div class="name">Nguyen Thanh Tung</div>
                <div class="rating">★★★★★</div>
                <div class="comment">Great product, good quality, fast delivery!</div>
            </div>
            <div class="review">
                <div class="name">Avicii</div>
                <div class="rating">★★★★☆</div>
                <div class="comment">Good service but delivery is a bit slow.</div>
            </div>
            <div class="review">
                <div class="name">Lalisa Manobal</div>
                <div class="rating">★★★★★</div>
                <div class="comment">The product matches the description and I am very satisfied!</div>
            </div>
            <div class="review">
                <div class="name">Trinh Tran Phuong Tuan</div>
                <div class="rating">★★★☆☆</div>
                <div class="comment">Decent quality but packaging needs improvement.</div>
            </div>
            <div class="review">
                <div class="name">Lionel Andres Messi</div>
                <div class="rating">★★★★★</div>
                <div class="comment">I really like this product, I will buy it again!</div>
            </div>
        </div>
</div>
<div class="voucher-section">
    <!-- Phần nhập mã giảm giá -->
    <div id="voucher-input-section">
        <h3>🎁 Enter Discount Code</h3>
        <input type="text" id="voucher-code" placeholder="Enter your code here">
        <button onclick="applyVoucher()">Apply</button>
        <p id="voucher-message"></p>

        <h4>🎟️ Available Discount Codes</h4>
        <div id="voucher-list">
            <button onclick="saveVoucher('SALE10')">SALE10 - 10% Off</button>
            <button onclick="saveVoucher('FREESHIP')">FREESHIP - Free Shipping</button>
            <button onclick="saveVoucher('DISCOUNT50')">DISCOUNT50 - 50% Off</button>
        </div>

        <h4>✅ Saved Codes</h4>
        <div id="saved-vouchers"></div>
    </div>

    <!-- Phần hiển thị ưu đãi, khuyến mãi -->
    <div id="promotion-section" style="display: none;">
        <h3>🔥 Hot Promotions</h3>
        <p>🚀 Buy 1 Get 1 Free on Bestsellers!</p>
        <p>💳 Extra 5% off when paying with Visa/MasterCard</p>
        <p>🛒 Free Shipping for orders above $50</p>
        <p>🎉 Members get exclusive discounts!</p>
    </div>
</div>


<style>
.voucher-section {
    text-align: center;
    padding: 15px;
    background: #2a2b2c; /* Dark gray-black */
    border-radius: 10px;
    margin-top: 20px;
    margin-left: 20px;
    width: 21%;
    transition: opacity 0.5s ease-in-out;
    height: 350px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.voucher-section input {
    padding: 5px;
    width: 200px;
    margin-right: 5px;
    border: 2px solid #ff1493; /* Deep pink */
    border-radius: 5px;
    background: #1a1b1c;
    color: #fff;
}

.voucher-section input:focus {
    outline: none;
    border-color: #ff69b4; /* Hot pink */
}

.voucher-section button {
    background: linear-gradient(45deg, #ff1493, #ff69b4); /* Pink gradient */
    color: white;
    border: none;
    padding: 6px 10px;
    cursor: pointer;
    margin: 5px;
    border-radius: 5px;
}

#voucher-list button {
    background: linear-gradient(45deg, #ff69b4, #ff1493);
}

#saved-vouchers {
    margin-top: 10px;
}

.saved-voucher {
    display: inline-block;
    background: linear-gradient(45deg, #ff1493, #c71585); /* Pink to deep pink */
    color: white;
    padding: 5px;
    margin: 5px;
    cursor: pointer;
    border-radius: 5px;
}

/* Promotion Section */
#promotion-section {
    background: linear-gradient(135deg, #1a1b1c, #ff1493); /* Black to deep pink */
    color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(255, 20, 147, 0.2);
    text-align: left;
    width: 95%;
    margin: auto;
    max-height: 320px;
    margin-top: 15px;
}

#promotion-section h3 {
    color: #fff;
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    text-align: center;
}

#promotion-section p {
    font-size: 14px;
    margin: 5px 0;
    padding: 6px;
    background: rgba(255, 20, 147, 0.85); /* Deep pink */
    border-radius: 5px;
    display: flex;
    align-items: center;
}

#promotion-section p::before {
    content: "🔥";
    margin-right: 8px;
}


</style>
<div class="publisher-container">
    <h2>📚 Publisher</h2>
    <div class="publisher-logos">
        <a href="https://www.nxbtre.com.vn/" target="_blank">
            <img src="data:image/png;base64,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" alt="NXB Trẻ">
        </a>
        <a href="https://nxbkimdong.com.vn/" target="_blank">
            <img src="data:image/png;base64,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" alt="NXB Kim Đồng">
        </a>
        <a href="https://nxbhanoi.com.vn/" target="_blank">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAgVBMVEXFAAD////CAADMLCzYfX3eiYnpqanLIyPjo6PMMjLz1NTjmpr44ODHAADegoLtvLzzzc3bd3fPRkb99/fOPj7ora3GCQnjlpbTXV3qs7P019fTUVH34uLjn5/JFhb56enTWFjXZ2f98/PyxMTQREThj4/acXHIHR3HERHOOTnWaWmrNuy2AAAFjUlEQVR4nO3daXeqOhQG4GRjVVQcihaIA9WeU4/3///ACw6MCXYQ4t5rvx+RpnlWwEggQci78Tez7WLt7N7E8+Tt6Kx7wyj271df3PlcBaEHSWyTNEmr5YWBuqNsFKqp+5S2YgDcqfqhcHV6et4lCTL6vtDvezh4l4A3PnxPOBxg8qWBXf8bQrXE5ksDS+35qBMuMPrSwMuXhMrFCkyITr0Za8LIdi1/mdq3alX4grcBL4FpszDEDkyI+ybhO35gQnw3C0kAq8SicE8DmBBDvXBKBVjuGHNhRAeYEKO6UNmu1IOjakLHdpUeHKcqRN/TVwOLslBRAyZEVRIubdenhSyLwj69JkwacZgLDzvbtWklAz8Tjik2YdKI/Uzo2a5LS/FuQlK/ZoqB1VXo2q5JazldhAT7wlvSPlGQuqaoJh3SENKne5AK4aZCwgfp+TAVMiAtDBJhaLsWrSaUwqfa3V/i+SKmfJAmh+lGkP1BcwnMxJC4cCt6xIULsbZdh5azFtTG2KpxxNF2FVrOTjzTk05thLqPw+GgDdhLR8Jpz1am3QBB2ks3jchCFiIRBv8G3eYt6ljY+X3/y60yFj7of92Ej++WG0q0IXQ2E102P7/TAx/aEifpeKANoWv4vvv5PXPY6EtUmISwbvgUJvoSR5iEs6YnW/ALwUkJ86NpB/RCCC/TzA5/DHtgF8LfbBfDnBXswllhH/3JiFoIg7hcad3UOMxC2FdnesaakxGxsHAK5vms7YdY+KHdcUhFCK6h4rWeEanw1gvqEr+W9kUpBAiMvjSL4sUWMuH5wu74abhcyKL2u+yiEpcQos1EKdME62IOSk025z/CJRTH8egrvrNxFPwT+ITnw1QYppAXshLZyAc24SWxYa9b4sJtaJRC+My2z0d55lkPssf7XXrN6bbZL42e3aaW+aWSUQqXubBYRiYsPhrJQhaykIUsZCELWchCFrKQhSxkIQtZyEIWspCFtIRBaeg3W3PLMCJc2opEGM+LGWXbS5v95q3PLXx8WMjCRwvj4m20b2WORFj+Lv1WkAh/8Zw3C1n4oLCQhXdKZmEHYWEubO7e8QtrbynI48fzaL+rVZeQ8JxD3wFNyYSESVP+hXrJpIRJ3ckLZbyrlkxNWFrPn6awOMmLqFC+khdOoFQyQWHeiJiFq3Ki0qu1sjMRs7D2E/W18Az/gYKwttAtuPkIvn+iKBSwzT/dkxSKdf7pbTYpMeFr/umChSxkIQtZyEIWspCFLGQhC1nIQhaykIUsZCELWchCFrKQhSxsFIr/RtnkJ43wlE+NCosla2JrTfbBx0yXj3xt2aYnLXWfgrbA2WxsSUj/3QjdpnPhtPn53xbStXC+HXabrepYaCksZCELr3EdW3G7AXI4HA6nGurvrX4Tu/s7oc5ROLar0HIcsbZdhZazFr2uL9a7DfTElrhwK2bEhZHYEBfGwtcM1hKK5wsZ3t8NcUIpZED5MIUgESrSQpUIJeWhAddPhT9/Q/HTJ73BJyTlwzRdnjERyqXtirSW9BZnKlxRbUSIrkLdPWgS8eRN2PAiacyBcSb0B7Yr00p2h0wohxQb8XJP9Sok+XV6XcH4KiTYJ96WKr4KTe8Cxxt4kWWhpDbmlq1BnQnV/T9CFVUTyojScXr+NVMVdv8QUHspLatSuEkeUiHCXuqF8p0GEd6lSeiTIMK7bxSW312LNBCWSRUh/iGNrKc3CWVku4q/TFQF1YRSVdfcRBRwVM1TFyLuGGGh0eiEUi0xGmFZb0CTUMp+fQncJw8MhnqKQSgPYw+TEby+4Q0uRmGSyP3NY/cdBuC0MjMahMn5OH1+JIA71Z5/XxImv+NUEHrXGQ/PlrRaXhg08u4Lz8o4GvbW7vGZnp562znr3na2MZ18hfwPO8ORGLwwOnQAAAAASUVORK5CYII=" alt="NXB Hà Nội">
        </a>
        <a href="https://www.nxbphunu.com.vn/" target="_blank">
            <img src="data:image/png;base64,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" alt="NXB Phụ Nữ">
        </a>
    </div>
</div>

<style>
.publisher-container {
    text-align: center;
    padding: 5px;
    background: #2a2b2c; /* Dark gray-black */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(255, 20, 147, 0.2); /* Pink-tinted shadow */
    margin-top: 20px;
    margin-left: 12px;
    width: 21%;
}

.publisher-container h2 {
    margin-bottom: 15px;
    color: #ff69b4; /* Hot pink */
    font-size: 20px;
    margin-left: -290px;
}

.publisher-logos {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.publisher-logos a {
    display: inline-block;
    transition: transform 0.3s ease;
}

.publisher-logos img {
    width: 100px;
    height: auto;
    border-radius: 8px;
    box-shadow: 2px 2px 10px rgba(255, 20, 147, 0.3); /* Pink-tinted shadow */
    border: 1px solid #ff1493; /* Deep pink border */
}

.publisher-logos a:hover {
    transform: scale(1.1);
}
</style>

<footer id="contact">
    <div class="footer-container">
        <div class="footer-info">
            <h3>About Us</h3>
            <p>We provide the best books at reasonable prices. Our mission is to bring knowledge to everyone.</p>
        </div>
        <div class="footer-contact">
            <h3>Contact</h3>
            <p>Email: changggfpt@gmail.com</p>
            <p>Hotline: 0123 456 789</p>
            <p>Address: 13 Trinh Van Bo, Phuong Canh, Nam Tu Liem, Hanoi</p>
        </div>
        <div class="footer-social">
            <h3>Connect With Us</h3>
            <a href="https://www.facebook.com/share/18Kb57peB1/">Facebook</a> | 
            <a href="https://l.messenger.com/l.php?u=https%3A%2F%2Fwww.instagram.com%2Fkiuchengg%2Fprofilecard%2F%3Figsh%3DMXEzaWNoNjhnbHZxMA%253D%253D&h=AT1iO1-ZTlS55jH7YeIzUbR8gJSc6stTUvM3SaXnIlWueebCRh2fDkPvAUkK0JdFBCZatnuGfimU80ZTsuWJOGwdzYVeRRMcrr-R4gl8i_EWm-uj8WSW3_y-zMz4poyYJV_Yjg">Instagram</a> | 
        </div>
        <div class="footer-payment">
            <h3>Payment Methods</h3>
            <p>We accept payments via Momo, ZaloPay, Visa, Mastercard, and bank transfers.</p>
        </div>
    </div>
    <p class="footer-copyright">© 2025 Online Bookstore. All rights reserved.</p>
</footer>

<style>
    #chatButton {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: hotpink;
      color: white;
      padding: 10px 15px;
      border-radius: 25px;
      cursor: pointer;
      z-index: 9999;
      font-weight: bold;
    }

    #chatBox {
      position: fixed;
      bottom: 80px;
      right: 20px;
      width: 300px;
      background-color: white;
      border: 2px solid hotpink;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
      z-index: 9999;
      display: flex;
      flex-direction: column;

      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    #chatBox.show {
      opacity: 1;
      visibility: visible;
    }

    #chatHeader {
      background-color: hotpink;
      color: white;
      padding: 10px;
      font-weight: bold;
    }

    #chatMessages {
      height: 180px;
      padding: 10px;
      overflow-y: auto;
      font-size: 14px;
    }

    #chatInputArea {
      display: flex;
      border-top: 1px solid #ccc;
    }

    #chatInput {
      flex: 1;
      padding: 8px;
      border: none;
      outline: none;
    }

    #chatInputArea button {
      background-color: hotpink;
      border: none;
      color: white;
      padding: 8px 12px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<!-- Nút mở chat -->
<div id="chatButton">💬 Chat</div>

<!-- Chat Box -->
<div id="chatBox">
  <div id="chatHeader">📚 Bookstore Support</div>
  <div id="chatMessages"></div>
  <div id="chatInputArea">
    <input type="text" id="chatInput" placeholder="Type something..." />
    <button onclick="sendMessage()">Send</button>
  </div>
</div>

<script>
  const chatButton = document.getElementById("chatButton");
  const chatBox = document.getElementById("chatBox");

  let pinned = false; // Is the chat pinned?

  // Show on hover (if not pinned)
  chatButton.addEventListener("mouseenter", () => {
    if (!pinned) chatBox.classList.add("show");
  });

  chatBox.addEventListener("mouseenter", () => {
    if (!pinned) chatBox.classList.add("show");
  });

  // Hide on mouse leave (if not pinned)
  chatButton.addEventListener("mouseleave", () => {
    setTimeout(() => {
      if (!chatBox.matches(':hover') && !pinned) {
        chatBox.classList.remove("show");
      }
    }, 300);
  });

  chatBox.addEventListener("mouseleave", () => {
    setTimeout(() => {
      if (!chatButton.matches(':hover') && !pinned) {
        chatBox.classList.remove("show");
      }
    }, 300);
  });

  // Toggle pin on click
  chatButton.addEventListener("click", () => {
    pinned = !pinned;
    if (pinned) {
      chatBox.classList.add("show");
    } else {
      chatBox.classList.remove("show");
    }
  });

  function sendMessage() {
    const input = document.getElementById("chatInput");
    const message = input.value.trim();
    if (message !== "") {
      const msgContainer = document.getElementById("chatMessages");
      const newMsg = document.createElement("div");
      newMsg.textContent = "👤 You: " + message;
      msgContainer.appendChild(newMsg);
      input.value = "";

      setTimeout(() => {
        const reply = document.createElement("div");
        reply.textContent = "🤖 Bookstore: Thank you! We will get back to you soon.";
        msgContainer.appendChild(reply);
        msgContainer.scrollTop = msgContainer.scrollHeight;
      }, 1000);
    }
  }
</script>

</body>
</html>





