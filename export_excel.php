<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit();
}

include 'connect.php';

// --- TỰ ĐỘNG TÌM vendor/autoload.php Ở NHIỀU VỊ TRÍ ---
$possible = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/vendor/autoload_runtime.php' // composer v2 fallback (thường không cần)
];

$autoload = null;
foreach ($possible as $p) {
    if (file_exists($p)) { $autoload = $p; break; }
}

if (!$autoload) {
    // Thông báo rõ ràng để bạn biết phải làm gì
    echo "<h2>Không tìm thấy file <code>vendor/autoload.php</code></h2>";
    echo "<p>Hãy kiểm tra:</p>";
    echo "<ul>
            <li>Bạn đã chạy <code>composer require phpoffice/phpspreadsheet</code> trong thư mục: <strong>" . __DIR__ . "</strong> chưa?</li>
            <li>Chạy trong terminal: <code>cd " . __DIR__ . " &amp;&amp; composer require phpoffice/phpspreadsheet</code></li>
            <li>Nếu đã chạy composer ở thư mục cha, hãy di chuyển vendor vào thư mục hiện tại hoặc chỉnh đường dẫn require cho đúng.</li>
            <li>Bạn có thể tìm file autoload bằng PowerShell: <code>Get-ChildItem -Path D:\\App\\xampp\\htdocs -Filter autoload.php -Recurse</code></li>
          </ul>";
    exit;
}

require $autoload; // bây giờ an toàn để require

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// --- Lấy filter nếu có (gửi bằng GET từ form) ---
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'day';
switch ($filter) {
    case 'month':
        $sql_revenue = "SELECT DATE_FORMAT(ngay_tao, '%Y-%m') as date, SUM(tong_tien) as revenue 
                        FROM hoa_don GROUP BY DATE_FORMAT(ngay_tao, '%Y-%m')";
        break;
    case 'year':
        $sql_revenue = "SELECT DATE_FORMAT(ngay_tao, '%Y') as date, SUM(tong_tien) as revenue 
                        FROM hoa_don GROUP BY DATE_FORMAT(ngay_tao, '%Y')";
        break;
    case 'day':
    default:
        $sql_revenue = "SELECT DATE(ngay_tao) as date, SUM(tong_tien) as revenue 
                        FROM hoa_don GROUP BY DATE(ngay_tao)";
        break;
}

// Query dữ liệu
$result_revenue = $conn->query($sql_revenue);
$result_invoices = $conn->query("SELECT * FROM hoa_don");

// Tạo spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header cho phần thống kê doanh thu
$sheet->setCellValue('A1', ($filter == 'day' ? 'Ngày' : ($filter == 'month' ? 'Tháng' : 'Năm')));
$sheet->setCellValue('B1', 'Doanh thu (VND)');

$rowIndex = 2;
if ($result_revenue && $result_revenue->num_rows > 0) {
    while ($r = $result_revenue->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowIndex, $r['date']);
        $sheet->setCellValue('B' . $rowIndex, $r['revenue']);
        $rowIndex++;
    }
} else {
    $sheet->setCellValue('A2', 'Không có dữ liệu');
    $rowIndex = 4;
}

// Bo sung gap 1 hàng rồi viết header danh sách hóa đơn
$invoiceHeaderRow = $rowIndex + 1;
$sheet->setCellValue('A' . $invoiceHeaderRow, 'Mã hóa đơn');
$sheet->setCellValue('B' . $invoiceHeaderRow, 'Nick');
$sheet->setCellValue('C' . $invoiceHeaderRow, 'Tên');
$sheet->setCellValue('D' . $invoiceHeaderRow, 'SĐT');
$sheet->setCellValue('E' . $invoiceHeaderRow, 'Ngày tạo');
$sheet->setCellValue('F' . $invoiceHeaderRow, 'Tổng tiền (VND)');

$rowIndex = $invoiceHeaderRow + 1;
if ($result_invoices && $result_invoices->num_rows > 0) {
    while ($r = $result_invoices->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowIndex, $r['ma_hoa_don']);
        $sheet->setCellValue('B' . $rowIndex, $r['username']);
        $sheet->setCellValue('C' . $rowIndex, $r['ten']);
        $sheet->setCellValue('D' . $rowIndex, $r['so_dien_thoai']);
        $sheet->setCellValue('E' . $rowIndex, $r['ngay_tao']);
        $sheet->setCellValue('F' . $rowIndex, $r['tong_tien']);
        $rowIndex++;
    }
}

// Gửi file về client
$writer = new Xlsx($spreadsheet);
$filename = "thong_ke_" . date('Ymd_His') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $filename .'"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
