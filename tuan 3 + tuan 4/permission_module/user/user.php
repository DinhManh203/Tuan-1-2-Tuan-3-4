<?php
include('../layout/header.php');
include('../db_connect.php');

// Kiểm tra kết nối CSDL
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Cài đặt biến phân trang
$limit = 5; // Số người dùng tối đa hiển thị mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy trang hiện tại từ GET, mặc định là 1
$offset = ($page - 1) * $limit; // Tính toán offset

// Cập nhật truy vấn để lấy dữ liệu người dùng với phân trang
$query = "SELECT * FROM mor_user LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($query);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// Lấy tổng số người dùng
$totalQuery = "SELECT COUNT(*) FROM mor_user";
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->execute();
$totalUsers = $totalStmt->fetchColumn(); // Tổng số người dùng
$totalPages = ceil($totalUsers / $limit); // Tổng số trang
?>

<body>
    <h1 class="title_h1">Users</h1>
    <form id="assignRoleForm" method="post" action="../handle_Roleuser/assign.php">
        <label for="userSelect">Chọn người dùng:</label>
        <select id="userSelect" name="user" required>
            <?php
            // Lấy danh sách người dùng từ CSDL
            $userQuery = "SELECT * FROM mor_user";
            $userStmt = $conn->prepare($userQuery);
            $userStmt->execute();
            $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

            // Hiển thị các người dùng dưới dạng option
            foreach ($users as $user) {
                echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars($user['name']) . '</option>';
            }
            ?>
        </select>

        <label for="roleSelect">Chọn vai trò:</label>
        <select id="roleSelect" name="role" required>
            <?php
            // Lấy danh sách vai trò từ CSDL
            $roleQuery = "SELECT * FROM mor_role";
            $roleStmt = $conn->prepare($roleQuery);
            $roleStmt->execute();
            $roles = $roleStmt->fetchAll(PDO::FETCH_ASSOC);

            // Hiển thị các vai trò dưới dạng option
            foreach ($roles as $role) {
                echo '<option value="' . htmlspecialchars($role['id']) . '">' . htmlspecialchars($role['name']) . '</option>';
            }
            ?>
        </select>

        <button type="submit">Gán vai trò</button>
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
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td>
                        <button onclick="showPopup('update', <?= $row['id'] ?>, '<?= addslashes(htmlspecialchars($row['name'])) ?>', '<?= addslashes(htmlspecialchars($row['description'])) ?>')">Update</button>
                    </td>
                    <td>
                        <button onclick="deleteUser(<?= $row['id'] ?>, '<?= addslashes(htmlspecialchars($row['name'])) ?>', '<?= addslashes(htmlspecialchars($row['description'])) ?>')">Delete</button>
                    </td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                </tr>
            <?php endwhile; ?>
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

    <!-- Overlay và Popup -->
    <div class="overlay" id="overlay" onclick="closePopup()"></div>

    <!-- Popup form -->
    <div class="popup" id="popupForm">
        <h2 id="popupTitle">User - Edit</h2>
        <div class="box_popup">
            <input type="hidden" id="userId">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" placeholder="Enter Text" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" placeholder="Enter Text" required>
            </div>
        </div>
        <button onclick="submitForm()">Submit</button>
    </div>

    <!-- Popup thông báo -->
    <div class="notification-popup" id="notificationPopup" style="display:none;">
        <h2 id="notificationTitle"></h2>
        <p><strong>Result:</strong></p>
        <p class="name_p">Name: <span id="notificationName"></span></p>
        <p>Description: <span id="notificationDescription"></span></p>
    </div>

    <!-- Popup xác nhận -->
    <div class="notification-popup" id="confirmPopup" style="display:none;">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this user?</p>
        <p><strong>Name:</strong> <span id="confirmName"></span></p>
        <p><strong>Description:</strong> <span id="confirmDescription"></span></p>
        <button id="confirmYes" onclick="confirmDelete()">Yes</button>
        <button onclick="closeConfirmPopup()">No</button>
    </div>

    <script>
        let userToDelete = { id: null, name: '', description: '' };

        // Hiển thị popup với thông tin cụ thể cho thêm mới hoặc cập nhật
        function showPopup(action, id = null, name = '', description = '') {
            const popupForm = document.getElementById("popupForm");
            const overlay = document.getElementById("overlay");

            popupForm.style.display = "block";
            overlay.style.display = "block";

            if (action === 'update') {
                document.getElementById("popupTitle").innerText = "User - Update";
                document.getElementById("userId").value = id;
                document.getElementById("name").value = name;
                document.getElementById("description").value = description;
                document.querySelector("#popupForm button").innerText = "Update";
            } else {
                document.getElementById("popupTitle").innerText = "User - Add new";
                document.getElementById("userId").value = '';
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
            var id = document.getElementById("userId").value;
            var name = document.getElementById("name").value;
            var description = document.getElementById("description").value;

            var xhr = new XMLHttpRequest();
            var url = id ? '../handle_user/update.php' : '../handle_user/add.php'; // Chỉnh sửa đường dẫn

            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (this.status == 200) {
                    showNotification("Success", name, description);
                    setTimeout(function() {
                        window.location.reload(); // Tải lại trang
                    }, 3000); // Thời gian trước khi tải lại trang
                } else {
                    alert("Error occurred.");
                }
            };

            xhr.send("id=" + id + "&name=" + encodeURIComponent(name) + "&description=" + encodeURIComponent(description));
            closePopup();
        }

        // Xác nhận xóa người dùng
        function deleteUser(id, name, description) {
            userToDelete.id = id;
            userToDelete.name = name;
            userToDelete.description = description;

            document.getElementById("confirmName").innerText = name;
            document.getElementById("confirmDescription").innerText = description;
            document.getElementById("confirmPopup").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }

        // Xác nhận xóa
        function confirmDelete() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", '../handle_user/delete.php', true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (this.status == 200) {
                    showNotification("Deleted", userToDelete.name, userToDelete.description);
                    setTimeout(function() {
                        window.location.reload(); // Tải lại trang
                    }, 3000); // Thời gian trước khi tải lại trang
                } else {
                    alert("Error occurred.");
                }
            };

            xhr.send("id=" + userToDelete.id);
            closeConfirmPopup();
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
