<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "You do not have permission to access this page.";
    exit();
}

$connect = new mysqli('localhost', 'root', '', 'se07102_sdlc');
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

$id = $_GET['id'];
$product = $connect->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category = $_POST["category"];

    // Handle image upload
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

    // Update database
    $sql = "UPDATE products SET name=?, description=?, price=?, quantity=?, image=?, category=? WHERE id=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ssdissi", $name, $description, $price, $quantity, $image, $category, $id);
    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href='manageproducts.php';</script>";
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
    <h2>Update Product</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?= $product['name'] ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= $product['description'] ?></textarea>

        <label>Price:</label>
        <input type="number" name="price" value="<?= $product['price'] ?>" required>

        <label>Quantity:</label>
        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>

        <label>Current Category: <strong><?= $product['category'] ?></strong></label>
        <label>Change Category:</label>
        <select name="category" required>
            <option value="">--Select new category--</option>
            <option value="Literary Book" <?= $product['category'] == 'Literary Book' ? 'selected' : '' ?>>Literary Book</option>
            <option value="Psychology - Life Skills Books" <?= $product['category'] == 'Psychology - Life Skills Books' ? 'selected' : '' ?>>Psychology - Life Skills Books</option>
            <option value="Children's Books" <?= $product['category'] == 'Children Books' ? 'selected' : '' ?>>Childrens Books</option>
            <option value="Specialized Books" <?= $product['category'] == 'Specialized Books' ? 'selected' : '' ?>>Specialized Books</option>
            <option value="Textbooks - Curriculum" <?= $product['category'] == 'Textbooks - Curriculum' ? 'selected' : '' ?>>Textbooks - Curriculum</option>
            <option value="IT - Foreign Language Books" <?= $product['category'] == 'IT - Foreign Language Books' ? 'selected' : '' ?>>IT - Foreign Language Books</option>
            <option value="Marketing - Sales Products" <?= $product['category'] == 'Marketing - Sales Products' ? 'selected' : '' ?>>Marketing - Sales Products</option>
            <option value="Human Resources & Employment" <?= $product['category'] == 'Human Resources & Employment' ? 'selected' : '' ?>>Human Resources & Employment</option>
            <option value="Business Figures & Lessons" <?= $product['category'] == 'Business Figures & Lessons' ? 'selected' : '' ?>>Business Figures & Lessons</option>
            <option value="Economic Analysis & Environment" <?= $product['category'] == 'Economic Analysis & Environment' ? 'selected' : '' ?>>Economic Analysis & Environment</option>
            <option value="Management & Leadership" <?= $product['category'] == 'Management & Leadership' ? 'selected' : '' ?>>Management & Leadership</option>
            <option value="Finance & Currency" <?= $product['category'] == 'Finance & Currency' ? 'selected' : '' ?>>Finance & Currency</option>
            <option value="Entrepreneurship & Work Skills" <?= $product['category'] == 'Entrepreneurship & Work Skills' ? 'selected' : '' ?>>Entrepreneurship & Work Skills</option>
            <option value="Literary/Historical Figures" <?= $product['category'] == 'Literary/Historical Figures' ? 'selected' : '' ?>>Literary/Historical Figures</option>
            <option value="Novels" <?= $product['category'] == 'Novels' ? 'selected' : '' ?>>Novels</option>
            <option value="Historical Novels" <?= $product['category'] == 'Historical Novels' ? 'selected' : '' ?>>Historical Novels</option>
            <option value="Folk Tales & Poetry" <?= $product['category'] == 'Folk Tales & Poetry' ? 'selected' : '' ?>>Folk Tales & Poetry</option>
            <option value="Short Stories - Essays" <?= $product['category'] == 'Short Stories - Essays' ? 'selected' : '' ?>>Short Stories - Essays</option>
            <option value="Childrens Stories" <?= $product['category'] == 'Childrens Stories' ? 'selected' : '' ?>>Childrens Stories</option>
            <option value="Reports, Chronicles" <?= $product['category'] == 'Reports, Chronicles' ? 'selected' : '' ?>>Reports, Chronicles</option>
            <option value="Books About Literary/Historical Figures" <?= $product['category'] == 'Books About Literary/Historical Figures' ? 'selected' : '' ?>>Books About Literary/Historical Figures</option>
            <option value="Poetry" <?= $product['category'] == 'Poetry' ? 'selected' : '' ?>>Poetry</option>
            <option value="Historical Stories" <?= $product['category'] == 'Historical Stories' ? 'selected' : '' ?>>Historical Stories</option>
            <option value="Short Stories" <?= $product['category'] == 'Short Stories' ? 'selected' : '' ?>>Short Stories</option>
            <option value="Family, Parenting" <?= $product['category'] == 'Family, Parenting' ? 'selected' : '' ?>>Family, Parenting</option>
            <option value="Psychology - Gender Studies" <?= $product['category'] == 'Psychology - Gender Studies' ? 'selected' : '' ?>>Psychology - Gender Studies</option>
            <option value="Home Economics" <?= $product['category'] == 'Home Economics' ? 'selected' : '' ?>>Home Economics</option>
            <option value="Natural Sciences" <?= $product['category'] == 'Natural Sciences' ? 'selected' : '' ?>>Natural Sciences</option>
            <option value="Social Sciences" <?= $product['category'] == 'Social Sciences' ? 'selected' : '' ?>>Social Sciences</option>
            <option value="Fine Arts, Music" <?= $product['category'] == 'Fine Arts, Music' ? 'selected' : '' ?>>Fine Arts, Music</option>

        </select>
        <label>Product Image (select from device):</label>
        <input type="file" name="image" accept="image/*">

        <input type="submit" value="Update">
    </form>
</div>