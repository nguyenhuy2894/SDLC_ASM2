<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffc0cb; /* Màu hồng nhạt */
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.7); /* Màu nền trong suốt */
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .signin-link {
            text-align: center;
            margin-top: 10px;
            font-size: 14px; /* Cỡ chữ phù hợp */
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Add Account</h2>
        <form action="add_account.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="submit" value="Add Account">
        </form>
        <div class="signin-link">
            <p>Already have an account? <a href="signin.php">Sign In</a></p>
        </div>
    </div>
    <?php
    // Bắt đầu phiên
    session_start();

    // Kiểm tra xem người dùng đã gửi biểu mẫu chưa
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Lấy dữ liệu từ biểu mẫu
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Kiểm tra mật khẩu và mật khẩu xác nhận
        if ($password != $confirm_password) {
            echo "Password and confirm password do not match.";
            exit;
        }

        // Hash mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Thực hiện truy vấn để thêm tài khoản vào cơ sở dữ liệu
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $dbname = "btec_sdlc_asm2";

        $conn = new mysqli($servername, $username_db, $password_db, $dbname);

        // Kiểm tra kết nối
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Truy vấn để thêm tài khoản mới
        $sql = "INSERT INTO accounts (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute() === TRUE) {
            // Chuyển hướng người dùng về trang account.php
            header("Location: account.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>

</body>
</html>
