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

// Lấy danh sách các hãng sản phẩm
$sql_hang_sp = "SELECT DISTINCT hang_sp FROM sanpham";
$result_hang_sp = $conn->query($sql_hang_sp);



$filter = isset($_GET['filter']) ? $_GET['filter'] : 'day'; // Mặc định là hiển thị theo ngày

switch ($filter) {
    case 'month':
        $sql_revenue = "SELECT DATE_FORMAT(ngay_tao, '%Y-%m') as date, SUM(tong_tien) as revenue 
                        FROM hoa_don 
                        GROUP BY DATE_FORMAT(ngay_tao, '%Y-%m')";
        break;
    case 'year':
        $sql_revenue = "SELECT DATE_FORMAT(ngay_tao, '%Y') as date, SUM(tong_tien) as revenue 
                        FROM hoa_don 
                        GROUP BY DATE_FORMAT(ngay_tao, '%Y')";
        break;
    case 'day':
    default:
        $sql_revenue = "SELECT DATE(ngay_tao) as date, SUM(tong_tien) as revenue 
                        FROM hoa_don 
                        GROUP BY DATE(ngay_tao)";
        break;
}

$result_revenue = $conn->query($sql_revenue);
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
                <li><a href="gioithieu.php">Giới thiệu</a></li>
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
                <li><a href="ql_nguoi_dung.php">Quản lý người dùng</a></li>
                <li><a href="them_san_pham.html">Thêm sản phẩm</a></li>
                <li><a href="thong_ke.php" style="color: black;">Thống kê</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>
            </ul>
        </nav>
    </header>
                        <br><br><br>
    <h1>Thống kê doanh thu</h1>
    <form method="GET" action="thong_ke.php">
        <label for="filter">Hiển thị theo:</label>
        <select name="filter" id="filter">
            <option value="day" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'day') ? 'selected' : ''; ?>>Ngày</option>
            <option value="month" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'month') ? 'selected' : ''; ?>>Tháng</option>
            <option value="year" <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'year') ? 'selected' : ''; ?>>Năm</option>
        </select>
        <button type="submit">Lọc</button>
    </form>


    <table border="1">
        <tr>
            <th><?php echo ($filter == 'day') ? 'Ngày' : (($filter == 'month') ? 'Tháng' : 'Năm'); ?></th>
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

<form action="export_excel.php" method="get">
    <input type="hidden" name="filter" value="<?php echo $filter; ?>">
    <button type="submit">📥 Xuất Excel</button>
</form>


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