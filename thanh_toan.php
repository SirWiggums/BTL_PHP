<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'connect.php';

$username = $_SESSION['username'];

// Lấy thông tin sản phẩm trong giỏ hàng
$sql = "SELECT sanpham.ma_san_pham, sanpham.gia_san_pham, gio_hang.so_luong 
        FROM gio_hang 
        JOIN sanpham ON gio_hang.ma_san_pham = sanpham.ma_san_pham 
        WHERE gio_hang.username='$username'";
$result = $conn->query($sql);

$total_price = 0;
$order_details = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $total_price += $row['gia_san_pham'] * $row['so_luong'];
        $order_details[] = [
            'ma_san_pham' => $row['ma_san_pham'],
            'so_luong' => $row['so_luong'],
            'gia_san_pham' => $row['gia_san_pham']
        ];
    }
} else {
    echo "Giỏ hàng của bạn trống.";
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
</head>
<body>
    <h1>Thông tin thanh toán</h1>
    <form action="xu_ly_thanh_toan.php" method="post">
        <label for="ten">Tên:</label>
        <input type="text" id="ten" name="ten" required><br>
        <label for="so_dien_thoai">Số điện thoại:</label>
        <input type="text" id="so_dien_thoai" name="so_dien_thoai" required><br>
        <label for="dia_chi">Địa chỉ:</label>
        <input type="text" id="dia_chi" name="dia_chi" required><br>
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
        <input type="hidden" name="order_details" value="<?php echo htmlspecialchars(json_encode($order_details)); ?>">
        <button type="submit">Đặt hàng</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>