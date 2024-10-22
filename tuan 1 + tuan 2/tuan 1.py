# Định nghĩa danh sách các xâu cần phân tích
strings = [
    "Both sides in the argument over whether a Dubai-based firm should be allowed to manage U.S. ports today used national security as a reason for why they are right",
    "The deputy defense secretary said blocking the deal could ostracize one of the United States' few Arab allies"
]

def analyze_string(s):
    # Danh sách các nguyên âm tiếng Anh
    vowels = 'aeiouAEIOU'
    
    # Tính số từ (chia xâu thành các từ dựa trên khoảng trắng)
    word_count = len(s.split())
    
    # Tính số ký tự (bao gồm cả khoảng trắng và dấu câu)
    char_count = len(s)
    
    # Tính số nguyên âm và danh sách các nguyên âm xuất hiện
    vowel_list = [char for char in s if char in vowels]
    vowel_count = len(vowel_list)
    
    # Loại bỏ các nguyên âm trùng lặp trong danh sách và giữ lại thứ tự xuất hiện
    unique_vowels = list(dict.fromkeys(vowel_list))
    
    return word_count, char_count, vowel_count, unique_vowels

# In bảng tiêu đề
print(f"{'STT':<5} {'Số từ':<10} {'Số ký tự':<10} {'Số nguyên âm':<15} {'Danh sách các nguyên âm':<30}")

# Duyệt qua từng xâu và hiển thị thông tin phân tích
for idx, string in enumerate(strings, start=1):
    word_count, char_count, vowel_count, unique_vowels = analyze_string(string)
    
    # In kết quả dạng bảng
    print(f"{idx:<5} {word_count:<10} {char_count:<10} {vowel_count:<15} {', '.join(unique_vowels):<30}")
