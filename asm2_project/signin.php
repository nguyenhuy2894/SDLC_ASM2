<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
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

    .signup-link {
        text-align: center;
        margin-top: 10px;
        font-size: 14px; /* Cỡ chữ phù hợp */
    }

    </style>
</head>
<body>
<div class="container">
        <h2>Sign In</h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Sign In">
        </form>
        <div class="signup-link">
            <p>Do not have an account? <a href="signup.php">Sign Up</a></p>
        </div>
        <?php
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Kết nối đến cơ sở dữ liệu
            $servername = "localhost";
            $username_db = "root";
            $password_db = "";
            $dbname = "btec_sdlc_asm2";

            $conn = new mysqli($servername, $username_db, $password_db, $dbname);

            // Kiểm tra kết nối
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Kiểm tra xem dữ liệu đã được gửi từ biểu mẫu hay không
            if(isset($_POST['username']) && isset($_POST['password'])) {
                // Lấy dữ liệu từ biểu mẫu đăng nhập
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Truy vấn để lấy thông tin người dùng, bao gồm user_id
                $sql = "SELECT id, username, password FROM accounts WHERE username=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    // Xác minh mật khẩu
                    if (password_verify($password, $row['password'])) {
                        // Lưu user_id vào session
                        $_SESSION['user_id'] = $row['id'];
                        // Chuyển hướng người dùng đến trang chính
                        header("Location: account.php");
                        exit;
                    } else {
                        echo "Invalid username or password.";
                    }
                } else {
                    echo "Invalid username or password.";
                }

                $stmt->close();
            } else {
                echo "Please fill out the form.";
            }

            $conn->close();
        }
        ?>


    </div>
</body>
</html>
