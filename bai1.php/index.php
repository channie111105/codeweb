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
                <span>📧 <b>Changggfpt@gmail.com</b></span>
                <span>📍 13 Trịnh Văn Bô, Phương Canh, Nam Từ Liêm, Hà Nội</span>
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
                        <a href="manageproducts.php"> Quản lý</a>
                    <?php endif; ?>
                    <a href="logout.php"> Đăng xuất</a> 
                <?php else: ?>
                    <a href="login.php"> ĐĂNG NHẬP</a>
                    <a href="register.php"> ĐĂNG KÝ</a>
                <?php endif; ?>
            </div>

        </div>

        <!-- LOGO & SEARCH -->
        <div class="header">
            <div class="logo">
                <a href="index.php"><span style="color: pink;">Book</span><span style="color: white;">store</span><span style="color: pink;">.com</span></a>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm sản phẩm...">
                <button>Tìm kiếm</button>
            </div>
            <div class="hotline">
                <span>📞 Tư vấn bán hàng</span>
                <b>19006401</b>
            </div>
            <div class="cart">
                <a href="cart.php">🛒 <span class="cart-count"><?= $cart_count ?></span></a>
            </div>

        </div>
    </header>
    <section id="books">
        <?php
        // Kiểm tra loại tài khoản
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $welcome_message = "Chào mừng Admin";
            } else {
                $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'bạn';
                $welcome_message = "Chào mừng khách hàng <span style='color: yellow;'>" . htmlspecialchars($username) . "</span>";
            }
        ?>

        <div class="marquee">
            <h1><?= $welcome_message ?> đến với BookStore</h1>
        </div>
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
        <!-- ====DANH MUC SAN PHAM===== -->
        <div class="sidebar">
        <h1 class="sidebar-title">Danh mục</h3>
        <div class="menu-item" onclick="toggleMenu('sach-kinh-te')">Sách Kinh Tế ▶</div>
        <div class="submenu" id="sach-kinh-te">
            <a href="#">Marketing - Bán Hàng</a>
            <a href="#">Nhân Sự & Việc Làm</a>
            <a href="#">Nhân Vật & Bài Học Kinh Doanh</a>
            <a href="#">Phân Tích & Môi Trường Kinh Kế</a>
            <a href="#">Quản Trị & Lãnh Đạo</a>
            <a href="#">Tài Chính & Tiền Tệ</a>
            <a href="#">Tài Chính & Kế Toán</a>
            <a href="#">Văn Bản Luật</a>
            <a href="#">Khởi Nghiệp & Kĩ Năng Làm Việc</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-van-hoc-trong-nuoc')">Sách Văn Học Trong Nước ▶</div>
        <div class="submenu" id="sach-van-hoc-trong-nuoc">
        <a href="#">Nhân Vật Văn Học/Nhân Vật Lịch Sử</a>
            <a href="#">Phê Bình Văn Học</a>
            <a href="#">Phóng Sự, Ký Sự</a>
            <a href="#">Thơ Ca</a>
            <a href="#">Tiểu Thuyết</a>
            <a href="#">Tiểu Thuyết Lịch Sử</a>
            <a href="#">Truyện & Thơ Ca Dân Gian</a>
            <a href="#">Truyện Dài</a>
            <a href="#">Truyện Giả Tưởng – Thần Bí</a>
            <a href="#">Truyện Kiếm Hiệp</a>
            <a href="#">Truyện Ngắn – Tản Văn</a>
            <a href="#">Truyện Thiếu Nhi</a>
            <a href="#">Truyện Trinh Thám, Vụ Án</a>
            <a href="#">Tự Truyện - Hồi Ký</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-van-hoc-nuoc-ngoai')">Sách Văn Học Nước Ngoài ▶</div>
        <div class="submenu" id="sach-van-hoc-nuoc-ngoai">
            <a href="#">Cổ Tích & Thần Thoại</a>
            <a href="#">Phê Bình Văn Học</a>
            <a href="#">Phóng Sự, Ký Sự</a>
            <a href="#">Sách Về Nhân Vật Văn Học/Nhân Vật Lịch Sử</a>
            <a href="#">Thơ Ca</a>
            <a href="#">Tiểu Thuyết</a>
            <a href="#">Truyện Kiếm Hiệp - Phiêu Lưu</a>
            <a href="#">Truyện Lịch Sử</a>
            <a href="#">Truyện Ngắn</a>
            <a href="#">Truyện Thiếu Nhi</a>
            <a href="#">Truyện Trinh Thám, Vụ Án</a>
            <a href="#">Truyện Viễn Tưởng - Kinh Dị</a>
            <a href="#">Tự Truyện - Hồi Ký</a>
            <a href="#">Tiểu Thuyết Ngôn Tình</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-thuong-thuc-doi-song')">Sách Thưởng Thức Đời Sống ▶</div>
        <div class="submenu" id="sach-thuong-thuc-doi-song">
            <a href="#">Bí Quyết Làm Đẹp</a>
            <a href="#">Gia Đình, Nuôi Dạy Con</a>
            <a href="#">Nhà Ở, Vật Nuôi</a>
            <a href="#">Sách Tâm Lý - Giới Tính</a>
            <a href="#">Nữ Công Gia Chánh</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-thieu-nhi')">Sách Thiếu Nhi ▶</div>
        <div class="submenu" id="sach-thieu-nhi">
            <a href="#">Khoa Học Tự Nhiên</a>
            <a href="#">Khoa Học Xã Hội</a>
            <a href="#">Mỹ Thuật, Âm Nhạc</a>
            <a href="#">Ngoại Ngữ</a>
            <a href="#">Truyện Tranh</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-phat-trien-ban-than')">Sách Phát Triển Bản Thân ▶</div>
        <div class="submenu" id="sach-phat-trien-ban-than">
            <a href="#">Sách Học LÀm Người</a>
            <a href="#">Danh Nhân</a>
            <a href="#">Tâm lý & Kỹ Năng Sống</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-tin-hoc-ngoai-ngu')">Sách Tin Học Ngoại Ngữ ▶</div>
        <div class="submenu" id="sach-tin-hoc-ngoai-ngu">
            <a href="#">Sách Ngoại Ngữ</a>
            <a href="#">Từ Điển</a>
            <a href="#">Tin Học</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-chuyen-nganh')">Sách Chuyên Ngành ▶</div>
        <div class="submenu" id="sach-chuyen-nganh">
            <a href="#">Âm Nhạc</a>
            <a href="#">Chính Trị, Triết Học</a>
            <a href="#">Du Lịch</a>
            <a href="#">Khoa Học Cơ Bản</a>
            <a href="#">Khoa Học Kỹ Thuật</a>
            <a href="#">Khoa Học Tự Nhiên - Xã Hội</a>
            <a href="#">Mỹ Thuật, Kiến Trúc</a>
            <a href="#">Nông Lâm Nghiệp</a>
            <a href="#">Pháp Luật</a>
            <a href="#">Sách Học Nghề</a>
            <a href="#">Sách Tôn Giáo</a>
            <a href="#">Thể Thao</a>
            <a href="#">Văn Hoá Nghệ Thuật</a>
            <a href="#">Y Học</a>
            <a href="#">Nghiệp Vụ Báo Chí</a>
            <a href="#">Các Chủ Đề Khác</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-giao-khoa-giao-trinh')">Sách Giáo Khoa - Giáo Trình ▶</div>
        <div class="submenu" id="sach-giao-khoa-giao-trinh">
            <a href="#">Cấp 1</a>
            <a href="#">Cấp 2 </a>
            <a href="#">Cấp 3 </a>
            <a href="#">Sách Tham Khảo</a>
            <a href="#">Đại Học</a>
            <a href="#">Bộ Sách Giáo Khoa</a>
        </div>
        <div class="menu-item" onclick="toggleMenu('sach-moi-2025')">Sách Mới 2025 ▶</div>
        <div class="submenu" id="sach-moi-2025">
            <a href="#">Sách Mới Tháng 1</a>
            <a href="#">Sách Mới Tháng 2</a>
            <a href="#">Sách Mới Tháng 3</a>
        </div>
        <div class="menu-item">Review Sách</div>
        </div>
        <?php
        $connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');
        if ($connect->connect_error) {
            die("Kết nối thất bại: " . $connect->connect_error);
        }

        $result = $connect->query("SELECT * FROM products WHERE category = 'Sách nổi bật'");
        ?>

        <h2>Sách Nổi Bật</h2>
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
                            <button type="submit">Thêm vào giỏ hàng</button>
                        </form>
                    </div>
                <?php endwhile; ?>
        </div>
        <?php
        $connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');
        if ($connect->connect_error) {
            die("Kết nối thất bại: " . $connect->connect_error);
        }

        $result = $connect->query("SELECT * FROM products WHERE category = 'Sách Văn Học'");
        ?>

        <div class="slider-header">
            <h7>Sách Văn Học</h7>
            <div class="slider-buttons">
                <button class="prev-btn">❮</button>
                <button class="next-btn">❯</button>
            </div>
        </div>
        <div class="vanhoc-slider-container">
        <div class="slider-track">
            <?php while ($book = mysqli_fetch_assoc($result)): ?>
                <div class="vanhoc-book">
                <div class="discount-badge">-10%</div>
                <img src="<?= $book['image'] ?>" alt="<?= $book['name'] ?>">
                <h3><?= $book['name'] ?></h3>
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
                    <button type="submit">Thêm vào giỏ hàng</button>
                </form>

            </div>
            <?php endwhile; ?>
        </div>
        </div>
        <?php
        $connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');
        if ($connect->connect_error) {
            die("Kết nối thất bại: " . $connect->connect_error);
        }

        $result = $connect->query("SELECT * FROM products WHERE category = 'Sách Tâm Lý - Kỹ Năng Sống'");
        ?>
        <div class="slider-header">
            <h8>Sách Tâm Lý - Kỹ Năng Sống</h8>
            <div class="slider-buttons">
                <button class="prev-btn">❮</button>
                <button class="next-btn">❯</button>
            </div>
        </div>
        <div class="sach-tam-ly-ky-nang-song-slider-container">
        <div class="slider-track">
            <?php while ($book = mysqli_fetch_assoc($result)): ?>
                <div class="tamlykynangsong-book">
                <div class="discount-badge">-15%</div>
                <img src="<?= $book['image'] ?>" alt="<?= $book['name'] ?>">
                <h3><?= $book['name'] ?></h3>
                <p><?= $book['description'] ?></p>
                <p class="price">
                    <span class="discount-price"><?= number_format($book['price'] * 0.85, 0, ',', '.') ?>đ</span>
                    <span class="old-price"><?= number_format($book['price'], 0, ',', '.') ?>đ</span>
                </p>
                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $book['id'] ?>">
                    <input type="hidden" name="product_name" value="<?= $book['name'] ?>">
                    <input type="hidden" name="product_price" value="<?= $book['price'] ?>">
                    <input type="hidden" name="product_image" value="<?= $book['image'] ?>">
                    <button type="submit">Thêm vào giỏ hàng</button>
                </form>

            </div>
            <?php endwhile; ?>
        </div>
        </div>       

</div>
    <div>
    </section>
    <footer id="contact">
        <div class="footer-container">
            <div class="footer-info">
                <h3>Về Chúng Tôi</h3>
                <p>Chúng tôi cung cấp những cuốn sách hay nhất với giá cả hợp lý. Sứ mệnh của chúng tôi là mang tri thức đến với mọi người.</p>
            </div>
            <div class="footer-contact">
                <h3>Liên Hệ</h3>
                <p>Email: changggfpt@gmail.com</p>
                <p>Hotline: 0123 456 789</p>
                <p>Địa chỉ: 13 Trịnh Văn Bô, Phương Canh, Nam Từ Liêm, Hà Nội </p>
            </div>
            <div class="footer-social">
                <h3>Kết Nối Với Chúng Tôi</h3>
                <a href="https://www.facebook.com/share/18Kb57peB1/">Facebook</a> | 
                <a href="https://l.messenger.com/l.php?u=https%3A%2F%2Fwww.instagram.com%2Fkiuchengg%2Fprofilecard%2F%3Figsh%3DMXEzaWNoNjhnbHZxMA%253D%253D&h=AT1iO1-ZTlS55jH7YeIzUbR8gJSc6stTUvM3SaXnIlWueebCRh2fDkPvAUkK0JdFBCZatnuGfimU80ZTsuWJOGwdzYVeRRMcrr-R4gl8i_EWm-uj8WSW3_y-zMz4poyYJV_Yjg">Instagram</a> | 
            </div>
            <div class="footer-payment">
                <h3>Phương Thức Thanh Toán</h3>
                <p>Chúng tôi chấp nhận thanh toán qua Momo, ZaloPay, Visa, Mastercard, và chuyển khoản ngân hàng.</p>
            </div>
        </div>
        <p class="footer-copyright">© 2025 Nhà Sách Online. All rights reserved.</p>
    </footer>
</body>
</html>





