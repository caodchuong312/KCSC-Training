<img width=800px src="https://user-images.githubusercontent.com/92881216/222703680-66db194f-d801-455d-a1a3-9a3f1017f9fd.png" />

Ở đây app sẽ lấy giá trị từ param `search` thông qua form được gửi lưu vào biến `animal` trong js.

`document.write()` là một phương thức trong js cho phép thêm nội dung vào vị trí mà đang được tải của một trang HTML.

Khi đó nội dụng tải lên sẽ là: `<br><img width=500px src="/image/'+animal+'.jpg" >` với thuộc tính `src` trong thẻ img được tạo nên và ghép với biến `animal` ở trên. Đây là chỗ để kẻ tấn công inject và thực hiện cuộc tấn công DOM based XSS.

Để thoát khỏi tag img kẻ tấn công đã dùng `">` để đóng tag này lại từ đó có thể viết tiếp mã độc phía sau ví dụ như `<img src=1 onerror=alert(1) />`. Đương nhiên `src=1` bị lỗi và sẽ thực hiện `alert(1)` thông qua `onerror`.

> Payload : `"><img src=1 onerror=alert(1) />`

<img width=800px src="https://user-images.githubusercontent.com/92881216/222703874-a4d061fc-be2d-4dfc-a67c-f12f01e840e1.png" />

Kết quả tag `img` được tạo thêm.
<img width=800px src="https://user-images.githubusercontent.com/92881216/222704208-7e8303d6-9725-485e-a54b-39dc5f92d3b3.png" />

