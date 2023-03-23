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


