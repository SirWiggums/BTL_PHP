<?php
include 'connect.php';

// Lấy dữ liệu từ form
$username = $_POST['username'];
$password = $_POST['password'];
$repass = $_POST['repass'];
$role = 'user'; // Bạn có thể thay đổi giá trị này tùy theo yêu cầu

// Kiểm tra mật khẩu
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{5,}$/', $password)) {
    header("Location: register.html?error=Mật khẩu phải có ít nhất 5 ký tự, bao gồm chữ cái hoa, chữ cái thường, số và một ký tự đặc biệt.");
    exit();
}

// Kiểm tra mật khẩu và mật khẩu nhập lại
if ($password !== $repass) {
    header("Location: register.html?error=Mật khẩu và mật khẩu nhập lại không khớp.");
    exit();
}

// Kiểm tra xem tên đăng nhập đã tồn tại hay chưa
$sql_check = "SELECT * FROM account WHERE username='$username'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    header("Location: register.html?error=Tên đăng nhập đã tồn tại. Vui lòng chọn tên đăng nhập khác.");
    exit();
}

// Thêm người dùng vào cơ sở dữ liệu
$sql = "INSERT INTO account (username, password, create_time, update_time, role) VALUES ('$username', '$password', NOW(), NOW(), '$role')";

if ($conn->query($sql) === TRUE) {
    header("Location: register.html?success=Đăng ký thành công");
} else {
    header("Location: register.html?error=Lỗi: " . $sql . "<br>" . $conn->error);
}

$conn->close();
?>