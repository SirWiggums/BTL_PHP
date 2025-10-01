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
    <link rel="stylesheet" href="CSS/HderFter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
<header>
        <img src="Anh/Anh2/pcgaming.png" alt="" width="100px" height="130px">
        <a href="index.php" class="logo">PC Gaming
            <span>|</span> 
            <br>
            <p> PCGAMING.COM</p>
        </a>

        <!-- <div class="down">
            <div class="seach">
                <form class="search-form" action="tim_kiem.php" method="GET">
                    <input type="text" name="search" placeholder="Tìm kiếm" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div> -->
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                
            </ul>
        </nav>
    </header>
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
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    
    <footer class="footer" id="footer">
        <a href="index.php" class="logofooter">PC Gaming 
            <span>|</span> 
            <br> 
            <p> PCGAMING.COM</p> 
        </a>
        <div class="contact">
            <h2>Contact</h2>
            <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook fa-flip" style="color: #0008ff;"></i></a>
            <br>
            <a href="https://www.google.com/"><i class="fa-brands fa-twitter fa-bounce" style="color: #005eff;"></i></a>
            <br>
            <a href="https://www.google.com/"><i class="fa-brands fa-instagram" style="color: #e1bbec;"></i></a>
        </div>
        <div class="lienhe">
            <h2>Liên Hệ</h2>
            <p><span>Phone:</span> 34597348573498 </p>
            <p><span >Mail:</span> hellotoila@gmail.com</p>
        </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>