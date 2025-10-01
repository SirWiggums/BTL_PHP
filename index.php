<?php
session_start();
// if (!isset($_SESSION['username'])) {
//     header("Location: login.html");
//     exit();
// }

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

//shearch
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

$sql = "SELECT ma_san_pham, ten_san_pham, gia_san_pham, gia_cu, mo_ta_ngan, mo_ta_chi_tiet, so_luong, image 
        FROM sanpham 
        WHERE ten_san_pham LIKE '%$search_query%' OR gia_san_pham LIKE '%$search_query%'";
$result = $conn->query($sql);

// $total_items = 0;
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


    </style>
</head>

<body>
    <header>
        <!-- <img src="Anh/LOGO.png" alt="" width="100px" height="130px"> -->
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
                <li><a href="index.php" style="color: black;">Trang chủ</a></li>
                <li> <a href="gioithieu.php">Giới thiệu</a></li>
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
                    if ($user_role == 'admin') {
                        echo '<li><a href="ql_nguoi_dung.php">Quản lý người dùng</a></li>';
                        echo '<li><a href="them_san_pham.html">Thêm sản phẩm</a></li>';
                        echo '<li><a href="thong_ke.php">Thống kê</a></li>';
                    }
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
    <section class="banner" id="banner">
        <img src="Anh/Anh2/33.webp" alt="" width="100%" height="300px" id="banner-image">
        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
        <button class="next" onclick="changeSlide(1)">&#10095;</button>
    </section>

    
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
                echo "<p>Số lượng: {$row['so_luong']}</p>";
            
                // Kiểm tra xem người dùng đã đăng nhập hay chưa
                if (!isset($_SESSION['username'])) {
                    echo "<p><a href='login.html' style='color: red;'>Đăng nhập để mua hàng</a></p>";
                    echo "<p><button type='submit' href='login.html'>Thêm vào giỏ hàng</button></p>";
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
                        echo "<p style='color: red; font-size: 1.5em;'>Hết hàng</p>";
                    }
                
                    // Hiển thị nút chỉnh sửa/xóa nếu là admin
                    if ($user_role == 'admin') {
                        echo "<div class='admin-actions'>
                                <a href='sua_san_pham.php?ma_san_pham={$row['ma_san_pham']}' style='color: blue;'>Sửa</a> |
                                <a href='xoa_san_pham.php?ma_san_pham={$row['ma_san_pham']}' style='color: red;' onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm này?\");'>Xóa</a>
                              </div>";
                    }
                }
                echo "</div>";
            }
        } else {
            echo "<p>Không có sản phẩm nào</p>";
        }
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