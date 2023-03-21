`Task 7:` 
`Tìm hiểu về LFI, RFI (Khái niệm,nguyên nhân, tác hại + 1 số cách bypass , cách phòng chống).`

`Xây dựng lab tấn công demo cả 2 loại.`

`Làm hết các bài liên quan trên rootme`
# LFI (Local File Inclusion)
- Khái niệm: <br>Đây là lỗ hổng bảo mật cho phép kẻ tấn công truy cập và hiện thị vào các file trên hệ thống máy chủ. Nó xảy ra khi kẻ tấn công chèn đường dẫn local qua HTTP request tới máy chủ
- Nguyên nhân:<br>Nguyên nhân chính là do máy chủ không kiểm tra và xác thực đầu vào từ phía người dùng. Khi web sử dụng cách hàm như `include`,`require`... mà không xác thực thì u này cho phép kẻ tấn công thay đổi giá trị của tham số để xem hoặc thực thi các tệp trên máy chủ web. 
