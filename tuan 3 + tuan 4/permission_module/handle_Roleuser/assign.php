<?php
include('../db_connect.php');

// Kiểm tra kết nối CSDL
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Lấy dữ liệu từ biểu mẫu
$userId = $_POST['user'];
$roleId = $_POST['role'];

try {
    // Kiểm tra xem người dùng đã được gán vai trò chưa
    $checkQuery = "SELECT COUNT(*) FROM mor_acl WHERE mor_user_id = :user_id AND mor_role_id = :role_id";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':user_id', $userId);
    $checkStmt->bindParam(':role_id', $roleId);
    $checkStmt->execute();
    $exists = $checkStmt->fetchColumn();

    if ($exists > 0) {
        echo "Người dùng này đã được gán vai trò này.";
    } else {
        // Chèn dữ liệu vào bảng mor_user_role
        $query = "INSERT INTO mor_acl (mor_user_id, mor_role_id) VALUES (:user_id, :role_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':role_id', $roleId);
        
        if ($stmt->execute()) {
            echo "Gán vai trò thành công!";
        } else {
            echo "Có lỗi khi gán vai trò.";
        }
    }
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

// Đóng kết nối
$conn = null;
?>
