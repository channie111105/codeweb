<?php  
session_start();
$connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');

// Kiểm tra kết nối
if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]); 

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // So sánh mật khẩu (nếu bạn có hash thì thay bằng password_verify)
        if ($password === $user["password"]) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Gửi phản hồi JSON để JS bắt
            echo json_encode([
                "status" => "success",
                "role" => $user['role']
            ]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password!"]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Username not found!"]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form id="loginForm" method="post">
        <h2>Login</h2>
        Username: <input type="text" name="username" id="username" required> <br>
        Password: <input type="password" name="password" id="password" required> <br>
        <input type="submit" value="Login">
        <p>Don't have an account yet? <a href="register.php">Register here</a></p>
    </form>

    <script>
        // Khi tải trang, điền username nếu đã lưu
        document.addEventListener("DOMContentLoaded", function () {
            const savedUsername = localStorage.getItem('savedUsername');
            if (savedUsername) {
                document.getElementById('username').value = savedUsername;
            }
        });

        // Bắt sự kiện submit để dùng fetch gửi AJAX
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    username: username,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Lưu username vào localStorage
                    localStorage.setItem('savedUsername', username);

                    if (data.role === 'admin') {
                        alert('Welcome Admin!');
                        window.location.href = 'manageproducts.php';
                    } else {
                        alert('Login successful!');
                        window.location.href = 'index.php';
                    }
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
