<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

include 'connect.php';

// Query to get revenue statistics
$sql_revenue = "SELECT DATE(ngay_tao) as date, SUM(tong_tien) as revenue FROM hoa_don GROUP BY DATE(ngay_tao)";
$result_revenue = $conn->query($sql_revenue);

// Query to get all invoices
$sql_invoices = "SELECT * FROM hoa_don";
$result_invoices = $conn->query($sql_invoices);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê doanh thu và hóa đơn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/HderFter.css">
    <link rel="stylesheet" href="CSS/SP.css">
</head>
<body>
    <header>
        <img src="Anh/pcgaming.png" alt="" width="100px" height="130px">
        <a href="index.php" class="logo">PC Gaming
            <span>|</span> 
            <br>
            <p> PCGAMING.COM</p>
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li><a href="gioithieu.php">Giới thiệu</a></li>
                <li><a href="san_pham.php">Sản phẩm</a></li>
                <li><a href="ql_nguoi_dung.php">Quản lý người dùng</a></li>
                <li><a href="them_san_pham.html">Thêm sản phẩm</a></li>
                <li><a href="thong_ke.php" style="color: black;">Thống kê</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </header>

    <h1>Thống kê doanh thu</h1>
    <table border="1">
        <tr>
            <th>Ngày</th>
            <th>Doanh thu (VND)</th>
        </tr>
        <?php
        if ($result_revenue->num_rows > 0) {
            while ($row = $result_revenue->fetch_assoc()) {
                $formatted_revenue = number_format($row['revenue'], 0, ',', '.');
                echo "<tr>
                        <td>{$row['date']}</td>
                        <td>{$formatted_revenue}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='2'>Không có dữ liệu</td></tr>";
        }
        ?>
    </table>

    <h1>Danh sách hóa đơn</h1>
    <table border="1">
        <tr>
            <th>Mã hóa đơn</th>
            <th>nick</th>
            <th>Tên</th>
            <th>SĐT</th>
            <th>Ngày tạo</th>
            <th>Tổng tiền (VND)</th>
            <th>Chi tiết</th>
        </tr>
        <?php
        if ($result_invoices->num_rows > 0) {
            while ($row = $result_invoices->fetch_assoc()) {
                $formatted_total = number_format($row['tong_tien'], 0, ',', '.');
                echo "<tr>
                        <td>{$row['ma_hoa_don']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['ten']}</td>
                        <td>{$row['so_dien_thoai']}</td>
                        <td>{$row['ngay_tao']}</td>
                        <td>{$formatted_total}</td>
                        <td><a href='chi_tiet_hoa_don.php?id={$row['ma_hoa_don']}'>Xem chi tiết</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Không có dữ liệu</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>