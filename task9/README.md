# SSTI
Tìm hiểu về SSTI (Khái niệm, nguyên nhân, tác hại + 1 số cách bypass, cách phòng tránh).<br>
Giải hết các bài SSTI trên rootme

## Khái niệm 
SSTI (Server Side Template Injection) là lỗ hổng bảo mật mà khi ứng dụng nhận dữ liệu đầu vào từ người dùng để tạo các template và từ đó kẻ tấn công có thể chèn mã độc nhằm mục đích xấu
## Nguyên nhân
- Nguyên nhân chính dẫn đến lỗ hổng này là do các ứng dụng web sử dụng các thư viện hay framework hỗ trợ template phía myá chủ mà không kiểm soát đầy đủ đầu vào của người dùng
- Khi ứng dụng web chấp nhận dữ liệu đầu vào, kẻ tấn công chèn mã đọc và nó được thực thi trên máy chủ từ đó chiếm quyền kiểm soát máy chủ
## Tác hại
- Các lỗ hổng SSTI làm máy chủ web chịu nhiều cuộc tấn công tùy thuộc vào các loại template engine.
- Nghiêm trọng nhẫn là dẫn đến RCE, từ đó chiếm quyền kiểm soát toàn bộ máy chủ .
- Khi không thực hiện được RCE, kẻ tấn công cũng có thể tạo ra nhiều cuộc tấn công khác như: XSS, CSRF,...
## Một số cách bypass
