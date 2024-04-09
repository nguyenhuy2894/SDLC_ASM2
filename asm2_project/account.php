<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffc0cb; /* Màu hồng nhạt */
        }

        .container {
            max-width: 90%;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        .buttons {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    }

    .add-button {
        background-color: #4CAF50; /* Màu xanh lá cây */
        color: white;
        border: none;
        cursor: pointer;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
    }

    .add-button:hover {
        background-color: #45a049;
    }

    .sign-out-button {
        text-decoration: none;
        color: #333;
    }

    .sign-out-button:hover {
        color: #666;
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Account Management</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password (Hashed)</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "btec_sdlc_asm2";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, username, password, email FROM accounts";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["username"] . "</td>";
                        echo "<td>" . $row["password"] . "</td>"; // Hiển thị giá trị đã được băm
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td><a href='edit_account.php?id=" . $row["id"] . "'>Edit</a> | <a href='delete_account.php?id=" . $row["id"] . "'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='error-message'>No accounts found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
        <div class="buttons">
            <a class="add-button" href="add_account.php">Add</a>
            <a href="signin.php" class="sign-out-button">Sign Out</a>
        </div>
    </div>
</body>
</html>

