# SSTI
Tìm hiểu về SSTI (Khái niệm, nguyên nhân, tác hại + 1 số cách bypass, cách phòng tránh).<br>
Giải hết các bài SSTI trên rootme

## Khái niệm 
SSTI (Server Side Template Injection) là lỗ hổng bảo mật mà khi ứng dụng nhận dữ liệu đầu vào từ người dùng để tạo các template và từ đó kẻ tấn công có thể chèn mã độc nhằm mục đích xấu
## Nguyên nhân
- Nguyên nhân chính dẫn đến lỗ hổng này là do các ứng dụng web sử dụng các thư viện hay framework hỗ trợ template phía myá chủ mà không kiểm soát đầy đủ đầu vào của người dùng
- Khi ứng dụng web chấp nhận dữ liệu đầu vào, kẻ tấn công chèn mã đọc và nó được thực thi trên máy chủ từ đó chiếm quyền kiểm soát máy chủ
## Tác hại
- Các lỗ hổng SSTI làm máy chủ web chịu nhiều cuộc tấn công tùy thuộc vào các loại template engine.
- Nghiêm trọng nhẫn là dẫn đến RCE, từ đó chiếm quyền kiểm soát toàn bộ máy chủ .
- Khi không thực hiện được RCE, kẻ tấn công cũng có thể tạo ra nhiều cuộc tấn công khác như: XSS, CSRF,...
## Một số cách bypass
## Phòng chống
- Kiểm tra và xác thực các đầu vào người dùng như sử dụng regex, whitelist để đảm bảo không chứa ký tự có thể chèn mã độc.
- Sử dụng các template engine an toàn như `Mustache` có tính năng escape các ký tự đặc biệt và thường xuyên cập nhật chúng.
- Sử dụng `docker` để tạo một môi trường an toàn hạn chế hoạt động độc hại.
# Rootme

## Python - Server-side Template Injection Introduction

Web có chức năng render ra `title` và `content` khi nhập:

![image](https://user-images.githubusercontent.com/92881216/231993476-2f64e2a8-d573-4a52-9dcc-aa1fbd017b78.png)

Vì tiêu đề cho biết web được viết bằng `python`, ta thử luôn với payload `{{7*7}}` được kết quả:

![image](https://user-images.githubusercontent.com/92881216/231993895-ccd91592-c5c8-4976-8cfc-fef657093fdf.png)

Vì phần `content` hiển thị `49` vậy chỗ này bị lỗi SSTI.

Sau khi xác định được lỗi, thử khai thác bằng code injection như `{{ import os; os.system("ls") }}` nhưng bị cách này không được. Tiếp tục với hướng khai thác khác là lấy ra được module `os` hoặc các module có thể khai thác được nếu nó có import `sys`, `os`. Xây dựng payload:

Trong python mọi thứ đều là `object`. Đầu tiên tạo chuỗi `''` và dùng `.__class__` lấy kết quả trả về là class của đối tượng chuỗi rỗng. Tiếp dùng `.__base__` để trả về class cha là root object. Dùng tiếp `.__subclasses__()` để trả về class con của root object. Đến đây thì 1 class có thể khai thác, ở đây có ` <class 'subprocess.Popen'>`. Tiếp tục dùng `Intruder` trong Burp Suite lấy index của nó :

![image](https://user-images.githubusercontent.com/92881216/232005125-54519380-1c4a-4393-b5f6-d90dd242613f.png)

Khi đó payload đầy đủ là:
```
{{''.__class__.__base__.__subclasses__()[408]('cmd',shell=True,stdout=-1).communicate()}}
```
Bây giờ chỉ việc đi tìm `flag` thôi.

Có vẻ flag là file `.passwd`:

![image](https://user-images.githubusercontent.com/92881216/232006911-ea0c9796-55d6-4475-a17a-38e675a7fddf.png)

Đọc nó:

![image](https://user-images.githubusercontent.com/92881216/232007054-dc52522c-c7c0-43c0-84ca-7131fdbaccaf.png)

> flag: `Python_SST1_1s_co0l_4nd_mY_p4yl04ds_4r3_1ns4n3!!!`

## Java - Server-side Template Injection
`Description`: Exploit the vulnerability in order to retrieve the validation password in the file SECRET_FLAG.txt.

Web có 1 input và nó sẽ in kết quả ra màn hình.

Theo tiêu đề web viết bởi Java, ta thử với payload `${7*7}` thì kết quả được 49 vậy xác định lỗi SSTI.

![image](https://user-images.githubusercontent.com/92881216/232008454-afabcaf2-80ff-49e1-b009-b896c5fb32df.png)

Nó chỉ đúng với payload đó nên khả năng web sử dụng engine `FreeMarker`.

![image](https://user-images.githubusercontent.com/92881216/232009737-359bf137-e5f7-4aae-87fc-76e0e98560e4.png)

Bây giờ lên mạng tìm payload và thử từng cái ở <a href="https://book.hacktricks.xyz/pentesting-web/ssti-server-side-template-injection#freemarker-java" >đây</a>

Payload tìm được : `<#assign ex = "freemarker.template.utility.Execute"?new()>${ex("id")}`

![image](https://user-images.githubusercontent.com/92881216/232012746-94d16ac6-ea0f-4eb5-9300-d6b8bca7b9d5.png)

Tìm hiểu 1 chút về payload này:  
- `assign` được sử dụng để gán một giá trị cho một biến (ở đây là biến `ex`).
- `freemarker.template.utility.Execute` là lớp đối tượng có chức năng thực thi câu lệnh với `?new()` là khởi tạo đối tượng đó.
- `${ex("id")` là gọi lại với câu lệnh truyền vào là `id`.

Kết quả:

![image](https://user-images.githubusercontent.com/92881216/232012261-104dc843-0d7f-40c0-a87b-f9254f0cad41.png)

Đọc file `SECRET_FLAG.txt`:

![image](https://user-images.githubusercontent.com/92881216/232012426-24fdd6b4-a245-4e7b-89a5-ea9c6b010f4d.png)

> flag: `B3wareOfT3mplat3Inj3ction`

## Python - Blind SSTI Filters Bypass
