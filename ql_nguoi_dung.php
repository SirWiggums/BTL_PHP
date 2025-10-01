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

// Lấy danh sách các hãng sản phẩm
$sql_hang_sp = "SELECT DISTINCT hang_sp FROM sanpham";
$result_hang_sp = $conn->query($sql_hang_sp);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/HderFter.css">
    <link rel="stylesheet" href="CSS/SP.css">
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

        <nav>
            <ul>
                <li><a href="index.php">Trang chủ</a></li>
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
                        echo '<li><a href="ql_nguoi_dung.php" style="color: black;">Quản lý người dùng</a></li>';
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