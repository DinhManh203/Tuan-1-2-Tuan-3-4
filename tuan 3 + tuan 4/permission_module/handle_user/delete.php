<?php
include('../db_connect.php');

// Kiểm tra xem ID người dùng đã được gửi qua phương thức POST chưa
if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Chuyển đổi ID thành số nguyên

    // Câu truy vấn để xóa người dùng
    $query = "DELETE FROM mor_user WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Thực thi câu truy vấn
    if ($stmt->execute()) {
        echo "User deleted successfully.";
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "No user ID specified.";
}
?>
