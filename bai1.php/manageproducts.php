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

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $connect->query("DELETE FROM products WHERE id = $id");
    echo "<script>alert('Product deleted successfully!'); window.location.href='manageproducts.php';</script>";
}

// Fetch unique categories
$categories_result = $connect->query("SELECT DISTINCT category FROM products");

// Handle product filtering by category
$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
if ($filter_category) {
    $stmt = $connect->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->bind_param("s", $filter_category);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $connect->query("SELECT * FROM products");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #111;
            color: white;
            text-align: center;
        }
        .container {
            width: 95%;
            max-width: 1100px;
            margin: auto;
            background: #222;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(255, 105, 180, 0.5);
        }
        h2 {
            color: #ff69b4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ff69b4;
            padding: 10px;
            text-align: center;
            word-wrap: break-word;
        }
        th {
            background-color: #ff69b4;
            color: black;
        }
        tr:hover {
            background-color: #333;
        }
        img {
            width: 50px;
            height: auto;
            border-radius: 5px;
        }
        .actions a {
            display: inline-block;
            padding: 8px 15px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            margin: 2px;
        }
        .edit {
            background-color: #ffcc00;
        }
        .delete {
            background-color: #ff4444;
        }
        .buttons {
            margin-top: 20px;
        }
        .buttons a {
            display: inline-block;
            margin: 10px;
            padding: 12px 18px;
            background-color: #ff69b4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        form.filter-form {
            margin-top: 20px;
        }
        select, button {
            padding: 8px 12px;
            margin: 5px;
            border-radius: 5px;
            border: none;
            outline: none;
        }
        button.filter-btn {
            background-color: #ff69b4;
            color: white;
            cursor: pointer;
        }
        button.filter-btn:hover {
            background-color: #ff1493;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Products</h2>

    <form method="get" class="filter-form">
        <label for="category">Filter by Category: </label>
        <select name="category" id="category">
            <option value="">-- Show All --</option>
            <?php while ($cat = $categories_result->fetch_assoc()): ?>
                <option value="<?= $cat['category'] ?>" <?= $cat['category'] == $filter_category ? 'selected' : '' ?>>
                    <?= $cat['category'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="filter-btn">Filter</button>
        <?php if ($filter_category): ?>
            <a href="manageproducts.php" style="color: #ff69b4; text-decoration: none; margin-left: 10px;">❌ Clear Filter</a>
        <?php endif; ?>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Category</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['description'] ?></td>
            <td><?= number_format($row['price'], 0, ',', '.') ?> VND</td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['category'] ?></td>
            <td><img src="<?= $row['image'] ?>" alt="Product Image"></td>
            <td class="actions">
                <a href="editproducts.php?id=<?= $row['id'] ?>" class="edit">✏ Edit</a>
                <a href="?delete=<?= $row['id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this product?')">❌ Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="buttons">
        <a href="addproducts.php">➕ Add Product</a>
        <a href="index.php">🏠 Back to Homepage</a>
        <a href="users.php">👥 View User List</a>
    </div>
</div>

</body>
</html>