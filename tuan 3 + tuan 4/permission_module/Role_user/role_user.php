<?php
include('../db_connect.php');
include('../layout/header.php');

// Kiểm tra kết nối CSDL
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Lấy danh sách người dùng và vai trò từ bảng mor_acl
$query = "
    SELECT a.mor_user_id, GROUP_CONCAT(r.name ORDER BY r.name SEPARATOR ', ') AS role_names, u.name AS user_name
    FROM mor_acl a
    JOIN mor_user u ON a.mor_user_id = u.id
    JOIN mor_role r ON a.mor_role_id = r.id
    GROUP BY a.mor_user_id, u.name
";
$result = $conn->query($query);

// Xử lý yêu cầu gỡ vai trò khỏi người dùng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_role'])) {
    $user_id = intval($_POST['user_id']);
    $role_id = intval($_POST['role_id']);

    // Xóa vai trò khỏi người dùng
    $delete_query = "DELETE FROM mor_acl WHERE mor_user_id = ? AND mor_role_id = ?";
    $stmt = $conn->prepare($delete_query);
    
    // Sử dụng bindValue thay cho bind_param
    $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $role_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo '<script>alert("Đã gỡ vai trò thành công!"); window.location.href = ""; </script>'; // Tự động tải lại trang
    } else {
        echo '<script>alert("Có lỗi xảy ra khi gỡ vai trò.");</script>';
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Danh sách Người dùng và Vai trò</h1>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Người dùng</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Hiển thị danh sách người dùng và vai trò
            if ($result->rowCount() > 0) { // Sử dụng rowCount() để kiểm tra nếu có dữ liệu
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['user_name']) . '</td>';
                    echo '<td>';

                    // Tạo danh sách vai trò cho từng người dùng
                    $role_array = explode(', ', $row['role_names']);
                    foreach ($role_array as $role) {
                        echo '<div class="role-item">' . htmlspecialchars($role) . '</div>';
                        echo '<hr class="role-divider">'; // Đường kẻ ngang giữa các vai trò
                    }

                    echo '</td>';
                    echo '<td>';

                    // Form để gỡ vai trò cho từng vai trò
                    foreach ($role_array as $role) {
                        // Lấy ID vai trò từ tên vai trò
                        $role_id_query = "SELECT id FROM mor_role WHERE name = ?";
                        $role_stmt = $conn->prepare($role_id_query);
                        $role_stmt->bindValue(1, $role, PDO::PARAM_STR);
                        $role_stmt->execute();
                        $role_stmt->bindColumn(1, $role_id);
                        $role_stmt->fetch();

                        echo '<form method="POST" style="display:block; margin-bottom: 5px;">';
                        echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($row['mor_user_id']) . '">';
                        echo '<input type="hidden" name="role_id" value="' . intval($role_id) . '">';
                        echo '<button type="submit" name="remove_role" class="btn btn-danger btn-sm">Gỡ vai trò</button>';
                        echo '</form>';
                        echo '<hr class="action-divider">'; // Đường kẻ ngang giữa các nút
                    }

                    echo '</td>'; // Kết thúc cột hành động
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">Không có dữ liệu</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include('../layout/footer.php');
$conn = null; // Đóng kết nối CSDL
?>
