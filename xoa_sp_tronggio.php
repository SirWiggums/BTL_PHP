<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $id = $_POST['id'];

    include 'connect.php';

    // Xóa sản phẩm khỏi giỏ hàng
    $sql = "DELETE FROM gio_hang WHERE id='$id' AND username='$username'";
    if ($conn->query($sql) === TRUE) {
        echo "Xóa sản phẩm khỏi giỏ hàng thành công";
    } else {
        echo "Lỗi khi xóa sản phẩm khỏi giỏ hàng: " . $conn->error;
    }

    $conn->close();

    // Chuyển hướng về trang giỏ hàng
    header("Location: gio_hang.php");
    exit();
}
?>