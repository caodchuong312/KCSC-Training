
`Task 2:
Tìm hiểu về SQLi (SQLi là gì, hoạt động như thế nào, các loại tấn công phổ biến, cách khai thác, cách ngăn chặn) 
=>Thực hành: Write up chi tiết các bài sau của webhacking.kr: old-45, old-49 (nếu xong sớm có thể làm thêm 27,35,39,51,61 không bắt buộc); root-me: SQL injection blind`

# Tìm hiểu về SQLi 
#### SQLi 
Là tấn công mà attacker can thiệp vào cơ sở dữ liệu thông qua các câu truy vấn để truy xuất, sửa đổi, xóa dữ liệu ở database hoặc gây ra ảnh hưởng khác tới nội dung hay thao tác người dùng...
### SQLi hoạt động: 
- Đầu tiên attacker tìm kiếm các điểm yếu trên web như input, parameter...
- Sau đó attacker sẽ thử các câu lệnh SQL cùng với các ký tự khác để xác định cách truy cập vào database
- Cuối cùng là dùng các câu lệnh SQL để tấn công
### Các loại tấn công phổ biến:
- In-Band SQLi: Các phản hồi nhận được kết quả truy vấn SQL. Được chia thành 2 loại:
  * Union-based SQLi: Sử dụng câu lệnh UNION trong các truy vấn SQL để truy cập vào db.
  * Error-based SQLi: Dựa vào lỗi trong câu lệnh SQL để xác định cấu trúc db từ đó tìm cách truy cập vào db.
- Blind SQLi: Các phản hồi không chứa kết quả truy vấn SQL. Được chia thành 2 loại:
  * Boolean: Câu truy vấn trả về chỉ cho biết đúng hoặc sai từ đó attacker điều chỉnh câu truy vấn để khai thác.
  * Time-based: Attacker sẽ sử dụng câu truy vấn làm db trả về kết trong thời gian khác nhau tùy thuộc tính đúng sai từ đó điều chỉnh câu truy vấn để khai thác.
- Out-of-Band SQLi: Tấn công sử dụng một cấu trúc truy vấn SQL để yêu cầu sever trả về kết quả thông qua các kênh liên quan đến mạng.
### Cách khai thác
**1. Detect**

 - Gửi dấu nháy đơn ' , nháy kép ", chấm phẩy ; ký tự comment như --, #,... và chờ kết quả phản hồi của web
 - Gửi các điều kiện boolean như OR 1=1, OR 1=2 ... và xem phản hồi
 - Gửi payload thử thời gian trả về như SLEEP(5), pg_sleep(10), DELAY '0:0:10' ...
 
 **2. Xác định thông tin database**
 - Xác định bằng cách sử dụng truy vấn như SELECT @@version (MySQL), SELECT * FROM v$version (Oracle)
 - Liệt kệ nội dung 
 
  Ví dụ với SQL Server:

  * Liệt kê tên các bảng: `SELECT * FROM information_schema.tables`.
  * Liệt kê cột từ tên bảng vừa tìm đc : `SELECT * FROM information_schema.columns WHERE table_name = 'table_name'`.
  * Từ đó truy xuất ra nội dung.
  
**3. Khai thác:**

Phá bỏ tính logic của ứng dụng:

Ví dụ: khi đăng nhập với username abcdef và passwd 12345678 thì ứng dụng kiểm tra qua câu truy vấn : 

`SELECT * FROM users WHERE username = 'abcdef' AND password = '12345678'`

Khi đó attacker có thể đăng nhập vào admin mà không cần passwd bằng sử dụng comment -- trong SQL với payload: `admin'--` khi đó câu truy vấn sẽ là :

`SELECT * FROM users WHERE username = 'admin'--' AND password = ''`

Hoặc có thể dùng điều kiện boolean với payload : `admin' OR 1=1 --` 

- Sử dụng UNION
Trả về kết quả của các câu lệnh SELECT từ bảng khác với điều kiện trả về cùng số cột và kiểu dữ liệu của cột.

* Xác định số cột cần để tấn công: 
Sử dụng ORDER BY ví dụ `' ORDER BY x--` với x là số cột cần thử và từ phản hồi tìm được số cột

Sử dụng `' UNION SELECT NULL,NULL--`

* Sau khi tìm ra số cột thì kiểm tra kiểu dữ liệu trong cột

Giả sử ở đây là 4 cột thì 

Sử dụng `' UNION SELECT 'a',NULL,NULL,NULL--`. Nếu không có lỗi trả về thì xác đinh cột đầu tiên có kiểu dữ liệu là String từ đó tìm kiếm cột khác

* Từ đó tìm tên cột vvà truy xuất dữ liệu

- Với Blind SQLi:

Nhập dữ liệu với giá trị boolean






- Tấn công qua input:
 * Dữ liệu nhập vào được đưa browser gửi tới server qua GET hoặc POST method trở thành tham số truy cập tới dữ liệu

