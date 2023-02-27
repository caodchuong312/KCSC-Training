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

