```
Task 10:
+ Tìm hiểu về lỗ hổng code injection  và command injection.(Khái niệm , nguyên nhân, tác hại + 1 số cách bypass, cách phòng chống).
+ Nêu ra điểm khác nhau giữa hai lỗi.
+ Làm lab demo hai lỗi trên.
```

# Code Injection
### Khái niệm
Code Injection là loại tấn công mà kẻ tấn công có thể chèn mã độc vào ứng dụng. Khi đó, ứng dụng thực thi đoạn mã độc đó và gây ảnh hưởng đến người dùng, máy chủ.
### Nguyên nhân
Nguyên nhân chính của `code injection` là việc không kiểm tra đầu vào đúng cách, sử dụng các thư viện không an toàn hoặc không cập nhật, hay không sử dụng các biện pháp bảo vệ bảo mật như mã hóa dữ liệu. Ngoài ra còn do server có các chức năng hoặc  thực hiện các hàm nguy hiểm mà người dùng kiểm soát được như: `eval()`, `system()`,...
### Tác hại 
- Tác hại của `code injection` có thể rất nghiêm trọng. Kẻ tấn công có thể truy cập và thay đổi dữ liệu, lấy cắp thông tin nhạy cảm, thực hiện các hành động trái phép trên hệ thống máy chủ, thậm chí kiểm soát toàn bộ máy chủ.
- Ngoài ra còn có thể dẫn đến các cuộc tấn công khác như: SQL injection, XXS, Command injection, ...
### Cách phòng chống
- Xác thực đầu vào như sử dụng whitelist: chỉ cho phép các từ kiểm soát được.
- Mã hóa: escape HTML entities để hạn chế nguy cơ thành mã độc hoặc mã hóa dữ liệu bằng cách khác trước khi được lưu trữ hoặc chuyển lên internet.
- Sử dụng tường lửa, các công cụ phát hiện tấn công và thường xuyên cập nhật thư viện an toàn.
# Commmand Injection
### Khái niệm
Command injection là lỗ hổng mà kẻ tấn công có thể thực thi câu lệnh hệ điều hành trên máy chủ của ứng dụng đó
### Nguyên nhân
Nguyên nhân chính của command injection là thiếu kiểm tra đầu vào của ứng dụng hoặc sử dụng các hàm không an toàn trong xử lý đầu vào như: `system()`, `eval()`, `exec()`, ...
Khi những lỗ hổng này xảy, kẻ tấn công có thể thực hiện các hành động trái phép nh như xem, sửa đổi hoặc xóa dữ liệu, kiểm soát toàn bộ hệ thống, và thực hiện các cuộc tấn công khác như DoS,...
### Phòng chống
- Kiểm tra xác thực đầu vào: Dùng whitelist để cho phép các câu lệnh an toàn được thực thi, loại bỏ các ký tự có thể chèn mã đọc như: `;`, `"`, `'`, `|`, `&`,...
- Chỉ chấp nhận đầu vào chỉ có ký tự chữ và số, không có ký tự đặc biệt, khoảng trắng, ...
- Ngoài ra cần thường xuyên cập nhật ứng dùng, thư viên an toàn.


  
