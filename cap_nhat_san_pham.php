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

// Chỉ cho phép admin cập nhật sản phẩm
if ($user_role != 'admin') {
    echo "Bạn không có quyền cập nhật sản phẩm.";
    $conn->close();
    exit();
}

// Lấy dữ liệu từ form
$ma_san_pham = $_POST['ma_san_pham'];
$ten_san_pham = $_POST['ten_san_pham'];
$hang_sp = $_POST['hang_sp'];
$gia_san_pham = $_POST['gia_san_pham'];
$gia_cu = $_POST['gia_cu'];
$mo_ta_ngan = $_POST['mo_ta_ngan'];
$mo_ta_chi_tiet = $_POST['mo_ta_chi_tiet'];
$so_luong = $_POST['so_luong'];
$image = $_FILES['image']['name'];
$target = "images/" . basename($image);

// Kiểm tra và tạo thư mục images nếu không tồn tại
if (!is_dir('images')) {
    mkdir('images', 0777, true);
}

// Cập nhật sản phẩm trong cơ sở dữ liệu
if (!empty($image)) {
    // Nếu có hình ảnh mới, cập nhật cả hình ảnh
    $sql = "UPDATE sanpham SET ten_san_pham='$ten_san_pham', hang_sp='$hang_sp', gia_san_pham='$gia_san_pham', gia_cu='$gia_cu', mo_ta_ngan='$mo_ta_ngan', mo_ta_chi_tiet='$mo_ta_chi_tiet', so_luong='$so_luong', image='$image' WHERE ma_san_pham='$ma_san_pham'";
    if ($conn->query($sql) === TRUE) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "Cập nhật sản phẩm thành công";
        } else {
            echo "Lỗi khi tải lên hình ảnh";
        }
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Nếu không có hình ảnh mới, chỉ cập nhật thông tin sản phẩm
    $sql = "UPDATE sanpham SET ten_san_pham='$ten_san_pham', hang_sp='$hang_sp', gia_san_pham='$gia_san_pham', gia_cu='$gia_cu', mo_ta_ngan='$mo_ta_ngan', mo_ta_chi_tiet='$mo_ta_chi_tiet', so_luong='$so_luong' WHERE ma_san_pham='$ma_san_pham'";
    if ($conn->query($sql) === TRUE) {
        echo "Cập nhật sản phẩm thành công";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

// Chuyển hướng về trang danh sách sản phẩm
header("Location: san_pham.php");
exit();
?>