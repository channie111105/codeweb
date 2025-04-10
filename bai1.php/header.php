<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
    .topbar {
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: #7a1c32;
    padding: 10px 20px;
    color: white;
    font-size: 16px;
    height: 40px;
    display: flex;
}

.topbar-left {
    display: flex;
    gap: 60px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 40px;
}
/* ======= HEADER (Cố định trên đầu) ======= */
.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #111;
    padding: 15px 20px;
    position: absolute; /* Mặc định ở dưới topbar */
    width: 100vw; /* Chiều ngang toàn màn hình */
    height: 80px; /* Chiều cao mặc định */
    top: 40px; /* Độ cao của topbar */
    left: 0;
    z-index: 1000;
    transition: all 0.3s ease-in-out;
}
.shrink {
    position: fixed; /* Khi cuộn thì dính lên */
    top: 0;
    height: 70px; /* Giảm chiều cao */
    padding: 15px 20px; /* Giảm padding */
    background-color: rgba(0, 0, 0, 0.8); /* Làm header mờ nhẹ */
    transform: scale(1); /* Không thu nhỏ */
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
}


.logo a {
    font-size: 35px;
    font-weight: bold;
    text-decoration: none;
    color: white;
}

/* Search Box */
.search-box {
    display: flex;
    width: 40%;
}

.search-box input {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 5px 0 0 5px;
    font-size: 16px;
    height: 42px;
}

.search-box button {
    padding: 12px 20px;
    border: none;
    background-color: #e6007e;
    color: white;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-size: 16px;
    height: 42px;
}

/* Hotline */
.hotline {
    color: white;
    text-align: right;
    font-weight: bold;
}

.hotline span {
    color: #ff0099;
}

/* Cart */
.cart {
    font-size: 18px;
    font-weight: bold;
    color: white;
    background: linear-gradient(135deg, #ff66b2, #d147a3);
    padding: 12px 18px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.15);
}

.cart:hover {
    background: linear-gradient(135deg, #d147a3, #ff66b2);
    color: white;
    transform: scale(1.05);
    box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
    animation: shake 0.3s ease-in-out;
}

@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-3px); }
    50% { transform: translateX(3px); }
    75% { transform: translateX(-3px); }
    100% { transform: translateX(0); }
}

    </style>
<div class="topbar">
            <div class="topbar-left">
                <span>📞 <b>19006401</b></span>
                <span>📧 <b>Changggfpt@gmail.com</b></span>
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
                <span>📞 Sales consulting</span>
                <b>19006401</b>
            </div>
            <div class="cart">
                <a href="cart.php">🛒 <span class="cart-count"><?= $cart_count ?></span></a>
            </div>

        </div>
</body>
</html>
<script>
    window.addEventListener("scroll", function() {
        var header = document.querySelector(".header");
        if (window.scrollY > 40) { // Nếu cuộn xuống hơn 40px
            header.classList.add("shrink");
        } else {
            header.classList.remove("shrink");
        }
    });
</script>
