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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category = $_POST["category"];

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = $_FILES["image"]["name"];
    $file_name = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file_name);
    $file_name = time() . "_" . $file_name;
    $target_file = $target_dir . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Chỉ hỗ trợ file JPG, JPEG, PNG, GIF!');</script>";
        exit();
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = $target_file;

        $sql = "INSERT INTO products (name, description, price, quantity, image, category, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssdiss", $name, $description, $price, $quantity, $image_path, $category);

        if ($stmt->execute()) {
            echo "<script>alert('Thêm sản phẩm thành công!'); window.location.href='manageproducts.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm sản phẩm!');</script>";
        }
    } else {
        echo "<script>alert('Lỗi khi tải ảnh lên!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
</head>
<body>
<div class="container">
        <h2>Thêm Sản Phẩm</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" name="name" required>

        <label for="description">Mô tả:</label>
        <textarea name="description" required></textarea>

        <label for="price">Giá:</label>
        <input type="number" name="price" required>

        <label for="quantity">Số lượng:</label>
        <input type="number" name="quantity" required>

        <label for="category">Thể loại:</label><br>
        <select name="category" required>
            <option value="">--Chọn thể loại--</option>
            <option value="Sách Văn Học">Sách Văn Học</option>
            <option value="Sách Tâm Lý - Kỹ Năng Sống">Sách Tâm Lý - Kỹ Năng Sống</option>
            <option value="Sách Thiếu Nhi">Sách Thiếu Nhi</option>
            <option value="Sách Chuyên Ngành">Sách Chuyên Ngành</option>
            <option value="Sách Giáo Khoa - Giáo Trình">Sách Giáo Khoa - Giáo Trình</option>
            <option value="Sách Tin Học - Ngoại Ngữ">Sách Tin Học - Ngoại Ngữ</option>
        </select>

        <label for="image">Ảnh:</label>
        <input type="file" name="image" required>

        <button type="submit">Thêm sản phẩm</button>
    </form>
    <div class="buttons">
        <a href="manageproducts.php">⬅ Quay lại</a>
    </div>
</div>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #111;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}
.container {
    background-color: #222;
    padding: 30px 40px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(255, 105, 180, 0.3);
    width: 400px;
    text-align: center;
}
h2 {
    color: #ff69b4;
    margin-bottom: 20px;
}
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
label {
    text-align: left;
    color: #ff69b4;
    font-weight: bold;
}
input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ff69b4;
    background-color: #333;
    color: white;
    font-size: 14px;
    transition: 0.3s;
}
input:focus, textarea:focus, select:focus {
    border-color: #ff1493;
    outline: none;
}
textarea {
    resize: vertical;
    min-height: 60px;
}
#image-preview {
    margin-top: 10px;
    max-width: 100%;
    border-radius: 8px;
    display: none;
}
button[type="submit"] {
    padding: 12px;
    background-color: #ff69b4;
    border: none;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    font-size: 16px;
    position: relative;
    transition: background-color 0.3s, transform 0.2s;
}
button[type="submit"]:hover {
    background-color: #ff1493;
    transform: scale(1.05);
}
button.loading::after {
    content: "";
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-left-color: white;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    animation: spin 0.8s linear infinite;
}
@keyframes spin {
    to { transform: translateY(-50%) rotate(360deg); }
}
.buttons {
    margin-top: 20px;
}
.buttons a {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #ff69b4;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s, transform 0.2s;
}
.buttons a:hover {
    background-color: #ff1493;
    transform: scale(1.05);
}
<script>
    const imageInput = document.querySelector('input[name="image"]');
    const preview = document.createElement('img');
    preview.id = "image-preview";
    imageInput.parentNode.insertBefore(preview, imageInput.nextSibling);

    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            preview.style.display = 'block';
            preview.src = URL.createObjectURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    form.addEventListener('submit', function() {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
        submitButton.textContent = "Đang thêm...";
    });
</script>

</style>

</body>
</html>
