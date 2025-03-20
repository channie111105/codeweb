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

$id = $_GET['id'];
$product = $connect->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category = $_POST["category"];

    // Xử lý upload ảnh
    if ($_FILES["image"]["name"]) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = $_FILES["image"]["name"];
        $file_name = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $file_name);
        $file_name = time() . "_" . $file_name;
        $target_file = $target_dir . $file_name;

        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = $target_file;
    } else {
        $image = $product['image'];
    }

    // Cập nhật database
    $sql = "UPDATE products SET name=?, description=?, price=?, quantity=?, image=?, category=? WHERE id=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ssdissi", $name, $description, $price, $quantity, $image, $category, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật sản phẩm thành công!'); window.location.href='manageproducts.php';</script>";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #111;
        color: white;
        text-align: center;
    }
    .container {
        width: 50%;
        margin: auto;
        background: #222;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(255, 105, 180, 0.5);
    }
    h2 {
        color: #ff69b4;
        margin-bottom: 20px;
    }
    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    label {
        font-weight: bold;
        margin-top: 10px;
        color: #ff69b4;
    }
    input, textarea, select {
        width: 80%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ff69b4;
        border-radius: 5px;
        background-color: #333;
        color: white;
    }
    textarea {
        height: 100px;
        resize: none;
    }
    input[type="file"] {
        border: none;
        background: none;
        color: white;
    }
    input[type="submit"] {
        width: 50%;
        padding: 10px;
        background-color: #ff69b4;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 15px;
        transition: 0.3s;
    }
    input[type="submit"]:hover {
        background-color: #ff3385;
    }
</style>

<div class="container">
    <h2>Cập nhật sản phẩm</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Tên sản phẩm:</label>
        <input type="text" name="name" value="<?= $product['name'] ?>" required>

        <label>Mô tả:</label>
        <textarea name="description" required><?= $product['description'] ?></textarea>

        <label>Giá:</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" required>

        <label>Số lượng:</label>
        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>

        <label>Thể loại hiện tại: <strong><?= $product['category'] ?></strong></label>
        <label>Thay đổi thể loại:</label>
        <select name="category" required>
            <option value="">--Chọn thể loại mới--</option>
            <option value="Sách Văn Học" <?= $product['category'] == 'Sách Văn Học' ? 'selected' : '' ?>>Sách Văn Học</option>
            <option value="Sách Tâm Lý - Kỹ Năng Sống" <?= $product['category'] == 'Sách Tâm Lý - Kỹ Năng Sống' ? 'selected' : '' ?>>Sách Tâm Lý - Kỹ Năng Sống</option>
            <option value="Sách Thiếu Nhi" <?= $product['category'] == 'Sách Thiếu Nhi' ? 'selected' : '' ?>>Sách Thiếu Nhi</option>
            <option value="Sách Chuyên Ngành" <?= $product['category'] == 'Sách Chuyên Ngành' ? 'selected' : '' ?>>Sách Chuyên Ngành</option>
            <option value="Sách Giáo Khoa - Giáo Trình" <?= $product['category'] == 'Sách Giáo Khoa - Giáo Trình' ? 'selected' : '' ?>>Sách Giáo Khoa - Giáo Trình</option>
            <option value="Sách Tin Học - Ngoại Ngữ" <?= $product['category'] == 'Sách Tin Học - Ngoại Ngữ' ? 'selected' : '' ?>>Sách Tin Học - Ngoại Ngữ</option>
        </select>

        <label>Ảnh sản phẩm (chọn từ máy):</label>
        <input type="file" name="image" accept="image/*">

        <input type="submit" value="Cập nhật">
    </form>
</div>
