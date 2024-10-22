import csv

# Đọc danh sách nhân viên và rate từ file employee_rate.txt
def read_employee_rates(file_name):
    employees = {}
    with open(file_name, mode='r') as file:
        reader = csv.reader(file)
        for row in reader:
            emp_id, name, rate = int(row[0]), row[1].strip(), float(row[2])  # Thêm strip() để loại bỏ khoảng trắng
            employees[emp_id] = {'name': name, 'rate': rate}
    return employees

# Đọc mức lương theo từng rate từ file salary.txt
def read_salary_rates(file_name):
    salaries = {}
    with open(file_name, mode='r') as file:
        reader = csv.reader(file)
        for row in reader:
            rate, vnd_salary, usd_salary = int(row[0]), int(row[1]), int(row[2])
            salaries[rate] = {'VND': vnd_salary, 'USD': usd_salary}
    return salaries

# Hàm tính lương
def calculate_salary(salary, days_off):
    return salary * (30 - days_off) / 30

# Chọn loại tiền tệ (VND hoặc USD)
def choose_currency():
    currency = input("Chọn loại tiền tệ cần tính (VND/USD): ").upper()
    if currency not in ['VND', 'USD']:
        print("Loại tiền không hợp lệ! Mặc định là VND.")
        currency = 'VND'
    return currency

# Chọn nhân viên cần tính lương
def select_employees(employees):
    print("\nDanh sách nhân viên:")
    for emp_id, info in employees.items():
        print(f"{emp_id}. {info['name']}")

    selected_ids = input("\nNhập mã nhân viên (cách nhau bởi dấu phẩy, vd: 1,2,3): ")
    selected_ids = [int(id.strip()) for id in selected_ids.split(',') if id.strip().isdigit()]
    return selected_ids

# Nhập số ngày nghỉ cho từng nhân viên
def enter_days_off(selected_ids):
    days_off = {}
    for emp_id in selected_ids:
        days = input(f"Nhập số ngày nghỉ cho nhân viên có mã {emp_id}: ")
        days_off[emp_id] = int(days) if days.isdigit() else 0
    return days_off

# Hiển thị kết quả tính lương
def display_salaries(employees, salaries, currency, days_off):
    print("\nKết quả tính lương:")
    for emp_id, info in employees.items():
        if emp_id in days_off:
            rate = int(info['rate'])  # Chuyển rate về int nếu là float
            
            # Kiểm tra xem rate có tồn tại trong salaries hay không
            if rate in salaries:
                base_salary = salaries[rate][currency]
                final_salary = calculate_salary(base_salary, days_off[emp_id])
                print(f"Nhân viên {info['name']}: Lương = {final_salary:.2f} {currency}")
            else:
                print(f"Không tìm thấy mức lương cho nhân viên {info['name']} với rate {rate}.")
                # Có thể tính lương trung bình hoặc một lương mặc định nếu không tìm thấy
                default_salary = 1500000 if currency == 'VND' else 150  # Ví dụ lương mặc định
                final_salary = calculate_salary(default_salary, days_off[emp_id])
                print(f"Giả định lương mặc định cho {info['name']}: Lương = {final_salary:.2f} {currency}")

# Hàm chính để chạy hệ thống tính lương
def main():
    # Đọc dữ liệu từ file
    employees = read_employee_rates("C:/Users/manht/OneDrive/Desktop/Thuc Tap/tuan1-bai2/employee_rate.txt")
    salaries = read_salary_rates("C:/Users/manht/OneDrive/Desktop/Thuc Tap/tuan1-bai2/salary.txt")

    # Bước 1: Chọn loại tiền tệ
    currency = choose_currency()

    # Bước 2: Chọn các nhân viên cần tính
    selected_ids = select_employees(employees)

    # Bước 3: Nhập số ngày nghỉ của từng nhân viên
    days_off = enter_days_off(selected_ids)

    # Bước 4: Tính lương và hiển thị kết quả
    display_salaries(employees, salaries, currency, days_off)

if __name__ == "__main__":
    main()
