<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'connect.php';

// Kiểm tra vai trò của người dùng
$username = $_SESSION['username'];
$sql = "SELECT role FROM account WHERE username='$username'";
$result = $conn->query($sql);
$user_role = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_role = $row['role'];
}

// Chỉ cho phép admin xóa sản phẩm
if ($user_role != 'admin') {
    echo "Bạn không có quyền xóa sản phẩm.";
    $conn->close();
    exit();
}

// Lấy mã sản phẩm từ URL
$ma_san_pham = $_GET['ma_san_pham'];

// Xóa các bản ghi liên quan trong bảng chi_tiet_hoa_don
$sql = "DELETE FROM chi_tiet_hoa_don WHERE ma_san_pham='$ma_san_pham'";
if ($conn->query($sql) === TRUE) {
    // Xóa sản phẩm từ cơ sở dữ liệu
    $sql = "DELETE FROM sanpham WHERE ma_san_pham='$ma_san_pham'";
    if ($conn->query($sql) === TRUE) {
        echo "Xóa sản phẩm thành công.";
    } else {
        echo "Lỗi: " . $conn->error;
    }
} else {
    echo "Lỗi: " . $conn->error;
}

$conn->close();

// Chuyển hướng về trang danh sách sản phẩm
header("Location: san_pham.php");
exit();
?>