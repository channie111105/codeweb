<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "se07102_sdlc";    
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'customer')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $email);
    
    if ($stmt->execute()) {
        // Trả về JSON để JS xử lý
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Đăng ký thất bại: " . $stmt->error]);
        exit();
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form id="registerForm" method="POST">
       <h2>Register</h2>
       UserName: <input type="text" name="username" id="username" required><br>
       Password: <input type="password" name="password" id="password" required> <br>
       Email: <input type="email" name="email" id="email" required> <br>
       <input type="submit" value="Register">
       <p>Đã có tài khoản? <a href="login.php">Đăng nhập tại đây</a></p>
    </form>

    <script>
        // Tự động điền username nếu đã từng đăng ký
        document.addEventListener("DOMContentLoaded", function () {
            const savedRegisterUsername = localStorage.getItem('savedRegisterUsername');
            if (savedRegisterUsername) {
                document.getElementById('username').value = savedRegisterUsername;
            }
        });

        document.getElementById('registerForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;

    fetch('', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({
            username: username,
            password: password,
            email: email
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Lấy danh sách người dùng hiện tại từ Local Storage
            let users = JSON.parse(localStorage.getItem('users')) || [];

            // Thêm người dùng mới vào danh sách
            users.push({ username, password, email });

            // Lưu lại danh sách vào Local Storage
            localStorage.setItem('users', JSON.stringify(users));

            alert('Đăng ký thành công! Chuyển tới trang đăng nhập');
            window.location.href = 'login.php';
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error('Lỗi:', err);
        alert('Có lỗi xảy ra!');
    });
});

    </script>
</body>
</html>
