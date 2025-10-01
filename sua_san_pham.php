<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'connect.php';

// Kiểm tra vai trò của người dùng
$username = $_SESSION['username'];
$sql = "SELECT role FROM account WHERE username='$username'";
$result = $conn->query($sql);
$user_role = '';
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_role = $row['role'];
}

// Chỉ cho phép admin sửa sản phẩm
if ($user_role != 'admin') {
    echo "Bạn không có quyền sửa sản phẩm.";
    $conn->close();
    exit();
}

// Lấy mã sản phẩm từ URL
$ma_san_pham = $_GET['ma_san_pham'];

// Lấy thông tin sản phẩm từ cơ sở dữ liệu
$sql = "SELECT * FROM sanpham WHERE ma_san_pham='$ma_san_pham'";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/ThemSP.css">
    <link rel="stylesheet" href="CSS/HderFter.css">
    
    <title>Sửa sản phẩm</title>
</head>
<body>
    <header id="header">
        <img src="Anh/Anh2/pcgaming.png" alt="" width="100px" height="130px">
        <a href="index.php" class="logo">PC Gaming
            <span>|</span> 
            <br>
            <p> PCGAMING.COM</p>
        </a>

        <!-- <div class="down">
            <div class="seach">
                <input type="" name="" placeholder="Tìm kiếm" >
            </div>
            gio hang
            <div class="button">
                <button><i class="fa-solid fa-magnifying-glass"></i><a href=""></a></button>
            </div>
        </div> -->

        <ul class="navigation">
            <li><a href="index.php">Trang chủ</a></li>
        </ul>
        
        <div class="giohang">
            <!-- sua href gio hang -->
           <a href=""><i class="fa-solid fa-cart-shopping"></i></a>
        </div>
    </header>
    
    <br>
    <br>
    <br>

    <h2>Sửa sản phẩm</h2>
    <form action="cap_nhat_san_pham.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ma_san_pham" value="<?php echo $product['ma_san_pham']; ?>">
        <label for="ten_san_pham">Tên sản phẩm:</label>
        <input type="text" id="ten_san_pham" name="ten_san_pham" value="<?php echo $product['ten_san_pham']; ?>" required><br>
        <label for="hang_sp">Hãng sản phẩm:</label>
        <input type="text" id="hang_sp" name="hang_sp" value="<?php echo $product['hang_sp']; ?>" required><br>
        <label for="gia_san_pham">Giá sản phẩm:</label>
        <input type="text" id="gia_san_pham" name="gia_san_pham" value="<?php echo $product['gia_san_pham']; ?>" required><br>
        <label for="gia_cu">Giá cũ:</label>
        <input type="text" id="gia_cu" name="gia_cu" value="<?php echo $product['gia_cu']; ?>"><br>
        <label for="mo_ta_ngan">Mô tả ngắn:</label>
        <textarea id="mo_ta_ngan" name="mo_ta_ngan"><?php echo $product['mo_ta_ngan']; ?></textarea><br>
        <label for="mo_ta_chi_tiet">Mô tả chi tiết:</label>
        <textarea id="mo_ta_chi_tiet" name="mo_ta_chi_tiet"><?php echo $product['mo_ta_chi_tiet']; ?></textarea><br>
        <label for="so_luong">Số lượng:</label>
        <input type="number" id="so_luong" name="so_luong" value="<?php echo $product['so_luong']; ?>" required><br>
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image"><br>
        <input type="submit" value="Cập nhật sản phẩm">
    </form>
    <br>
    <br>
    <br>
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
</body>
</html>