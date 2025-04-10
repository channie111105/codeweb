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

// Handle add & update user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_user'])) {
    $id = $_POST['user_id'] ?? '';
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    if ($id) {
        // Update user (Password not updated)
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$id";
    } else {
        // Add new user (Hash password)
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'customer')";
    }

    if ($connect->query($sql)) {
        echo "<script>alert('Saved successfully!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Error: " . $connect->error . "');</script>";
    }
}

// Handle delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($connect->query("DELETE FROM users WHERE id=$id")) {
        echo "<script>alert('Deleted successfully!'); window.location='users.php';</script>";
    } else {
        echo "<script>alert('Error deleting: " . $connect->error . "');</script>";
    }
}

// Retrieve user list
$result = $connect->query("SELECT id, username, email FROM users WHERE role='customer'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
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
    <h2>Manage Customers</h2>

    <!-- Add / Edit User Form -->
    <form method="POST">
        <input type="hidden" name="user_id" id="user_id">
        <input type="text" name="username" id="username" placeholder="Username" required>
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password">
        <button type="submit" name="save_user" class="btn">Save</button>
    </form>

    <!-- User List Table -->
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
                <button class="btn edit-btn" onclick="editUser('<?= $row['id'] ?>', '<?= $row['username'] ?>', '<?= $row['email'] ?>')">✏ Edit</button>
                <a href="?delete=<?= $row['id'] ?>" class="btn delete-btn" onclick="return confirm('Confirm delete?');">🗑 Delete</a>
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
        <a href="manageproducts.php">⬅ Back</a>
</div>

</body>
</html>

<?php $connect->close(); ?>
