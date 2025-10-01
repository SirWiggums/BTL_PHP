<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

include 'connect.php';

$username = $_SESSION['username'];
$id = $_POST['id'];
$so_luong = $_POST['so_luong'];

$sql = "UPDATE gio_hang SET so_luong = ? WHERE id = ? AND username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $so_luong, $id, $username);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

$stmt->close();
$conn->close();
?>