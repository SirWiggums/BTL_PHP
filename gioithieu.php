<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/HderFter.css">
    <title>PC Gaming</title>
</head>
<body>
    <header>
        <img src="Anh/pcgaming.png" alt="" width="100px" height="130px">
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
                <li> <a href="gioithieu.php" style="color: black;">Giới thiệu</a></li>
                <li><a href="san_pham.php">Sản phẩm</a></li>
                <?php
                if (isset($_SESSION['username'])) {
                    
                    include 'connect.php';
                    
                    $username = $_SESSION['username'];
                    $sql = "SELECT role FROM account WHERE username='$username'";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        if ($row['role'] == 'admin') {
                            echo '<li><a href="ql_nguoi_dung.php">Quản lý người dùng</a></li>';
                            echo '<li><a href="them_san_pham.html">Thêm sản phẩm</a></li>';
                            echo '<li><a href="thong_ke.php">Thống kê</a></li>';
                        }
                    }

                    // Lấy số lượng sản phẩm trong giỏ hàng
                    $sql_cart = "SELECT SUM(so_luong) AS total_items FROM gio_hang WHERE username='$username'";
                    $result_cart = $conn->query($sql_cart);
                    $total_items = 0;
                    if ($result_cart->num_rows > 0) {
                        $row_cart = $result_cart->fetch_assoc();
                        $total_items = $row_cart['total_items'];
                    }
                    
                    $conn->close();
                    
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
    
    <br>
    <br>
    <br>
        <h1>Giới thiệu</h1>
    <br>
    <br>
        <div class="intro-container">
            <h2>Chào mừng bạn đến với trang web của chúng tôi!</h2>
            <p>
                Tại đây, chúng tôi cung cấp cho bạn những chiếc máy tính hàng đầu với công nghệ tiên tiến nhất. Mỗi sản phẩm của chúng tôi đều được thiết kế để mang lại trải nghiệm tốt nhất cho người dùng, từ hiệu suất mạnh mẽ, độ bền cao, đến thiết kế thời trang.
                <br><br>
                Máy tính của chúng tôi không chỉ phục vụ cho công việc học tập, làm việc mà còn là lựa chọn hoàn hảo cho những người yêu thích giải trí như xem phim, chơi game. Với nhiều lựa chọn từ các thương hiệu nổi tiếng, chúng tôi tin rằng bạn sẽ tìm thấy chiếc máy tính phù hợp nhất với nhu cầu của mình.
                <br><br>
                Hãy khám phá ngay bộ sưu tập máy tính của chúng tôi và trải nghiệm sự khác biệt! <a href="index.php">Tại đây</a>
            </p>
        </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
<!--------------------------------------------------------------------------------Footer-------------------------------------------------------------------------------->
    <footer class="footer" id="footer">
        <a href="bài tập lớn.html" class="logofooter">PC Gaming 
            <span>|</span> 
            <br> 
            <p> PCGAMING.COM</p> 
        </a>
        <div class="sanpham">
            <!-- <ul class="menu-footer">
                <h2>Sản Phẩm</h2>
                    <li><a href="Main.html#Acer">Acer</a></li>
                    <li><a href="Main.html#Macbook">Macbook</a></li>
                    <li><a href="Main.html#Asus">Asus</a></li>
                    <li><a href="Main.html#MSI">MSI</a></li>
            </ul> -->
        </div>
        <div class="contact">
            <h2>Contact</h2>
            <a href="#"><i class="fa-brands fa-facebook"></i></a>
            <br>
            <a href=""><i class="fa-brands fa-instagram-square"></i></a>
            <br>
            <a href=""><i class="fa-brands fa-twitter"></i></a>
            
        </div>
        <div class="lienhe">
            <h2>Liên Hệ</h2>
            <p><span>Phone:</span> 34597348573498 </p>
            <p><span >Mail:</span> hellotoila@gmail.com</p>
        </div>
    </footer>
</body>
</html>