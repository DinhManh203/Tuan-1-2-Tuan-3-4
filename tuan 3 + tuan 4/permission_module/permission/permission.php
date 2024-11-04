<?php
include('../layout/header.php');
include('../db_connect.php');

// Kiểm tra kết nối CSDL
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Lấy tổng số quyền để tính toán số trang
$totalQuery = "SELECT COUNT(*) FROM mor_permission";
$totalResult = $conn->query($totalQuery);
$totalCount = $totalResult->fetchColumn();
$totalPages = ceil($totalCount / $limit);

// Truy vấn danh sách permissions
$query = "SELECT * FROM mor_permission LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bindParam(1, $limit, PDO::PARAM_INT);
$stmt->bindParam(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<body>
    <h1 class="title_h1">Permissions</h1>
    <form id="assignPermissionForm" method="post" action="../handlePer_Role/assign.php">
        <label for="roleSelect">Chọn vai trò:</label>
        <select id="roleSelect" name="role" required>
            <?php
            // Lấy danh sách vai trò từ CSDL
            $query = "SELECT * FROM mor_role";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC); 

            // Hiển thị các vai trò dưới dạng option
            foreach ($roles as $role) {
                echo '<option value="' . htmlspecialchars($role['id']) . '">' . htmlspecialchars($role['name']) . '</option>';
            }
            ?>
        </select>

        <label for="permissionSelect">Chọn permission:</label>
        <select id="permissionSelect" name="permission" required>
            <?php
            foreach ($permissions as $permission) {
                echo '<option value="' . htmlspecialchars($permission['id']) . '">' . htmlspecialchars($permission['name']) . '</option>';
            }
            ?>
        </select>

        <button type="submit">Gán Permission</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Update</th>
                <th>Delete</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($permissions as $row): ?>
            <tr>
                <td>
                    <button onclick="showPopup('update', <?= $row['id'] ?>, '<?= addslashes(htmlspecialchars($row['name'])) ?>', '<?= addslashes(htmlspecialchars($row['description'])) ?>')">Update</button>
                </td>
                <td>
                    <button onclick="deletePermission(<?= $row['id'] ?>, '<?= addslashes(htmlspecialchars($row['name'])) ?>', '<?= addslashes(htmlspecialchars($row['description'])) ?>')">Delete</button>
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer-actions d-flex justify-content-between">
        <button class="add-new-button" onclick="showPopup('add')">Add new</button>
        <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">« Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Next »</a>
        <?php endif; ?>
        </div>
    </div>

    <!-- Overlay để làm mờ nền khi mở popup -->
    <div class="overlay" id="overlay" onclick="closePopup()"></div>

    <!-- Popup form -->
    <div class="popup" id="popupForm">
        <h2 id="popupTitle">Permission - Edit</h2>
        <div class="box_popup">
            <input type="hidden" id="permissionId">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" placeholder="Enter Text" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" placeholder="Enter Text" required>
            </div>
        </div>
        <button onclick="submitForm()">Add</button>
    </div>

    <!-- Popup thông báo -->
    <div class="notification-popup" id="notificationPopup" style="display:none;">
        <h3 id="notificationTitle"></h3>
        <p><strong>Result:</strong></p>
        <p class="name_p">Name: <span id="notificationName"></span></p>
        <p>Description: <span id="notificationDescription"></span></p>
    </div>

    <!-- Popup xác nhận -->
    <div class="notification-popup" id="confirmPopup" style="display:none;">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this permission?</p>
        <p><strong>Name:</strong> <span id="confirmName"></span></p>
        <p><strong>Description:</strong> <span id="confirmDescription"></span></p>
        <button id="confirmYes" onclick="confirmDelete()">Yes</button>
        <button onclick="closeConfirmPopup()">No</button>
    </div>

    <script>
        let permissionToDelete = { id: null, name: '', description: '' };

        // Hiển thị popup với thông tin cụ thể cho thêm mới hoặc cập nhật
        function showPopup(action, id = null, name = '', description = '') {
            document.getElementById("popupForm").style.display = "block";
            document.getElementById("overlay").style.display = "block";

            if (action === 'update') {
                document.getElementById("popupTitle").innerText = "Permission - Update";
                document.getElementById("permissionId").value = id;
                document.getElementById("name").value = name;
                document.getElementById("description").value = description;
                document.querySelector("#popupForm button").innerText = "Update";
            } else {
                document.getElementById("popupTitle").innerText = "Permission - Add new";
                document.getElementById("permissionId").value = '';
                document.getElementById("name").value = '';
                document.getElementById("description").value = '';
                document.querySelector("#popupForm button").innerText = "Add";
            }
        }

        // Đóng popup
        function closePopup() {
            document.getElementById("popupForm").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }

        // Hiển thị thông báo
        function showNotification(title, name, description) {
            document.getElementById("notificationTitle").innerText = title;
            document.getElementById("notificationName").innerText = name;
            document.getElementById("notificationDescription").innerText = description;
            document.getElementById("notificationPopup").style.display = "block";
            setTimeout(closeNotification, 3000); // Tắt thông báo sau 3 giây
        }

        // Đóng thông báo
        function closeNotification() {
            document.getElementById("notificationPopup").style.display = "none";
        }

        // Xử lý gửi dữ liệu từ form
        function submitForm() {
            var id = document.getElementById("permissionId").value;
            var name = document.getElementById("name").value;
            var description = document.getElementById("description").value;

            var xhr = new XMLHttpRequest();
            if (id) {
                // Cập nhật quyền hiện có
                xhr.open("POST", '../handle_permission/update.php', true); // Chỉnh sửa đường dẫn
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        closePopup();
                        showNotification("Update permission successful!", name, description);
                        setTimeout(() => {
                            location.reload(); // Tải lại trang để cập nhật danh sách quyền
                        }, 2000); // Delay 2 giây
                    }
                };
                xhr.send("id=" + encodeURIComponent(id) + "&name=" + encodeURIComponent(name) + "&description=" + encodeURIComponent(description));
            } else {
                // Thêm quyền mới
                xhr.open("POST", '../handle_permission/add.php', true); // Chỉnh sửa đường dẫn
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        closePopup();
                        showNotification("Add new permission successful!", name, description);
                        setTimeout(() => {
                            location.reload(); // Tải lại trang để cập nhật danh sách quyền
                        }, 2000); // Delay 2 giây
                    }
                };
                xhr.send("name=" + encodeURIComponent(name) + "&description=" + encodeURIComponent(description));
            }
        }

        // Hàm xóa quyền
        function deletePermission(id, name, description) {
            permissionToDelete = { id, name, description }; // Lưu thông tin quyền cần xóa
            document.getElementById("confirmName").innerText = name;
            document.getElementById("confirmDescription").innerText = description;
            document.getElementById("confirmPopup").style.display = "block"; // Hiển thị popup xác nhận
            document.getElementById("overlay").style.display = "block"; // Hiển thị overlay
        }

        // Xác nhận xóa
        function confirmDelete() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", '../handle_permission/delete.php', true); // Chỉnh sửa đường dẫn
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    closeConfirmPopup(); // Đóng popup xác nhận
                    showNotification("Delete permission successful!", permissionToDelete.name, permissionToDelete.description);
                    setTimeout(() => {
                        location.reload(); // Tải lại trang để cập nhật danh sách quyền
                    }, 2000); // Delay 2 giây
                }
            };
            xhr.send("id=" + encodeURIComponent(permissionToDelete.id));
        }

        // Đóng popup xác nhận
        function closeConfirmPopup() {
            document.getElementById("confirmPopup").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
        document.getElementById("overlay").onclick = function() {
            closePopup(); // Đóng popup thông thường nếu mở
            closeConfirmPopup(); // Đóng popup xác nhận nếu mở
        }
    </script>
</body>
