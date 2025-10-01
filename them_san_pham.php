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

// Chỉ cho phép admin thêm sản phẩm
if ($user_role != 'admin') {
    echo "Bạn không có quyền thêm sản phẩm.";
    $conn->close();
    exit();
}

// Lấy dữ liệu từ form
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

// Thêm sản phẩm vào cơ sở dữ liệu
$sql = "INSERT INTO sanpham (ten_san_pham, hang_sp, gia_san_pham, gia_cu, mo_ta_ngan, mo_ta_chi_tiet, so_luong, image) VALUES ('$ten_san_pham', '$hang_sp', '$gia_san_pham', '$gia_cu', '$mo_ta_ngan', '$mo_ta_chi_tiet', '$so_luong', '$image')";
if ($conn->query($sql) === TRUE) {
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        echo "Thêm sản phẩm thành công";
    } else {
        echo "Lỗi khi tải lên hình ảnh";
    }
} else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
}

$conn->close();

// Chuyển hướng về trang danh sách sản phẩm
header("Location: san_pham.php");
exit();
?>