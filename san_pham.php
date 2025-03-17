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

// Lấy danh sách sản phẩm
$sql = "SELECT ma_san_pham, ten_san_pham, gia_san_pham, gia_cu, mo_ta_ngan, mo_ta_chi_tiet, so_luong, image FROM sanpham";
$result = $conn->query($sql);

//shearch
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

$sql = "SELECT ma_san_pham, ten_san_pham, gia_san_pham, gia_cu, mo_ta_ngan, mo_ta_chi_tiet, so_luong, image 
        FROM sanpham 
        WHERE ten_san_pham LIKE '%$search_query%' OR gia_san_pham LIKE '%$search_query%'";
$result = $conn->query($sql);

$total_items = 0;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql_cart = "SELECT SUM(so_luong) AS total_items FROM gio_hang WHERE username='$username'";
    $result_cart = $conn->query($sql_cart);
    if ($result_cart->num_rows > 0) {
        $row_cart = $result_cart->fetch_assoc();
        $total_items = $row_cart['total_items'];
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/HderFter.css">
    <link rel="stylesheet" href="CSS/SP.css">
    <style>
        .search-form {
            display: flex;
            align-items: center;
        }
        .search-form input[type="text"] {
            padding: 2px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            outline: none;
        }
        .search-form button {
            padding: 7px;
            border: 1px solid #ccc;
            border-left: none;
            background-color: #3498db;
            color: #fff;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            outline: none;
        }
        .search-form button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <header>
        <img src="Anh/pcgaming.png" alt="" width="100px" height="130px">
        <a href="index.php" class="logo">PC Gaming
            <span>|</span> 
            <br>
            <p> PCGAMING.COM</p>
        </a>

        <div class="down">
            <div class="seach">
                <form class="search-form" action="tim_kiem.php" method="GET">
                    <input type="text" name="search" placeholder="Tìm kiếm" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
                <li> <a href="gioithieu.php">Giới thiệu</a></li>
                <li><a href="san_pham.php" style="color: black;">Sản phẩm</a></li>
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
    
    <h1>Danh sách sản phẩm</h1>
    <div class="product-grid">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $formatted_price = number_format($row['gia_san_pham'], 0, ',', '.');
                $formatted_old_price = !empty($row['gia_cu']) ? number_format($row['gia_cu'], 0, ',', '.') : '';

                echo "<div class='product-item'>
                        <a href='chi_tiet_san_pham.php?ma_san_pham={$row['ma_san_pham']}'>
                            <img src='images/{$row['image']}' alt='{$row['ten_san_pham']}'>
                            <h3>{$row['ten_san_pham']}</h3>
                        </a>
                        <p class='price'>{$formatted_price} VND</p>";
                if (!empty($formatted_old_price)) {
                    echo "<p class='old-price'>{$formatted_old_price} VND</p>";
                }
                echo "<p>{$row['mo_ta_ngan']}</p>
                        <p>{$row['mo_ta_chi_tiet']}</p>
                        <p>Số lượng: {$row['so_luong']}</p>";
                if ($user_role == 'admin') {
                    echo "<div class='admin-buttons'>
                            <a href='sua_san_pham.php?ma_san_pham={$row['ma_san_pham']}'>Sửa</a>
                            <a href='xoa_san_pham.php?ma_san_pham={$row['ma_san_pham']}' class='delete'>Xóa</a>
                          </div>";
                } else {
                    if ($row['so_luong'] > 0) {
                        echo "<form action='them_vao_gio_hang.php' method='POST'>
                            <input type='hidden' name='ma_san_pham' value='{$row['ma_san_pham']}'>
                            <button type='submit' style='
                                background-color: #3498db;
                                color: #fff;
                                padding: 10px 20px;
                                border: none;
                                border-radius: 5px;
                                cursor: pointer;
                                font-size: 1em;
                                transition: background-color 0.3s;
                            '>Thêm vào giỏ hàng</button>
                        </form>";
                    } else {
                        echo "<p style='color: red; font-size: 1.5em;' >Hết hàng</p>";
                    }
                }
                echo "</div>";
            }
        } else {
            echo "<p>Không có sản phẩm nào</p>";
        }
        $conn->close();
        ?>
    </div>

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