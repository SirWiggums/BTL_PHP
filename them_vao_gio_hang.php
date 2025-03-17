<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $ma_san_pham = $_POST['ma_san_pham'];

    include 'connect.php';

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $sql = "SELECT * FROM gio_hang WHERE username='$username' AND ma_san_pham='$ma_san_pham'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Nếu sản phẩm đã có trong giỏ hàng, tăng số lượng
        $sql = "UPDATE gio_hang SET so_luong = so_luong + 1 WHERE username='$username' AND ma_san_pham='$ma_san_pham'";
    } else {
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
        $sql = "INSERT INTO gio_hang (username, ma_san_pham, so_luong) VALUES ('$username', '$ma_san_pham', 1)";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Thêm vào giỏ hàng thành công";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

    // Chuyển hướng về trang giỏ hàng
    header("Location: gio_hang.php");
    exit();
}
?>