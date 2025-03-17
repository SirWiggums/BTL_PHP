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

$sql = "SELECT username, password, role FROM account";

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
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
                <li> <a href="gioithieu.php">Giới thiệu</a></li>
                <li><a href="san_pham.php">Sản phẩm</a></li>
                <?php
                if (isset($_SESSION['username'])) {
                    if ($user_role == 'admin') {
                        echo '<li><a href="ql_nguoi_dung.php" style="color: black;">Quản lý người dùng</a></li>';
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

    <main>
        <h2>Danh sách người dùng</h2>
        <table>
            <thead>
                <tr>
                    <th>TK</th>
                    <th>MK</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'connect.php';

                $sql = "SELECT username, password, role FROM account";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['username']}</td>
                                <td>{$row['password']}</td>
                                <td>{$row['role']}</td>
                                <td>
                                    <a href='sua_nguoi_dung.php?username={$row['username']}'>Sửa</a>
                                    <a href='xoa_nguoi_dung.php?username={$row['username']}' class='delete'>Xóa</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Không có người dùng nào</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>

        <h2>Thêm người dùng mới</h2>
        <form action="them_nguoi_dung.php" method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <select name="role" required>
                <option value="user">Người dùng</option>
                <option value="admin">Quản trị viên</option>
            </select>
            <button type="submit">Thêm người dùng</button>
        </form>
    </main>

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