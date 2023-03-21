`Task 7:` 
`Tìm hiểu về LFI, RFI (Khái niệm,nguyên nhân, tác hại + 1 số cách bypass , cách phòng chống).`

`Xây dựng lab tấn công demo cả 2 loại.`

`Làm hết các bài liên quan trên rootme`
# LFI (Local File Inclusion)
### Khái niệm
Đây là lỗ hổng bảo mật cho phép kẻ tấn công truy cập và hiện thị vào các file trên hệ thống máy chủ. Nó xảy ra khi kẻ tấn công chèn đường dẫn local qua HTTP request tới máy chủ
### Nguyên nhân
Nguyên nhân chính là do máy chủ không kiểm tra và xác thực đầu vào từ phía người dùng như các đường link dạng `php?file=...` ... Khi web sử dụng cách hàm như `include`,`require`... mà không xác thực thì dẫn đến việc kẻ tấn công sẽ thay đổi giá trị của tham số để xem hoặc thực thi các tệp trên máy chủ web. Ngoài ra còn do việc không kiểm soát dẫn không được quyền truy cập file hay đường dẫn trên hệ thống.
### Tác hại
  - Kẻ tấn công sẽ truy cập và đọc được thông tin nhạy cảm như `/etc/passwd`, các cấu hình, các file nhạy cảm... hoặc có thể thực thi các file khác.
  - Dẫn đến các cuộc tấn công khác nhứ XSS, DOS, Command Injection,..
  
# RFI (Remote Fle Iclusion)
### Khái niệm 
Đây là một lỗ hổng bảo mật trên các ứng dụng web cho phép kẻ tấn công chèn mã độc từ một máy chủ khác vào trong trang web bằng cách sử dụng đường dẫn tuyệt đối đến các tệp tin từ xa. RFI thường được sử dụng như một phương pháp tấn công phụ để thực hiện các cuộc tấn công khác.
### Nguyên nhân
- Thiếu kiểm tra và xác thực đầu vào: kẻ tấn công từ đó có thể chèn mã độc
- Sử dụng các hàm như `include`, `require`và không xác thực đúng cách
### Tác hại
- Truy cập và xem các file nhạy cảm.
- Thực thi mã độc: dẫn đến việc chiếm được quyền hệ thống.
- Thực thi các cuộc tấn công khác: Dos, SQL injection, XSS, ...
### Phòng chống
- Xác thực đầu vào an toàn.
- Giới hạn quyền truy cập vào hệ thống.
- Cập nhật ứng dụng, sử dụng tường lửa...
