`Task 7:` 
`Tìm hiểu về LFI, RFI (Khái niệm,nguyên nhân, tác hại + 1 số cách bypass , cách phòng chống).`

`Xây dựng lab tấn công demo cả 2 loại.`

`Làm hết các bài liên quan trên rootme`
# LFI (Local File Inclusion)
### Khái niệm
Đây là Ký thuật mà kẻ tấn công dùng để truy cập và hiện thị vào các file trên hệ thống máy chủ. Nó xảy ra khi kẻ tấn công chèn đường dẫn local qua HTTP request tới máy chủ
### Nguyên nhân
Nguyên nhân chính là do máy chủ không kiểm tra và xác thực đầu vào từ phía người dùng như các đường link dạng `php?file=...` ... Khi web sử dụng cách hàm như `include`,`require`... mà không xác thực thì dẫn đến việc kẻ tấn công sẽ thay đổi giá trị của tham số để xem hoặc thực thi các tệp trên máy chủ web. Ngoài ra còn do việc không kiểm soát dẫn không được quyền truy cập file hay đường dẫn trên hệ thống.
### Tác hại
  - Kẻ tấn công sẽ truy cập và đọc được thông tin nhạy cảm như `/etc/passwd`, các cấu hình, các file nhạy cảm... hoặc có thể thực thi các file khác.
  - Dẫn đến các cuộc tấn công khác nhứ XSS, DOS, Command Injection,..
### Bypass
Khi tham số được người dùng kiểm soát để đưa vào xử lý, server có thể thêm 1 số filter để ứng dụng hoạt động theo ý muốn. Vì vậy cách bypass tùy theo từng trường hợp:
- Server thêm extension `.php` phía sau như `include($_GET["page"].".php");`. Khi đó nên sử dụng `null` byte như `%00` hoặc `0x00` dưới dạng hex. Ví dụ: `http://example.com/index.php?page=../../../etc/passwd%00`. Tuy nhiên `null byte` không được sử dụng ở version PHP mới. Thay vào đó có thể sử dụng `./`, nó chỉ thư mục hiện tại : `/etc/passwd/.` 
- Khi server filter 1 số ký tự thì có thể bypass bằng cách `Encoding` cũng như `Double Encoding`. Ví dụ: `http://example.com/index.php?page=..%252f..%252f..%252fetc%252fpasswd` hoặc có thể thay thế `/` bằng `\`
- Khi server có thể kiểm tra thư mục hiện tại và chỉ cho phép truy cập khi ở thư mục đó thì ta thêm thư mục đó phía trước payload ví dụ: `http://example.com/index.php?lang=languages/../../../../../etc/passwd`
- Xem cách server xử lý và sử dụng payload thích hợp. Ví dụ:
`http://example.com/index.php?page=....//....//etc/passwd` <br>
`http://example.com/index.php?page=/%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../etc/passwd`
...

# RFI (Remote Fle Iclusion)
### Khái niệm 
Đây là kỹ thuật mà kẻ tấn công có thể include 1 file hoặc chèn file mã độc từ một máy chủ khác vào trong trang web bằng cách sử dụng đường dẫn tuyệt đối đến các tệp tin từ xa. RFI thường được sử dụng như một phương pháp tấn công phụ để thực hiện các cuộc tấn công khác.
### Nguyên nhân
- Thiếu kiểm tra và xác thực đầu vào: kẻ tấn công từ đó có thể chèn mã độc
- Sử dụng các hàm như `include`, `require`và không xác thực đúng cách
### Tác hại
Tác của RFI cao hơn LFI vì các lỗ hổng RFI cho phép kẻ tấn công thực thi lệnh từ xa (RCE) trên máy chủ.<br>
- Truy cập và xem các file nhạy cảm.
- Thực thi mã độc: dẫn đến việc chiếm được quyền hệ thống.
- Thực thi các cuộc tấn công khác: Dos, SQL injection, XSS, ...
### Phòng chống
- Xác thực đầu vào an toàn như sử dụng whitelist...
- Giới hạn quyền truy cập vào hệ thống.
- Tắt các options `allow_url_fopen`, `allow_url_include` trong cấu hình server
- Cập nhật ứng dụng, sử dụng tường lửa...
### Bypass
- Sử dụng các protocols và wrappers: `phar://`, `php://`, `http://`, `ftp://`...
- 
