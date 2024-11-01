<?php
include('../db_connect.php');

// Kiểm tra kết nối CSDL
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Lấy ID permission cần xóa từ yêu cầu POST
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Câu lệnh SQL để xóa permission
    $query = "DELETE FROM mor_permission WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Không thể xóa quyền."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID permission không được cung cấp."]);
}
