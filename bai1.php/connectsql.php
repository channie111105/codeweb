<!DOCTYPE html>
<html>
<head>
    <title>Database Connection</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "se07102_sdlc";

    // Kết nối đến MySQL
    $connect = mysqli_connect($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($connect) {
        echo "Connect successfully!";
    } else {
        echo "Connect fail";
    }
    ?>
</body>
</html>
