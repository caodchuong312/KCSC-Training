**Task6**  <br>
` + Viết lí thuyết về lỗ hổng upload file (là gì, tại sao lại xảy ra lỗi, cách khai thác + một số cách bypass mà em biết, cách ngăn chặn)` <br>
` + Làm hết mấy bài upload file trên RootMe`

 # File upload vulnerabilities
 ## File upload vulnerabilities là gì?
 File upload vulnerabilities là lỗ hổng mà khi server cho phép người dùng upload files từ hệ thống lên server mà không có xác thực đầy đủ như tên, type, nội dung, size...
 
 Việc thực thi không đúng hạn chế đối với điều đó thì kẻ tấn công có thể gửi lên mã độc lến ứng dụng đó nhằm thực hiền mục đích xấu.
 ![image](https://user-images.githubusercontent.com/92881216/225553938-9b790939-122f-44bc-a1d6-e2d95170506c.png)

 ## Tại sao lại xảy ra lỗi?
 Nguyên nhân lỗ hổng này thường liên quan đến việc không kiểm tra, xác thực thông tin đầy đủ như tên, loại file, định dạng, kích thước, nội dung... Khi không hoặc thiếu các bước kiểm tra này thì kẻ tấn công có thể tải lên các tệp tin độc hại và thực thi mã độc trên máy chủ, gây ra một số hậu quả nghiêm trọng như mất hoặc lộ dữ liệu, chiếm quyền kiểm soát máy chủ...
 
 ## Cách khai thác và bypass
 Cách khai thác và bypass sẽ dựa vào cách xử lí file gửi lên ở client-side và server-side. <br>
 Đầu tiền là tìm hiểu về `web shell`: <br>
 Nó là một tập lệnh độc hại cho phép kẻ tấn công dùng để thực thi nó trên máy chủ từ xa bằng các yêu cầu HTTP.
 Nếu có thể tải lên thành công web shell thì kẻ tấn công có toàn quyền kiểm soát máy chủ. 
 Điều này có nghĩa là kẻ tấn công có thể đọc và ghi các tệp tùy ý, xem dữ liệu nhạy cảm, thậm chí sử dụng máy chủ để xoay vòng các cuộc tấn công chống lại cả cơ sở hạ tầng nội bộ và các máy chủ khác bên ngoài mạng.<br>
 Ví dụ:
 ```
 <?php echo system($_GET['command']); ?>
 ```
 Tập lệnh này sẽ được thực thi qua yêu cầu HTTP:
 ```
 GET /example/exploit.php?command=id HTTP/1.1
 ```
**Cách khai thác đối với các xác thực của file**: <br>
### 1. Xác thực file type
Khi gửi file qua form html, trình duyệt sẽ gửi dữ liệu qua POST method và thường content type là `multipart/form-data`.
Khi đó nội dung file gửi lên sẽ được chia thành các phần riêng biệt. Trong đó có header `Content-Type` là một cách để cho server biết kiểu MIME của dữ liệu.

Nếu không có biện pháp xác thực khác thì kẻ tấn công có thể dùng `Burp Suite` để thay đổi nó.
### 2. Xác thực dựa vào extension:
#### Blacklist, Whitelist
Với blacklist, server sẽ xác thực bằng cách từ chối các tệp có extension nằm trong blacklist được liệt kê sẵn.

Ngược lại thì whitelist, server sẽ cho phép các file có extention có trong whitelist.

Tuy nhiên phương pháp này sẽ khó để liệt kê hết và luôn có những lỗ hổng, cách bypass:

- PHP: .php, .php2, .php3, .php4, .php5, .php6, .php7, .phps, .phps, .pht, .phtm, .phtml, .htaccess ...
- Phân biệt chữ hoa chữ thường: .PhP3, pHP4, ...
- Sử dụng double extension nếu xác thực không kỹ. Ví dụ: .jpg.php, .png.php5, ...
- Sử dụng `null` bytes: Server có thể đọc và ghi tên file và sẽ ngắt nếu `null` bytes: .php%00.png, php\x00.png, php%0a.png, php%0d%0a.png, ...
#### Một cách để bypass nữa đó là ghi đè các cấu hình của server:
Để server thực thi 1 file PHP hay file khác thì server sẽ được cấu hình để nó thực thi được. Ví dụ với `apache2.conf` thì sẽ có cấu hình:
```
LoadModule php_module /usr/lib/apache2/modules/libphp.so
```
Điều đó sẽ giúp module PHP kết nối với Apache để nó có thể xử lý file php.

Một cấu hình khác là của file `.htaccess`:
```
AddType application/x-httpd-php .php
```
Khi đó tệp tin `.php` sẽ được thực thi.
### 3. Xác thực dựa vào nội dụng file.
Server sẽ xác thực dựa vào một số bytes đầu và cuối đặc trưng cho từng loại file. Ví dụ JPEG file bắt đầu bằng các bytes: `FF D8 FF`.<br>
Một cách khác nữa, server có thể dựa vào kết quả của hàm `getimagesize()` trong PHP để xem kích thước ảnh.

Cách để bypass cho kiểu xác thực này là sẽ chèn các script đọc hại vào giữa nội dung file để đánh lừa server. Ngoài ra có thể dùng 1 số tools như `Exiftool` để tạo `polyglo` có chứa mã độc.

Ví dụ:
```
exiftool -Comment="<?php echo 'START ' . phpinfo(). ' END'; ?>" image.jpg -o polyglot.php
```
Tham khảo thêm: https://medium.com/swlh/polyglot-files-a-hackers-best-friend-850bf812dd8a
### 4. Xác thực bằng cách ngăn truy cập thư mục chứa file tải lên
Người dùng có thể hạn chế truy cập vào thư mục chứ các file tải lên. Tuy nhiên có thể dễ dàng bypass bằng cách đặt tên file sử dụng `../` để thay đổi các thưc mục chứa/

Ví dụ : `..%2fexploit.php`, `../../exploit.php`,...

Ngoài ra server có thể đặt file ở thư mục ngoài web server.
Điều này liên quan đến lỗ hổng như: Local file inclusion, Path traversal .

### 5. Một số cách khai thác khác
- Khai thác qua `RCE`.
- Thực thi phía client-side như sử dụng tag `<script>
- Tải lên các tập tin khác như `.doc`, `.xls` để khai khác lỗ hổng XXE injection.
- Tải file lên bằng `PUT` method
## Cách ngăn chặn 
- Kiểm tra extension tệp dựa trên whitelist thay vì blacklist.
- Tên tệp không được chứa các ký tự như `../` ...
- Giới hạn kích thước file.
- Quét mã độc file, các phần được nhúng vào.
- Đổi tên file upload nếu trùng để tránh ghi đè.
- Xác thực ở một nơi khác an toán trước khi đưa nó vào server.
- Giới hạn quyền truy cập thư mục, file thực thi.

---

**Tham khảo thêm:**
- https://portswigger.net/web-security/file-upload
- https://viblo.asia/p/khai-thac-cac-lo-hong-file-upload-phan-1-aWj53L6pK6m
- https://book.hacktricks.xyz/pentesting-web/file-upload

---

# Rootme

 
