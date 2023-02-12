
`Task 2:
Tìm hiểu về SQLi (SQLi là gì, hoạt động như thế nào, các loại tấn công phổ biến, cách khai thác, cách ngăn chặn) 
=>Thực hành: Write up chi tiết các bài sau của webhacking.kr: old-45, old-49 (nếu xong sớm có thể làm thêm 27,35,39,51,61 không bắt buộc); root-me: SQL injection blind`

# Tìm hiểu về SQLi 
#### SQLi 
Là tấn công mà attacker can thiệp vào cơ sở dữ liệu thông qua các câu truy vấn để truy xuất, sửa đổi, xóa dữ liệu ở database hoặc gây ra ảnh hưởng khác tới nội dung hay thao tác người dùng...
#### SQLi hoạt động: 
- Đầu tiên attacker tìm kiếm các điểm yếu trên web như input, parameter...
- Sau đó attacker sẽ thử các câu lệnh SQL cùng với các ký tự khác để xác định cách truy cập vào database
- Cuối cùng là dùng các câu lệnh SQL để tấn công
#### Các loại tấn công phổ biến:
- In-Band SQLi: Các phản hồi nhận được kết quả truy vấn SQL. Được chia thành 2 loại:
* Union-based SQLi: Sử dụng câu lệnh UNION trong các truy vấn SQL để truy cập vào db.
* Error-based SQLi: Dựa vào lỗi trong câu lệnh SQL để xác định cấu trúc db từ đó tìm cách truy cập vào db.
- Blind SQLi: Các phản hồi không chứa kết quả truy vấn SQL. Được chia thành 2 loại:
  * Boolean: Câu truy vấn trả về chỉ cho biết đúng hoặc sai từ đó attacker điều chỉnh câu truy vấn để khai thác.
  * Time-based: Attacker sẽ sử dụng câu truy vấn làm db trả về kết trong thời gian khác nhau tùy thuộc tính đúng sai từ đó điều chỉnh câu truy vấn để khai thác.
- Out-of-Band SQLi: Tấn công sử dụng một cấu trúc truy vấn SQL để yêu cầu sever trả về kết quả thông qua các kênh liên quan đến mạng.

