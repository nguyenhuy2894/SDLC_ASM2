<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        <h2>Sign Up</h2>
        <form action="signup.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <input type="submit" value="Sign Up">
        </form>
        <div class="signin-link">
            <p>Already have an account? <a href="signin.php">Sign In</a></p>
        </div>
        <!-- xu li -->
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
            if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
                // Lấy dữ liệu từ biểu mẫu đăng ký
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];

                // Kiểm tra xem mật khẩu và mật khẩu xác nhận có khớp nhau không
                if ($password !== $confirm_password) {
                    echo "Passwords do not match.";
                    exit;
                }

                // Kiểm tra xem tên người dùng đã tồn tại trong cơ sở dữ liệu chưa
                $check_username_sql = "SELECT id FROM accounts WHERE username=?";
                $check_username_stmt = $conn->prepare($check_username_sql);
                $check_username_stmt->bind_param("s", $username);
                $check_username_stmt->execute();
                $check_username_result = $check_username_stmt->get_result();

                if ($check_username_result->num_rows > 0) {
                    echo "Username already exists.";
                    exit;
                }

                // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
                $check_email_sql = "SELECT id FROM accounts WHERE email=?";
                $check_email_stmt = $conn->prepare($check_email_sql);
                $check_email_stmt->bind_param("s", $email);
                $check_email_stmt->execute();
                $check_email_result = $check_email_stmt->get_result();

                if ($check_email_result->num_rows > 0) {
                    echo "Email already exists.";
                    exit;
                }

                // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Thêm người dùng mới vào cơ sở dữ liệu
                $insert_user_sql = "INSERT INTO accounts (username, email, password) VALUES (?, ?, ?)";
                $insert_user_stmt = $conn->prepare($insert_user_sql);
                $insert_user_stmt->bind_param("sss", $username, $email, $hashed_password);
                if ($insert_user_stmt->execute()) {
                    echo "User registered successfully.";
                } else {
                    echo "Error registering user.";
                }

                $insert_user_stmt->close();
                $conn->close();
            } else {
                echo "Please fill out the form.";
            }
        }
        ?>

    </div>
</body>
</html>
