<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

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

// Xử lý khi nhận dữ liệu từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kiểm tra mật khẩu mới có được nhập không
    if (empty($password)) {
        // Nếu không có mật khẩu mới, chỉ cập nhật tên người dùng và email
        $sql = "UPDATE accounts SET username=?, email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $email, $_SESSION['user_id']);
    } else {
        // Nếu có mật khẩu mới, cập nhật cả tên người dùng, email và mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE accounts SET username=?, email=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $hashed_password, $_SESSION['user_id']);
    }

    // Thực hiện truy vấn cập nhật
    if ($stmt->execute() === TRUE) {
        header("Location: account.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

// Lấy thông tin tài khoản hiện tại từ cơ sở dữ liệu để điền vào form
$sql_select = "SELECT username, email FROM accounts WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $_SESSION['user_id']);
$stmt_select->execute();
$stmt_select->store_result();

if ($stmt_select->num_rows > 0) {
    $stmt_select->bind_result($current_username, $current_email);
    $stmt_select->fetch();
} else {
    echo "Không tìm thấy thông tin người dùng!";
}

$stmt_select->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
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

        label { 
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Account</h2>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $current_username; ?>" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $current_email; ?>" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Save Changes">
        </form>
    </div>
</body>
</html>
