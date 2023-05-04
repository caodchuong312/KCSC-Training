```
+ Tìm hiểu về Insecure Deserialization.(Khái niệm , nguyên nhân, tác hại, cách phòng chống).
+ Làm lab demo.
+ Làm các bài trên root-me
```
# Insecure Deserialization
## Khái niệm
Đầu tiền cần hiểu vể serialization và deserialization:
- Serialization là quá trình chuyển đổi đối tượng hoặc cấu trúc dữ liệu thành định dạng khác như chuỗi, chuỗi byte,... (serialized data) để dễ dàng lưu trữ và truyền tải.
- Deserialization là quá trình ngược lại với serialization là từ serialized data khôi phục lại đối tượng hoặc cấu trúc dữ liệu ban đầu.
Insecure Deserialization là lỗ hổng bảo mật xảy ra khi kẻ tấn công có thể kiểm soát được serialized data từ đó khi quá trình deserialization thực hiện, kẻ tấn công có thể thực thi mã độc trên ứng dụng, kiểm soát các đối tượng thậm chí là máy chủ ...
## Nguyên nhân 
Nguyên nhân chính là do ứng dụng cho phép deserialize từ serialized data mà người dùng có thể kiểm soát được. <br>
Ngoài ra còn do 1 số nguyên nhân như:
- Không kiểm tra đầu vào
- Sử dụng thư viện để deserialize không an toàn 
- ...
## Tác hại
Lỗ hổng này có thể gây ra nhiều tác hại nghiêm trọng:
- Thực thi các mã độc trên ứng dụng, có thể RCE.
- Thay đổi các thuộc tính đối tượng,...
- Gây tràn bộ nhớ ảnh hưởng tới ứng dụng và máy chủ.
- Dẫn đến 1 số cuộc tấn công khác
## Các phòng chống
Tốt nhất là không dùng dữ liệu từ người dùng để thực hiện quá trình deserialization.<br>
Tuy nhiên trong trường hợp phải dùng thì cần:
- Xác mính tính toàn vẹn của dữ liệu ví dụ như dùng chữ ký số,...
- Giới hạn quyền truy cập, chạy ứng dụng trong quyền thấp.
- Kiểm tra đầu vào.
- Sử dụng thư viện deserialization an toàn và thường xuyên cập nhật chúng.
