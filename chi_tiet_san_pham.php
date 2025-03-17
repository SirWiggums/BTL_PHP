<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'connect.php';

// Lấy vai trò của người dùng
$username = $_SESSION['username'];
$sql = "SELECT role FROM account WHERE username='$username'";
$result = $conn->query($sql);
$user_role = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_role = $row['role'];
}

// Lấy số lượng sản phẩm trong giỏ hàng
$sql_cart = "SELECT SUM(so_luong) AS total_items FROM gio_hang WHERE username='$username'";
$result_cart = $conn->query($sql_cart);
$total_items = 0;
if ($result_cart->num_rows > 0) {
    $row_cart = $result_cart->fetch_assoc();
    $total_items = $row_cart['total_items'];
}

if (isset($_GET['ma_san_pham'])) {
    $ma_san_pham = $_GET['ma_san_pham'];

    // Lấy thông tin chi tiết sản phẩm
    $sql = "SELECT * FROM sanpham WHERE ma_san_pham='$ma_san_pham'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Sản phẩm không tồn tại.";
        exit();
    }
} else {
    echo "Mã sản phẩm không hợp lệ.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/HderFter.css">
    <link rel="stylesheet" href="CSS/chi_tiet_SP.css"> <!-- Liên kết tệp CSS -->
</head>
<body>
    <header>
        <a href="index.php" class="logo">PC Gaming
            <p> PCGAMING.COM</p>
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li> <a href="gioithieu.php">Giới thiệu</a></li>
                <li><a href="san_pham.php">Sản phẩm</a></li>
                <?php
                if (isset($_SESSION['username'])) {
                    if ($user_role == 'admin') {
                        echo '<li><a href="ql_nguoi_dung.php">Quản lý người dùng</a></li>';
                        echo '<li><a href="them_san_pham.html">Thêm sản phẩm</a></li>';
                        echo '<li><a href="thong_ke.php">Thống kê</a></li>';
                    }
                    echo '<li>Chào mừng, ' . $_SESSION['username'] . '!</li>';
                    echo '<li><a href="gio_hang.php">Giỏ hàng (' . $total_items . ')</a></li>'; // Hiển thị số lượng sản phẩm trong giỏ hàng

                    echo '<li><a href="logout.php">Đăng xuất</a></li>';
                } else {
                    echo '<li><a href="login.html">Đăng nhập</a></li>';
                    echo '<li><a href="register.html">Đăng ký</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <h1>Chi tiết sản phẩm</h1>
    <div class="product-detail">
        <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['ten_san_pham']; ?>">
        <div class="product-info">
            <h2><?php echo $product['ten_san_pham']; ?></h2>
            <p class="price"><?php echo number_format($product['gia_san_pham'], 0, ',', '.'); ?> VND</p>
            <?php if (!empty($product['gia_cu'])): ?>
                <p class="old-price"><?php echo number_format($product['gia_cu'], 0, ',', '.'); ?> VND</p>
            <?php endif; ?>
            <p><?php echo $product['mo_ta_ngan']; ?></p>
            <p><?php echo $product['mo_ta_chi_tiet']; ?></p>
            <p>Số lượng: <?php echo $product['so_luong']; ?></p>
            <?php if ($user_role == 'admin'): ?>
                <div class="admin-buttons">
                    <a href="sua_san_pham.php?ma_san_pham=<?php echo $product['ma_san_pham']; ?>">Sửa</a>
                    <a href="xoa_san_pham.php?ma_san_pham=<?php echo $product['ma_san_pham']; ?>" class="delete">Xóa</a>
                </div>
            <?php else: ?>
                <form action="them_vao_gio_hang.php" method="POST">
                    <input type="hidden" name="ma_san_pham" value="<?php echo $product['ma_san_pham']; ?>">
                    <button type="submit">Thêm vào giỏ hàng</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer" id="footer">
        <a href="index.php" class="logofooter">PC Gaming 
            <span>|</span> 
            <br> 
            <p> PCGAMING.COM</p> 
        </a>
    </footer>
</body>
</html>