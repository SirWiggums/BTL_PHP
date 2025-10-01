<?php
$conn = mysqli_connect('localhost', 'root', '', 'testketnoicsdl');
//('sql212.infinityfree.com', 'if0_38539812', 'NhomHaiBanPC', 'if0_38539812_testketnoicsdl');
// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>