Nhìn vào lab dễ dàng biết được tham số `page` dễ bị LFI:

![image](https://user-images.githubusercontent.com/92881216/227257986-842f4d88-40d9-40f4-bfae-602a18f5dd40.png)

Nhập thử `abc` thì có lỗi xuất hiện : 

![image](https://user-images.githubusercontent.com/92881216/227258375-49367145-4b8e-4680-837d-9404d79ad968.png)

Tiếp tục khai thác với payload `../../../../etc/passwd`

![image](https://user-images.githubusercontent.com/92881216/227258821-aa0454cc-5c3f-4744-9a62-d089330cbd6e.png)

Nhìn vào lỗi dễ dàng biết được ứng dụng đã filter `../`. <br>Vậy để bypass sử dụng payload: `....//....//....//....//etc/passwd`:

![image](https://user-images.githubusercontent.com/92881216/227259398-fac264e9-7662-4f52-8a80-8d9f9db0bfa0.png)


