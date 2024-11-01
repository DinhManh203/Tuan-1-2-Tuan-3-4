<?php
include ('../db_connect.php');

if (isset($_POST['id'])) {
    // Chuyển đổi ID thành số nguyên để đảm bảo an toàn
    $id = intval($_POST['id']);

    // Chuẩn bị truy vấn SQL để xóa vai trò
    $query = "DELETE FROM mor_role WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);

    // Thực hiện truy vấn và kiểm tra kết quả
    if ($stmt->execute()) {
        echo "Role deleted successfully!";
    } else {
        // Cung cấp thông tin chi tiết về lỗi
        echo "Error deleting role: " . implode(":", $stmt->errorInfo());
    }
} else {
    echo "No ID provided for deletion.";
}
?>
