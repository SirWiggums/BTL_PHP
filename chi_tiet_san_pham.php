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
    <style>
        
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