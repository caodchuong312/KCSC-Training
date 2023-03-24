`Task 7:` 
`Tìm hiểu về LFI, RFI (Khái niệm,nguyên nhân, tác hại + 1 số cách bypass , cách phòng chống).`

`Xây dựng lab tấn công demo cả 2 loại.`

`Làm hết các bài liên quan trên rootme`<br>

## Khái niệm
- LFI (Local File Inclusion): Đây là Ký thuật mà kẻ tấn công dùng để truy cập và hiện thị vào các file trên hệ thống máy chủ. Nó xảy ra khi kẻ tấn công chèn đường dẫn local qua HTTP request tới máy chủ.
- RFI (Remote Fle Iclusion): Đây là kỹ thuật mà kẻ tấn công có thể include 1 file hoặc chèn file mã độc từ một máy chủ khác vào trong trang web bằng cách sử dụng đường dẫn tuyệt đối đến các tệp tin từ xa. RFI thường được sử dụng như một phương pháp tấn công phụ để thực hiện các cuộc tấn công khác.
## Nguyên nhân
Nguyên nhân chính là do máy chủ không kiểm tra và xác thực đầu vào từ phía người dùng như các đường link dạng `php?file=...` ... Khi web sử dụng cách hàm như `include`,`require`... mà không xác thực thì dẫn đến việc kẻ tấn công sẽ thay đổi giá trị của tham số để xem hoặc thực thi các tệp trên máy chủ web. Ngoài ra còn do việc không kiểm soát dẫn không được quyền truy cập file hay đường dẫn trên hệ thống hay do cấu hình server dễ bị tấn công như `allow_url_fopen`, `allow_url_include`
## Tác hại
Tác của RFI cao hơn LFI vì các lỗ hổng RFI cho phép kẻ tấn công thực thi lệnh từ xa (RCE) trên máy chủ.<br>
- Kẻ tấn công sẽ truy cập và đọc được thông tin nhạy cảm như `/etc/passwd`, các cấu hình, các file nhạy cảm... hoặc có thể thực thi các file khác.
- Thực thi mã độc: có thể dẫn đến việc chiếm được quyền hệ thống.
- Thực thi các cuộc tấn công khác: Dos, SQL injection, XSS, ...
### Bypass LFI,RFI
Khi tham số được người dùng kiểm soát để đưa vào xử lý, server có thể thêm 1 số filter để ứng dụng hoạt động theo ý muốn. Vì vậy cách bypass tùy theo từng trường hợp:
- Server thêm extension `.php` phía sau như `include($_GET["page"].".php");`. Khi đó nên sử dụng `null` byte như `%00` hoặc `0x00` dưới dạng hex. Ví dụ: `http://example.com/index.php?page=../../../etc/passwd%00`. Tuy nhiên `null byte` không được sử dụng ở version PHP mới. Thay vào đó có thể sử dụng `./`, nó chỉ thư mục hiện tại : `/etc/passwd/.` 
- Khi server filter 1 số ký tự thì có thể bypass bằng cách `Encoding` cũng như `Double Encoding`. Ví dụ: `http://example.com/index.php?page=..%252f..%252f..%252fetc%252fpasswd` hoặc có thể thay thế `/` bằng `\`
- Khi server có thể kiểm tra thư mục hiện tại và chỉ cho phép truy cập khi ở thư mục đó thì ta thêm thư mục đó phía trước payload ví dụ: `http://example.com/index.php?lang=languages/../../../../../etc/passwd`
- Xem cách server xử lý và sử dụng payload thích hợp. Ví dụ:
`http://example.com/index.php?page=....//....//etc/passwd` <br>
`http://example.com/index.php?page=/%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../etc/passwd`
- Sử dụng các protocols và wrappers: 
  - php://filter<br>String filters : string.rot13, string.toupper, string.tolower, string.strip_tags
  <br>Conversion Filters: convert.base64-encode, convert.base64-decode, convert.quoted-printable-encode, convert.quoted-printable-decode
  - php://
  - zip:// và rar://
  - phar:// 
  - ...
<br>Xem thêm <a href="https://book.hacktricks.xyz/pentesting-web/file-inclusion" >Hacktricks</a>
### Phòng chống
- Xác thực đầu vào an toàn như sử dụng whitelist...
- Giới hạn quyền truy cập vào hệ thống.
- Tắt các options `allow_url_fopen`, `allow_url_include` trong cấu hình server
- Cập nhật ứng dụng, sử dụng tường lửa...
### So sánh LFI và Path traversal
- LFI là kỹ thuật tấn công khi kẻ tấn công sử dụng lỗ hổng trong ứng dụng web để đọc các file cục bộ trên máy chủ web, thường là các files cấu hình hay source của ứng dụng web đó. Khi kẻ tấn công có thể đọc được các file này, họ có thể tìm ra các lỗ hổng tiềm năng khác hoặc sử dụng thông tin này để tiến hành các cuộc tấn công khác
- Path Traversal là kỹ thuật tấn công khi kẻ tấn công sử dụng các ký tự đặc biệt để truy cập đến các files và thư mục bên ngoài thư mục root của ứng dụng web. Khi kẻ tấn công có thể truy cập được các tệp tin và thư mục bên ngoài thư mục root này, họ có thể đọc hoặc sửa đổi các files quan trọng trên máy chủ web, thực hiện các cuộc tấn công khác hoặc thậm chí kiểm soát máy chủ web.
- Điểm chung: Cả LFI và Path Traversal đều liên quan đến việc đọc file và thư mục bên trong máy chủ web. Về tác hại thì cả 2 đều có thể dẫn đến việc tiết lộ thông tin nhạy cảm, mất dữ liệu và mất kiểm soát về hệ thống.
- Khác nhau: LFI tập trung vào việc đọc các files cục bộ và có thể thực thi mã độc trong máy chủ web, trong khi Path Traversal tập trung vào việc truy cập đến các files và thư mục bên ngoài thư mục gốc của ứng dụng web.

#### LAB, Root-me
<a href="https://github.com/caodchuong312/KCSC-Training/tree/main/task7/LFI_lab" >LFI</a>

<a href="https://github.com/caodchuong312/KCSC-Training/tree/main/task7/RFI_lab" >RFI</a>

<a href="https://github.com/caodchuong312/KCSC-Training/tree/main/task7/Root-me" >Root-me</a>
