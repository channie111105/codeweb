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
        echo "<script>alert('Only JPG, JPEG, PNG, GIF files are supported!');</script>";
        exit();
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = $target_file;

        $sql = "INSERT INTO products (name, description, price, quantity, image, category, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("ssdiss", $name, $description, $price, $quantity, $image_path, $category);

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully!'); window.location.href='manageproducts.php';</script>";
        } else {
            echo "<script>alert('Error adding product!');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products</title>
</head>
<body>
<div class="container">
        <h2>Add Products</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Products Name:</label>
        <input type="text" name="name" required>

        <label for="description">Describe:</label>
        <textarea name="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" name="price" required>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>

        <label for="category">Category:</label><br>
        <select name="category" required>
            <option value="">--Choose genre--</option>
            <option value="Literary book">Literary book</option>
            <option value="Psychological books - Life skills">Psychological books - Life skills</option>
            <option value="Economic book">Economic book</option>
            <option value="Children book">Children book</option>
            <option value="Psychological books - Gender">Psychological books - Gender</option>
            <option value="Marketing - Sales Products">Marketing - Sales Products</option>
            <option value="Human Resources & Employment">Human Resources & Employment</option>
            <option value="Business Figures & Lessons">Business Figures & Lessons</option>
            <option value="Economic Analysis & Environment">Economic Analysis & Environment</option>
            <option value="Management & Leadership">Management & Leadership</option>
            <option value="Finance & Currency">Finance & Currency</option>
            <option value="Entrepreneurship & Work Skills">Entrepreneurship & Work Skills</option>
            <option value="Literary/Historical Figures">Literary/Historical Figures</option>
            <option value="Novels">Novels</option>
            <option value="Historical Novels">Historical Novels</option>
            <option value="Folk Tales & Poetry">Folk Tales & Poetry</option>
            <option value="Short Stories - Essays">Short Stories - Essays</option>
            <option value="Childrens Stories">Childrens Stories</option>
            <option value="Reports, Chronicles">Reports, Chronicles</option>
            <option value="Books About Literary/Historical Figures">Books About Literary/Historical Figures</option>
            <option value="Poetry">Poetry</option>
            <option value="Historical Stories">Historical Stories</option>
            <option value="Short Stories">Short Stories</option>
            <option value="Family, Parenting">Family, Parenting</option>
            <option value="Psychology - Gender Studies">Psychology - Gender Studies</option>
            <option value="Home Economics">Home Economics</option>
            <option value="Natural Sciences">Natural Sciences</option>
            <option value="Social Sciences">Social Sciences</option>
            <option value="Fine Arts, Music">Fine Arts, Music</option>



        </select>
        <label for="image">Image:</label>
        <input type="file" name="image" required>

        <button type="submit">Add product</button>
    </form>
    <div class="buttons">
        <a href="manageproducts.php">⬅ Back</a>
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
        submitButton.textContent = "Adding...";
    });
</script>

</style>

</body>
</html>
