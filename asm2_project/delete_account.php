<?php
// Xử lý chức năng xóa tài khoản
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "btec_sdlc_asm2";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_GET['id'];

    // Xóa tài khoản từ cơ sở dữ liệu
    $sql = "DELETE FROM accounts WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute() === TRUE) {
        // Chuyển hướng người dùng về trang account.php
        header("Location: account.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
