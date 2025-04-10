<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "se07102_sdlc";    
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'customer')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $email);
    
    if ($stmt->execute()) {
        // Return JSON for JavaScript to handle
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed: " . $stmt->error]);
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
    <title>Register Account</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form id="registerForm" method="POST">
       <h2>Register</h2>
       Username: <input type="text" name="username" id="username" required><br>
       Password: <input type="password" name="password" id="password" required> <br>
       Email: <input type="email" name="email" id="email" required> <br>
       <input type="submit" value="Register">
       <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>

    <script>
        // Auto-fill username if previously registered
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
                    // Get the current list of users from Local Storage
                    let users = JSON.parse(localStorage.getItem('users')) || [];

                    // Add the new user to the list
                    users.push({ username, password, email });

                    // Save the updated list to Local Storage
                    localStorage.setItem('users', JSON.stringify(users));

                    alert('Registration successful! Redirecting to login page');
                    window.location.href = 'login.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('An error occurred!');
            });
        });
    </script>
</body>
</html>