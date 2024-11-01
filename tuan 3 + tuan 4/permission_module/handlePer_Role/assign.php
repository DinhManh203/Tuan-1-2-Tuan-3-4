<?php
include('../db_connect.php');

// Kiểm tra kết nối CSDL
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Lấy dữ liệu từ biểu mẫu
$roleId = $_POST['role'];
$permissionId = $_POST['permission'];

try {
    // Kiểm tra xem vai trò đã được gán quyền chưa
    $checkQuery = "SELECT COUNT(*) FROM mor_role_permission WHERE mor_role_id = :role_id AND mor_permission_id = :permission_id";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':role_id', $roleId);
    $checkStmt->bindParam(':permission_id', $permissionId);
    $checkStmt->execute();
    $exists = $checkStmt->fetchColumn();

    if ($exists > 0) {
        echo "Vai trò này đã được gán quyền này.";
    } else {
        // Chèn dữ liệu vào bảng mor_role_permission
        $query = "INSERT INTO mor_role_permission (mor_role_id, mor_permission_id) VALUES (:role_id, :permission_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':role_id', $roleId);
        $stmt->bindParam(':permission_id', $permissionId);
        
        if ($stmt->execute()) {
            echo "Gán Permission thành công!";
        } else {
            echo "Có lỗi khi gán Permission.";
        }
    }
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

// Đóng kết nối
$conn = null;
?>
