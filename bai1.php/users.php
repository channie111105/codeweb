<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Bạn không có quyền truy cập trang này.";
    exit();
}

$connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');
if ($connect->connect_error) {
    die("Kết nối thất bại: " . $connect->connect_error);
}

// Xử lý thêm & cập nhật khách hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_user'])) {
    $id = $_POST['user_id'] ?? '';
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    if ($id) {
        // Cập nhật khách hàng (Không cập nhật mật khẩu)
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$id";
    } else {
        // Thêm khách hàng mới (hash mật khẩu)
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'customer')";
    }

    if ($connect->query($sql)) {
        echo "<script>alert('Lưu thành công!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $connect->error . "');</script>";
    }
}

// Xử lý xóa khách hàng
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($connect->query("DELETE FROM users WHERE id=$id")) {
        echo "<script>alert('Xóa thành công!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa: " . $connect->error . "');</script>";
    }
}


// Lấy danh sách khách hàng
$result = $connect->query("SELECT id, username, email FROM users WHERE role='customer'");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách hàng</title>
    <style>
        body { font-family: Arial, sans-serif; background: #111; color: white; text-align: center; }
        .container { width: 80%; margin: auto; background: #222; padding: 20px; border-radius: 10px; }
        h2 { color: #ff69b4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ff69b4; padding: 10px; text-align: center; }
        th { background: #ff69b4; color: black; }
        tr:hover { background: #333; }
        input, button { padding: 10px; margin: 5px; }
        .btn { background: #ff69b4; color: white; border: none; padding: 10px; cursor: pointer; }
        .edit-btn { background: orange; }
        .delete-btn { background: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>Quản lý khách hàng</h2>

    <!-- Form thêm / sửa khách hàng -->
    <form method="POST">
        <input type="hidden" name="user_id" id="user_id">
        <input type="text" name="username" id="username" placeholder="Tên đăng nhập" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Mật khẩu">
        <button type="submit" name="save_user" class="btn">Lưu</button>
    </form>

    <!-- Bảng danh sách khách hàng -->
    <table>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
                <button class="btn edit-btn" onclick="editUser('<?= $row['id'] ?>', '<?= $row['username'] ?>', '<?= $row['email'] ?>')">✏ Sửa</button>
                <a href="?delete=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Xác nhận xóa?');">🗑 Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    function editUser(id, username, email) {
        document.getElementById("user_id").value = id;
        document.getElementById("username").value = username;
        document.getElementById("email").value = email;
        document.getElementById("password").removeAttribute("required");
    }
</script>
<div class="buttons">
        <a href="manageproducts.php">⬅ Quay lại</a>
</div>

</body>
</html>

<?php $connect->close(); ?>
