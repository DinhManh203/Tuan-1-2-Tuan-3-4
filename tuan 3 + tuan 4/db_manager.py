import mysql.connector
from tkinter import messagebox, simpledialog, Tk, Button, Listbox, Scrollbar, Toplevel

# Kết nối tới cơ sở dữ liệu MySQL
def connect_to_db():
    connection = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="dm@04102003",
        database="permission_system"
    )
    return connection

# =================== Quản lý Người Dùng (mor_user) ===================
def add_user(name, description):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "INSERT INTO mor_user (name, description) VALUES (%s, %s)"
    cursor.execute(query, (name, description))
    conn.commit()
    messagebox.showinfo("Thành công", f"User '{name}' added successfully.")
    cursor.close()
    conn.close()

def update_user(user_id, name, description):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "UPDATE mor_user SET name=%s, description=%s WHERE id=%s"
    cursor.execute(query, (name, description, user_id))
    conn.commit()
    messagebox.showinfo("Thành công", f"User '{name}' updated successfully.")
    cursor.close()
    conn.close()

def delete_user(user_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "DELETE FROM mor_user WHERE id=%s"
    cursor.execute(query, (user_id,))
    conn.commit()
    messagebox.showinfo("Thành công", f"User '{user_id}' deleted successfully.")
    cursor.close()
    conn.close()

def get_all_users():
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "SELECT * FROM mor_user"
    cursor.execute(query)
    users = cursor.fetchall()
    cursor.close()
    conn.close()
    return users

# =================== Giao diện Quản Lý Người Dùng ===================
def user_management_ui():
    user_window = Toplevel()  # Sử dụng Toplevel để mở cửa sổ con
    user_window.title("Quản Lý Người Dùng")

    def add_user_ui():
        name = simpledialog.askstring("Nhập tên người dùng", "Tên người dùng:")
        description = simpledialog.askstring("Nhập mô tả", "Mô tả:")
        if name and description:
            add_user(name, description)
            update_user_list()

    def update_user_ui():
        selected_user = user_listbox.curselection()
        if not selected_user:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn người dùng để sửa.")
            return
        user_id = user_list[selected_user[0]][0]
        name = simpledialog.askstring("Nhập tên mới", "Tên người dùng mới:")
        description = simpledialog.askstring("Nhập mô tả mới", "Mô tả mới:")
        if name and description:
            update_user(user_id, name, description)
            update_user_list()

    def delete_user_ui():
        selected_user = user_listbox.curselection()
        if not selected_user:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn người dùng để xóa.")
            return
        user_id = user_list[selected_user[0]][0]
        delete_user(user_id)
        update_user_list()

    def update_user_list():
        user_listbox.delete(0, 'end')
        global user_list
        user_list = get_all_users()
        for user in user_list:
            user_listbox.insert('end', f"ID: {user[0]}, Tên: {user[1]}, Mô tả: {user[2]}")

    user_listbox = Listbox(user_window, width=50)
    user_listbox.pack(pady=10)

    scrollbar = Scrollbar(user_window)
    scrollbar.pack(side='right', fill='y')
    user_listbox.config(yscrollcommand=scrollbar.set)
    scrollbar.config(command=user_listbox.yview)

    Button(user_window, text="Thêm Người Dùng", command=add_user_ui).pack(pady=5)
    Button(user_window, text="Sửa Người Dùng", command=update_user_ui).pack(pady=5)
    Button(user_window, text="Xóa Người Dùng", command=delete_user_ui).pack(pady=5)

    update_user_list()
    
    return user_window  # Trả về cửa sổ người dùng

# =================== Quản lý Vai Trò (mor_role) ===================
def add_role(name, description):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "INSERT INTO mor_role (name, description) VALUES (%s, %s)"
    cursor.execute(query, (name, description))
    conn.commit()
    messagebox.showinfo("Thành công", f"Role '{name}' added successfully.")
    cursor.close()
    conn.close()

def update_role(role_id, name, description):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "UPDATE mor_role SET name=%s, description=%s WHERE id=%s"
    cursor.execute(query, (name, description, role_id))
    conn.commit()
    messagebox.showinfo("Thành công", f"Role '{name}' updated successfully.")
    cursor.close()
    conn.close()

def delete_role(role_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "DELETE FROM mor_role WHERE id=%s"
    cursor.execute(query, (role_id,))
    conn.commit()
    messagebox.showinfo("Thành công", f"Role '{role_id}' deleted successfully.")
    cursor.close()
    conn.close()

def get_all_roles():
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "SELECT * FROM mor_role"
    cursor.execute(query)
    roles = cursor.fetchall()
    cursor.close()
    conn.close()
    return roles

# =================== Giao diện Quản Lý Vai Trò ===================
def role_management_ui():
    window = Toplevel()  # Sử dụng Toplevel để mở cửa sổ con
    window.title("Quản Lý Vai Trò")

    def add_role_ui():
        name = simpledialog.askstring("Nhập tên vai trò", "Tên vai trò:")
        description = simpledialog.askstring("Nhập mô tả", "Mô tả:")
        if name and description:
            add_role(name, description)
            update_role_list()  # Cập nhật danh sách vai trò sau khi thêm

    def update_role_ui():
        selected_role = role_listbox.curselection()
        if not selected_role:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn vai trò để sửa.")
            return
        role_id = role_list[selected_role[0]][0]  # Lấy ID vai trò
        name = simpledialog.askstring("Nhập tên mới", "Tên vai trò mới:")
        description = simpledialog.askstring("Nhập mô tả mới", "Mô tả mới:")
        if name and description:
            update_role(role_id, name, description)
            update_role_list()  # Cập nhật danh sách vai trò sau khi sửa

    def delete_role_ui():
        selected_role = role_listbox.curselection()
        if not selected_role:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn vai trò để xóa.")
            return
        role_id = role_list[selected_role[0]][0]  # Lấy ID vai trò
        delete_role(role_id)  # Xóa vai trò
        update_role_list()  # Cập nhật danh sách vai trò sau khi xóa

    def update_role_list():
        role_listbox.delete(0, 'end')  # Xóa danh sách hiện tại
        global role_list
        role_list = get_all_roles()  # Lấy danh sách vai trò
        for role in role_list:
            role_listbox.insert('end', f"ID: {role[0]}, Tên: {role[1]}, Mô tả: {role[2]}")  # Thêm vào danh sách

    # Tạo danh sách hiển thị vai trò
    role_listbox = Listbox(window, width=50)
    role_listbox.pack(pady=10)

    scrollbar = Scrollbar(window)
    scrollbar.pack(side='right', fill='y')
    role_listbox.config(yscrollcommand=scrollbar.set)
    scrollbar.config(command=role_listbox.yview)

    Button(window, text="Thêm Vai Trò", command=add_role_ui).pack(pady=5)
    Button(window, text="Sửa Vai Trò", command=update_role_ui).pack(pady=5)
    Button(window, text="Xóa Vai Trò", command=delete_role_ui).pack(pady=5)

    update_role_list()  # Cập nhật danh sách vai trò khi khởi động

    return window  # Trả về cửa sổ vai trò

# =================== Quản lý Quyền (mor_permission) ===================
def add_permission(name, description):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "INSERT INTO mor_permission (name, description) VALUES (%s, %s)"
    cursor.execute(query, (name, description))
    conn.commit()
    messagebox.showinfo("Thành công", f"Permission '{name}' added successfully.")
    cursor.close()
    conn.close()

def update_permission(permission_id, name, description):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "UPDATE mor_permission SET name=%s, description=%s WHERE id=%s"
    cursor.execute(query, (name, description, permission_id))
    conn.commit()
    messagebox.showinfo("Thành công", f"Permission '{name}' updated successfully.")
    cursor.close()
    conn.close()

def delete_permission(permission_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "DELETE FROM mor_permission WHERE id=%s"
    cursor.execute(query, (permission_id,))
    conn.commit()
    messagebox.showinfo("Thành công", f"Permission '{permission_id}' deleted successfully.")
    cursor.close()
    conn.close()

def get_all_permissions():
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "SELECT * FROM mor_permission"
    cursor.execute(query)
    permissions = cursor.fetchall()
    cursor.close()
    conn.close()
    return permissions

# =================== Giao diện Quản Lý Quyền ===================
def permission_management_ui():
    window = Toplevel()  # Sử dụng Toplevel để mở cửa sổ con
    window.title("Quản Lý Quyền")

    def add_permission_ui():
        name = simpledialog.askstring("Nhập tên quyền", "Tên quyền:")
        description = simpledialog.askstring("Nhập mô tả", "Mô tả:")
        if name and description:
            add_permission(name, description)
            update_permission_list()  # Cập nhật danh sách quyền sau khi thêm

    def update_permission_ui():
        selected_permission = permission_listbox.curselection()
        if not selected_permission:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn quyền để sửa.")
            return
        permission_id = permission_list[selected_permission[0]][0]  # Lấy ID quyền
        name = simpledialog.askstring("Nhập tên mới", "Tên quyền mới:")
        description = simpledialog.askstring("Nhập mô tả mới", "Mô tả mới:")
        if name and description:
            update_permission(permission_id, name, description)
            update_permission_list()  # Cập nhật danh sách quyền sau khi sửa

    def delete_permission_ui():
        selected_permission = permission_listbox.curselection()
        if not selected_permission:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn quyền để xóa.")
            return
        permission_id = permission_list[selected_permission[0]][0]  # Lấy ID quyền
        delete_permission(permission_id)  # Xóa quyền
        update_permission_list()  # Cập nhật danh sách quyền sau khi xóa

    def update_permission_list():
        permission_listbox.delete(0, 'end')  # Xóa danh sách hiện tại
        global permission_list
        permission_list = get_all_permissions()  # Lấy danh sách quyền
        for permission in permission_list:
            permission_listbox.insert('end', f"ID: {permission[0]}, Tên: {permission[1]}, Mô tả: {permission[2]}")  # Thêm vào danh sách

    # Tạo danh sách hiển thị quyền
    permission_listbox = Listbox(window, width=50)
    permission_listbox.pack(pady=10)

    scrollbar = Scrollbar(window)
    scrollbar.pack(side='right', fill='y')
    permission_listbox.config(yscrollcommand=scrollbar.set)
    scrollbar.config(command=permission_listbox.yview)

    Button(window, text="Thêm Quyền", command=add_permission_ui).pack(pady=5)
    Button(window, text="Sửa Quyền", command=update_permission_ui).pack(pady=5)
    Button(window, text="Xóa Quyền", command=delete_permission_ui).pack(pady=5)

    update_permission_list()  # Cập nhật danh sách quyền khi khởi động

    return window  # Trả về cửa sổ quyền

# =================== Quản lý Quyền cho Vai Trò (mor_role_permission) ===================
def add_role_permission(role_id, permission_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "INSERT INTO mor_role_permission (role_id, permission_id) VALUES (%s, %s)"
    cursor.execute(query, (role_id, permission_id))
    conn.commit()
    messagebox.showinfo("Thành công", f"Permission ID '{permission_id}' added to Role ID '{role_id}' successfully.")
    cursor.close()
    conn.close()

def delete_role_permission(role_id, permission_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "DELETE FROM mor_role_permission WHERE role_id=%s AND permission_id=%s"
    cursor.execute(query, (role_id, permission_id))
    conn.commit()
    messagebox.showinfo("Thành công", f"Permission ID '{permission_id}' removed from Role ID '{role_id}' successfully.")
    cursor.close()
    conn.close()

def get_role_permissions(role_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "SELECT permission_id FROM mor_role_permission WHERE role_id=%s"
    cursor.execute(query, (role_id,))
    permissions = cursor.fetchall()
    cursor.close()
    conn.close()
    return permissions

# =================== Giao diện Quản Lý Quyền cho Vai Trò ===================
def role_permission_management_ui():
    window = Toplevel()
    window.title("Quản Lý Quyền cho Vai Trò")

    def add_permission_to_role_ui():
        selected_role = role_listbox.curselection()
        if not selected_role:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn vai trò.")
            return
        role_id = role_list[selected_role[0]][0]
        permission_id = simpledialog.askinteger("Nhập ID Quyền", "ID Quyền:")
        if permission_id:
            add_role_permission(role_id, permission_id)
            update_role_permissions_list(role_id)

    def delete_permission_from_role_ui():
        selected_permission = permission_listbox.curselection()
        selected_role = role_listbox.curselection()
        if not selected_permission or not selected_role:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn vai trò và quyền để xóa.")
            return
        role_id = role_list[selected_role[0]][0]
        permission_id = permission_list[selected_permission[0]][0]
        delete_role_permission(role_id, permission_id)
        update_role_permissions_list(role_id)

    def update_role_permissions_list(role_id):
        permission_listbox.delete(0, 'end')
        global permission_list
        permission_list = get_role_permissions(role_id)
        for permission in permission_list:
            permission_listbox.insert('end', f"Permission ID: {permission[0]}")  # Chỉ hiển thị Permission ID

    # Tạo danh sách hiển thị vai trò
    role_listbox = Listbox(window, width=50)
    role_listbox.pack(pady=10)
    
    # Giả sử đã có danh sách vai trò
    global role_list
    role_list = get_all_roles()  # Cần đảm bảo danh sách vai trò đã có
    for role in role_list:
        role_listbox.insert('end', f"ID: {role[0]}, Tên: {role[1]}, Mô tả: {role[2]}")  # Thêm vào danh sách

    permission_listbox = Listbox(window, width=50)
    permission_listbox.pack(pady=10)

    Button(window, text="Thêm Quyền cho Vai Trò", command=add_permission_to_role_ui).pack(pady=5)
    Button(window, text="Xóa Quyền khỏi Vai Trò", command=delete_permission_from_role_ui).pack(pady=5)

# =================== Quản lý Danh Sách Kiểm Soát Truy Cập (mor_acl) ===================
def add_acl(user_id, role_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "INSERT INTO mor_acl (user_id, role_id) VALUES (%s, %s)"
    cursor.execute(query, (user_id, role_id))
    conn.commit()
    messagebox.showinfo("Thành công", f"User ID '{user_id}' assigned Role ID '{role_id}' successfully.")
    cursor.close()
    conn.close()

def delete_acl(user_id, role_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "DELETE FROM mor_acl WHERE user_id=%s AND role_id=%s"
    cursor.execute(query, (user_id, role_id))
    conn.commit()
    messagebox.showinfo("Thành công", f"Role ID '{role_id}' removed from User ID '{user_id}' successfully.")
    cursor.close()
    conn.close()

def get_user_roles(user_id):
    conn = connect_to_db()
    cursor = conn.cursor()
    query = "SELECT role_id FROM mor_acl WHERE user_id=%s"
    cursor.execute(query, (user_id,))
    roles = cursor.fetchall()
    cursor.close()
    conn.close()
    return roles

# =================== Giao diện Quản Lý Danh Sách Kiểm Soát Truy Cập ===================
def acl_management_ui():
    window = Toplevel()
    window.title("Quản Lý Danh Sách Kiểm Soát Truy Cập")

    def add_user_role_ui():
        selected_user = user_listbox.curselection()
        if not selected_user:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn người dùng.")
            return
        user_id = user_list[selected_user[0]][0]
        role_id = simpledialog.askinteger("Nhập ID Vai Trò", "ID Vai Trò:")
        if role_id:
            add_acl(user_id, role_id)
            update_user_roles_list(user_id)

    def delete_user_role_ui():
        selected_role = user_roles_listbox.curselection()
        selected_user = user_listbox.curselection()
        if not selected_role or not selected_user:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn người dùng và vai trò để xóa.")
            return
        user_id = user_list[selected_user[0]][0]
        role_id = user_roles_list[selected_role[0]][0]  # Sửa lại chỉ số cho chính xác
        delete_acl(user_id, role_id)
        update_user_roles_list(user_id)

    def update_user_roles_list(user_id):
        user_roles_listbox.delete(0, 'end')  # Xóa danh sách hiện tại
        global user_roles_list
        user_roles_list = get_user_roles(user_id)  # Lấy danh sách vai trò của người dùng
        for role in user_roles_list:
            user_roles_listbox.insert('end', f"Role ID: {role[0]}")  # Chỉ hiển thị Role ID

    # Tạo danh sách hiển thị người dùng
    user_listbox = Listbox(window, width=50)
    user_listbox.pack(pady=10)

    # Giả sử đã có danh sách người dùng
    global user_list
    user_list = get_all_users()  # Cần đảm bảo danh sách người dùng đã có
    for user in user_list:
        user_listbox.insert('end', f"ID: {user[0]}, Tên: {user[1]}, Mô tả: {user[2]}")  # Thêm vào danh sách

    # Tạo danh sách hiển thị vai trò đã gán
    user_roles_listbox = Listbox(window, width=50)
    user_roles_listbox.pack(pady=10)

    Button(window, text="Thêm Vai Trò cho Người Dùng", command=add_user_role_ui).pack(pady=5)
    Button(window, text="Xóa Vai Trò khỏi Người Dùng", command=delete_user_role_ui).pack(pady=5)

    # Thêm sự kiện để cập nhật vai trò khi chọn người dùng
    user_listbox.bind('<<ListboxSelect>>', lambda event: update_user_roles_list(user_list[user_listbox.curselection()[0]][0]) if user_listbox.curselection() else None)

# =================== Giao diện Quản Lý Quyền cho Vai Trò ===================
def role_permission_management_ui():
    window = Toplevel()
    window.title("Quản Lý Quyền cho Vai Trò")

    # Tạo danh sách hiển thị vai trò
    role_listbox = Listbox(window, width=50)
    role_listbox.pack(pady=10)

    # Tạo danh sách hiển thị quyền của vai trò
    permission_listbox = Listbox(window, width=50)
    permission_listbox.pack(pady=10)

    def update_role_list():
        role_listbox.delete(0, 'end')
        global role_list
        role_list = get_all_roles()
        for role in role_list:
            role_listbox.insert('end', f"ID: {role[0]}, Tên: {role[1]}, Mô tả: {role[2]}")

    def update_role_permissions_list(role_id):
        permission_listbox.delete(0, 'end')
        global permission_list  # Khai báo biến toàn cục để sử dụng sau
        permission_list = get_role_permissions(role_id)  # Lấy quyền của vai trò
        for permission in permission_list:
            permission_listbox.insert('end', f"Permission ID: {permission[0]}")  # Hiển thị ID quyền

    def add_permission_to_role_ui():
        selected_role = role_listbox.curselection()
        if not selected_role:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn vai trò.")
            return
        role_id = role_list[selected_role[0]][0]
        permission_id = simpledialog.askinteger("Nhập ID Quyền", "ID Quyền:")
        if permission_id:
            add_role_permission(role_id, permission_id)
            update_role_permissions_list(role_id)  # Cập nhật danh sách quyền

    def delete_permission_from_role_ui():
        selected_permission = permission_listbox.curselection()
        selected_role = role_listbox.curselection()
        if not selected_permission or not selected_role:
            messagebox.showwarning("Cảnh báo", "Vui lòng chọn vai trò và quyền để xóa.")
            return
        role_id = role_list[selected_role[0]][0]
        permission_id = permission_list[selected_permission[0]][0]  # Lấy ID quyền
        delete_role_permission(role_id, permission_id)
        update_role_permissions_list(role_id)  # Cập nhật danh sách quyền

    # Tạo các nút cho các chức năng
    Button(window, text="Thêm Quyền cho Vai Trò", command=add_permission_to_role_ui).pack(pady=5)
    Button(window, text="Xóa Quyền khỏi Vai Trò", command=delete_permission_from_role_ui).pack(pady=5)

    # Thêm sự kiện để cập nhật quyền khi chọn vai trò
    role_listbox.bind('<<ListboxSelect>>', lambda event: update_role_permissions_list(role_list[role_listbox.curselection()[0]][0]) if role_listbox.curselection() else None)

    update_role_list()  # Cập nhật danh sách vai trò khi khởi động

# =================== Giao diện Chính ===================
def main_ui():
    root = Tk()
    root.title("Hệ Thống Quản Lý Quyền")
    
    Button(root, text="Quản Lý Người Dùng", command=user_management_ui).pack(pady=5)
    Button(root, text="Quản Lý Vai Trò", command=role_management_ui).pack(pady=5)
    Button(root, text="Quản Lý Quyền", command=permission_management_ui).pack(pady=5)
    Button(root, text="Quản Lý Quyền cho Vai Trò", command=role_permission_management_ui).pack(pady=5)
    Button(root, text="Quản Lý Danh Sách Kiểm Soát Truy Cập", command=acl_management_ui).pack(pady=5)

    root.mainloop()

if __name__ == "__main__":
    main_ui()