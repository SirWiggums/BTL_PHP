<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

include 'connect.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Xóa các bản ghi liên quan trong bảng gio_hang trước
    $sql_delete_cart = "DELETE FROM gio_hang WHERE username='$username'";
    if ($conn->query($sql_delete_cart) !== TRUE) {
        echo "Lỗi khi xóa dữ liệu từ giỏ hàng: " . $conn->error;
        exit();
    }

    // Sau đó, lấy thông tin người dùng từ account
    $sql = "SELECT * FROM account WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Người dùng không tồn tại.";
        exit();
    }
} else {
    echo "Tên đăng nhập không hợp lệ.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    $new_role = $_POST['role'];

    // Cập nhật mật khẩu mới nếu có thay đổi
    if (!empty($new_password)) {
        $sql_update = "UPDATE account SET username='$new_username', password='$new_password', role='$new_role' WHERE username='$username'";
    } else {
        $sql_update = "UPDATE account SET username='$new_username', role='$new_role' WHERE username='$username'";
    }

    if ($conn->query($sql_update) === TRUE) {
        echo "Cập nhật thông tin người dùng thành công.";
        header("Location: ql_nguoi_dung.php");
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thông tin người dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/HderFter.css">
    <link rel="stylesheet" href="CSS/SP.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">PC Gaming</a>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="gioithieu.php">Giới thiệu</a></li>
                <li><a href="san_pham.php">Sản phẩm</a></li>
                <li><a href="ql_nguoi_dung.php">Quản lý người dùng</a></li>
                <li><a href="them_san_pham.html">Thêm sản phẩm</a></li>
                <li><a href="thong_ke.php">Thống kê</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </header>

    <h1>Sửa thông tin người dùng</h1>
    <form action="" method="POST">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        <br>
        <label for="password">Mật khẩu mới (để trống nếu không thay đổi):</label>
        <input type="password" id="password" name="password">
        <br>
        <label for="role">Vai trò:</label>
        <select id="role" name="role" required>
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>Người dùng</option>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Quản trị viên</option>
        </select>
        <br>
        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>