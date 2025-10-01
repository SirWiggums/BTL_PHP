<?php
session_start();


include 'connect.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
$username = '';
$user_role = '';
$total_items = 0;

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Lấy vai trò của người dùng
    $sql = "SELECT role FROM account WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_role = $row['role'];
    }

    // Lấy số lượng sản phẩm trong giỏ hàng
    $sql_cart = "SELECT SUM(so_luong) AS total_items FROM gio_hang WHERE username='$username'";
    $result_cart = $conn->query($sql_cart);
    if ($result_cart->num_rows > 0) {
        $row_cart = $result_cart->fetch_assoc();
        $total_items = $row_cart['total_items'];
    }
}

// Lấy danh sách sản phẩm
$sql = "SELECT ma_san_pham, ten_san_pham, gia_san_pham, gia_cu, mo_ta_ngan, mo_ta_chi_tiet, so_luong, image FROM sanpham";
$result = $conn->query($sql);
// Lấy danh sách các hãng sản phẩm
$sql_hang_sp = "SELECT DISTINCT hang_sp FROM sanpham";
$result_hang_sp = $conn->query($sql_hang_sp);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">    <link rel="stylesheet" href="CSS/HderFter.css">
    <title>PC Gaming</title>
    <style>
        
        /* SP */
        .dropdown {
        position: relative;
        display: inline-block;
        }
    
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }
    
        .dropdown-menu li {
            list-style: none;
        }
    
        .dropdown-menu li a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
    
        .dropdown-menu li a:hover {
            background-color: #f1f1f1;
        }
    
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .banner {
        position: relative;
            width: 100%;
            height: 100px; /* banner cao 100px, bạn chỉnh lại = 10px hay 50px tuỳ ý */
            overflow: hidden;
        }
        .banner::before {
            content: "";
            position: absolute;
            inset: 0;
            background: url('Anh/Anh2/33.webp') center/cover no-repeat;
            filter: blur(10px); /* làm mờ */
            transform: scale(1.1); /* phóng to để che hết */
            z-index: 0;
        }

        /* ảnh chính */
        #banner-image {
            position: relative;
            z-index: 1;
            max-width: 100%;
            height: 100%;       /* khớp chiều cao banner */
            object-fit: contain; /* ảnh nằm gọn trong khung, không bị méo */
        }
        
        .prev, .next {
            /* cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none; */

                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 2;
                cursor: pointer;
                background: rgba(0,0,0,0.4);
                color: #fff;
                border: none;
                padding: 10px;
                border-radius: 50%;
        }
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }
        .prev {
            left: 0;
            border-radius: 3px 0 0 3px;
        }
        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8);
        }
        
    </style>
</head>
    <section class="banner" id="banner">
        <img src="Anh/Anh2/33.webp" alt="" width="100%" height="300px" id="banner-image">
        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="next" onclick="changeSlide(1)">&#10095;</button>
    </section>
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
                <li> <a href="gioithieu.php" style="color: black;">Giới thiệu</a></li>
                <li class="dropdown">
                    <a href="#">Sản phẩm</a>
                    <ul class="dropdown-menu">
                        <?php
                        if ($result_hang_sp->num_rows > 0) {
                            while ($row_hang = $result_hang_sp->fetch_assoc()) {
                                $hang_sp = htmlspecialchars($row_hang['hang_sp']);
                                echo "<li><a href='san_pham.php?hang_sp={$hang_sp}'>{$hang_sp}</a></li>";
                            }
                        } else {
                            echo "<li><a href='#'>Không có hãng sản phẩm</a></li>";
                        }
                        ?>
                    </ul>
                </li>
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
                                        echo '<li>
                            <a href="gio_hang.php">
                                <i class="fa-solid fa-cart-shopping"></i> ' . $total_items . '
                            </a>
                          </li>';
                    echo '<li><a href="logout.php">Đăng xuất</a></li>';
                } else {
                    echo '<li><a href="login.html">Đăng nhập</a></li>';
                    echo '<li><a href="register.html">Đăng ký</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

        <h1>Giới thiệu</h1>

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

        <script>
        let slideIndex = 0;
        const images = ["Anh/Anh2/11.jpg", "Anh/Anh2/22.webp", "Anh/Anh2/33.webp"];

        function showSlide(index) {
            const bannerImage = document.getElementById("banner-image");
            slideIndex = (index + images.length) % images.length; // Đảm bảo chỉ số nằm trong khoảng hợp lệ
            bannerImage.src = images[slideIndex];
        }

        function changeSlide(n) {
            showSlide(slideIndex + n);
        }

        function autoSlide() {
            changeSlide(1);
            setTimeout(autoSlide, 3000); // Thay đổi hình ảnh sau mỗi 5 giây
        }

        window.onload = function() {
            showSlide(slideIndex);
            setTimeout(autoSlide, 3000); // Bắt đầu tự động thay đổi hình ảnh
        };

        window.addEventListener("scroll", function () {
            const header = document.querySelector("header");
            if (window.scrollY > 50) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        });
    </script>

</body>
</html>