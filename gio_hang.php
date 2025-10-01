<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'connect.php';

$username = $_SESSION['username'];

// Lấy thông tin sản phẩm trong giỏ hàng
$sql = "SELECT gio_hang.id, sanpham.ten_san_pham, sanpham.gia_san_pham, sanpham.image, gio_hang.so_luong 
        FROM gio_hang 
        JOIN sanpham ON gio_hang.ma_san_pham = sanpham.ma_san_pham 
        WHERE gio_hang.username='$username'";
$result = $conn->query($sql);

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="CSS/HderFter.css">
    <link rel="stylesheet" href="GioHang.css">
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
    <script>
        function updateTotalPrice() {
            let totalPrice = 0;
            const rows = document.querySelectorAll('.cart-item');
            rows.forEach(row => {
                const price = parseFloat(row.querySelector('.item-price').innerText.replace(/\./g, ''));
                const quantity = parseInt(row.querySelector('.item-quantity').value);
                totalPrice += price * quantity;
            });
            document.getElementById('total-price').innerText = new Intl.NumberFormat('vi-VN').format(totalPrice) + ' VND';
        }

        function updateQuantity(id, quantity) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'cap_nhat_so_luong.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        updateTotalPrice();
                    } else {
                        alert('Cập nhật số lượng thất bại: ' + response.message);
                    }
                }
            };
            xhr.send('id=' + id + '&so_luong=' + quantity);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const quantityInputs = document.querySelectorAll('.item-quantity');
            quantityInputs.forEach(input => {
                input.addEventListener('change', (event) => {
                    const id = event.target.dataset.id;
                    const quantity = event.target.value;
                    updateQuantity(id, quantity);
                });
            });
            updateTotalPrice();
        });
    </script>
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
                <!-- <li><a href="san_pham.php">Sản phẩm</a></li> -->
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<li>Chào mừng, ' . $_SESSION['username'] . '!</li>';
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
        <h1>Giỏ hàng của bạn</h1>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<p style='color: red;'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']);
        }
        if (isset($_SESSION['success_message'])) {
            echo "<p style='color: green;'>" . $_SESSION['success_message'] . "</p>";
            unset($_SESSION['success_message']);
        }
        ?>
        <?php
        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Hình ảnh</th>
                        <th>Hành động</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                $total_price += $row['gia_san_pham'] * $row['so_luong'];
                $formatted_price = number_format($row['gia_san_pham'], 0, ',', '.');
                echo "<tr class='cart-item'>
                        <td>" . $row['ten_san_pham'] . "</td>
                        <td class='item-price'>" . $formatted_price . "</td>
                        <td>
                            <input type='number' class='item-quantity' name='so_luong' value='" . $row['so_luong'] . "' min='1' data-id='" . $row['id'] . "'>
                        </td>
                        <td><img src='images/" . $row['image'] . "' alt='" . $row['ten_san_pham'] . "' width='100'></td>
                        <td>
                            <form action='xoa_sp_tronggio.php' method='post'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <button type='submit'>Xóa</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</table>";
            echo "<h2>Tổng tiền: <span id='total-price'>" . number_format($total_price, 0, ',', '.') . " VND</span></h2>";
            echo "<form action='thanh_toan.php' method='post'>";
            echo "<button type='submit'>Thanh toán</button>";
            echo "</form>";
        } else {
            echo "Giỏ hàng của bạn trống.";
        }
        $conn->close();
        ?>
    </main>

    <footer class="footer" id="footer">
        <a href="index.php" class="logofooter">PC Gaming 
            <span>|</span> 
            <br> 
            <p> PCGAMING.COM</p> 
        </a>
        <div class="sanpham">
            <!-- <ul class="menu-footer">
                <h2>Sản Phẩm</h2>
                    <li><a href="#Acer">Acer</a></li>
                    <li><a href="#Macbook">Macbook</a></li>
                    <li><a href="#Asus">Asus</a></li>
                    <li><a href="#MSI">MSI</a></li>
            </ul> -->
        </div>
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