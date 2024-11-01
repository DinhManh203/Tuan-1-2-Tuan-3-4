<?php
include('../db_connect.php');
include('../layout/header.php');

// Kiểm tra kết nối CSDL
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Lấy danh sách quyền và vai trò từ CSDL, gộp quyền bằng GROUP_CONCAT
$query = "
    SELECT r.id AS role_id, r.name AS role_name, GROUP_CONCAT(p.name SEPARATOR ', ') AS permissions
    FROM mor_role_permission rp
    JOIN mor_role r ON rp.mor_role_id = r.id
    JOIN mor_permission p ON rp.mor_permission_id = p.id
    GROUP BY r.id
";
$result = $conn->query($query);

// Xử lý yêu cầu gỡ quyền
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_permission'])) {
    $role_id = intval($_POST['role_id']);
    $permission_id = intval($_POST['permission_id']);

    // Xóa quyền khỏi vai trò
    $delete_query = "DELETE FROM mor_role_permission WHERE mor_role_id = ? AND mor_permission_id = ?";
    $stmt = $conn->prepare($delete_query);
    
    // Sử dụng bindValue thay cho bind_param
    $stmt->bindValue(1, $role_id, PDO::PARAM_INT);
    $stmt->bindValue(2, $permission_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo '<script>alert("Đã gỡ quyền thành công!"); window.location.href = ""; </script>'; // Tự động tải lại trang
    } else {
        echo '<script>alert("Có lỗi xảy ra khi gỡ quyền.");</script>';
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Danh sách Permission cho Role</h1>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Vai trò</th>
                <th>Permission</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Hiển thị danh sách quyền cho vai trò
            if ($result->rowCount() > 0) { // Sử dụng rowCount() để kiểm tra nếu có dữ liệu
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['role_name']) . '</td>';
                    echo '<td>';

                    // Tạo danh sách quyền cho từng quyền
                    $permissions_array = explode(', ', $row['permissions']);
                    foreach ($permissions_array as $permission) {
                        echo '<div class="permission-item">' . htmlspecialchars($permission) . '</div>';
                        echo '<hr class="permission-divider">'; // Đường kẻ ngang giữa các quyền
                    }

                    echo '</td>';
                    echo '<td>';

                    // Form để gỡ quyền cho từng quyền
                    foreach ($permissions_array as $permission) {
                        // Lấy ID quyền từ tên quyền
                        $permission_id_query = "SELECT id FROM mor_permission WHERE name = ?";
                        $perm_stmt = $conn->prepare($permission_id_query);
                        $perm_stmt->bindValue(1, $permission, PDO::PARAM_STR);
                        $perm_stmt->execute();
                        $perm_stmt->bindColumn(1, $permission_id);
                        $perm_stmt->fetch();

                        echo '<form method="POST" style="display:block; margin-bottom: 5px;">';
                        echo '<input type="hidden" name="role_id" value="' . htmlspecialchars($row['role_id']) . '">';
                        echo '<input type="hidden" name="permission_id" value="' . intval($permission_id) . '">';
                        echo '<button type="submit" name="remove_permission" class="btn btn-danger btn-sm">Gỡ quyền</button>';
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
