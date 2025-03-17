<?php
include 'connect.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    // Xóa các bản ghi liên quan trong bảng gio_hang
    $sql_delete_gio_hang = "DELETE FROM gio_hang WHERE username='$username'";
    if ($conn->query($sql_delete_gio_hang) === TRUE) {
        // Sau khi xóa các bản ghi liên quan, xóa người dùng
        $sql_delete_user = "DELETE FROM account WHERE username='$username'";
        if ($conn->query($sql_delete_user) === TRUE) {
            header("Location: ql_nguoi_dung.php?success=Xóa người dùng thành công");
        } else {
            header("Location: ql_nguoi_dung.php?error=Lỗi khi xóa người dùng: " . $conn->error);
        }
    } else {
        header("Location: ql_nguoi_dung.php?error=Lỗi khi xóa hóa đơn: " . $conn->error);
    }
} else {
    header("Location: ql_nguoi_dung.php?error=Không có tên người dùng được cung cấp");
}

$conn->close();
?>