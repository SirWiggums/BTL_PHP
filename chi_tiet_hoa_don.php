<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

include 'connect.php';

if (isset($_GET['id'])) {
    $ma_hoa_don = $_GET['id'];

    // Query to get invoice details
    $sql = "SELECT cthd.*, sp.ten_san_pham FROM chi_tiet_hoa_don cthd
            JOIN sanpham sp ON cthd.ma_san_pham = sp.ma_san_pham
            WHERE cthd.ma_hoa_don = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $ma_hoa_don);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Mã hóa đơn không hợp lệ.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết hóa đơn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/HderFter.css">
    <link rel="stylesheet" href="CSS/SP.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">PC Gaming</a>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="gioithieu.php">Giới thiệu</a></li>
                <li><a href="san_pham.php">Sản phẩm</a></li>
                <li><a href="ql_nguoi_dung.php">Quản lý người dùng</a></li>
                <li><a href="them_san_pham.html">Thêm sản phẩm</a></li>
                <li><a href="thong_ke.php">Thống kê</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </header>

    <h1>Chi tiết hóa đơn</h1>
    <table border="1">
        <tr>
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá sản phẩm (VND)</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $formatted_price = number_format($row['gia_san_pham'], 0, ',', '.');
                echo "<tr>
                        <td>{$row['ma_san_pham']}</td>
                        <td>{$row['ten_san_pham']}</td>
                        <td>{$row['so_luong']}</td>
                        <td>{$formatted_price}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Không có dữ liệu</td></tr>";
        }
        $conn->close();
        ?>
    </table>
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