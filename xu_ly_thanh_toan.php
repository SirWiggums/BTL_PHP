<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'connect.php';

$username = $_SESSION['username'];
$order_details = json_decode($_POST['order_details'], true);
$total_price = $_POST['total_price'];
$ten = $_POST['ten'];
$so_dien_thoai = $_POST['so_dien_thoai'];
$dia_chi = $_POST['dia_chi'];

// Bắt đầu giao dịch
$conn->begin_transaction();

try {
    // Thêm hóa đơn vào bảng hoa_don
    $sql_hoa_don = "INSERT INTO hoa_don (username, ten, so_dien_thoai, dia_chi, tong_tien, ngay_tao) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql_hoa_don);
    $stmt->bind_param("ssssd", $username, $ten, $so_dien_thoai, $dia_chi, $total_price);
    $stmt->execute();
    $ma_hoa_don = $stmt->insert_id;

    // Thêm chi tiết hóa đơn vào bảng chi_tiet_hoa_don
    $sql_chi_tiet = "INSERT INTO chi_tiet_hoa_don (ma_hoa_don, ma_san_pham, so_luong, gia_san_pham) VALUES (?, ?, ?, ?)";
    $stmt_chi_tiet = $conn->prepare($sql_chi_tiet);

    foreach ($order_details as $item) {
        $ma_san_pham = $item['ma_san_pham'];
        $so_luong = $item['so_luong'];
        $gia_san_pham = $item['gia_san_pham'];

        $stmt_chi_tiet->bind_param("isid", $ma_hoa_don, $ma_san_pham, $so_luong, $gia_san_pham);
        $stmt_chi_tiet->execute();

        // Cập nhật số lượng sản phẩm trong kho
        $sql_update = "UPDATE sanpham SET so_luong = so_luong - ? WHERE ma_san_pham = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("is", $so_luong, $ma_san_pham);
        $stmt_update->execute();
    }

    // Xóa giỏ hàng sau khi thanh toán thành công
    $sql_delete = "DELETE FROM gio_hang WHERE username = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("s", $username);
    $stmt_delete->execute();

    // Commit giao dịch
    $conn->commit();

    $_SESSION['success_message'] = "Thanh toán thành công!";
    header("Location: gio_hang.php");
    exit();
} catch (Exception $e) {
    // Rollback giao dịch nếu có lỗi
    $conn->rollback();

    $_SESSION['error_message'] = "Thanh toán thất bại: " . $e->getMessage();
    header("Location: gio_hang.php");
    exit();
}

$conn->close();
?>