<img width=800px src="https://user-images.githubusercontent.com/92881216/222705843-99e0b223-01f5-43b9-bb6b-6ba9fd76f0aa.png" />

Ứng dụng ở đây thể hiện 1 bài đăng và phía dưới là phần comment.

Khi dùng chức năng comment và gửi lên thì comment và nên người dùng sẽ được lưu lại trong db đồng thời hiển thị lên web và mọi người truy cập vào bài post này đều thấy

Khi đó kẻ tấn công sẽ tấn công ở đây với lỗ hổng Stored XSS. Do ứng dụng không có filter hay phương pháp prevention gì nên dễ dàng chèn mã độc `<img src=1 onerror=alert(1) />` . Nó sẽ hiện thị ảnh lỗi và trình duyệt thực hiện `alert(1)`. Và dĩ nhiên nó lưu lại trong bài post đó nên bất kỳ người dùng nào truy cập vào bài đăng đó đều bị `alert(1)`.

> payload : `<img src=1 onerror=alert(1) />`

<img width=800px src="https://user-images.githubusercontent.com/92881216/222706100-cb202f27-13a9-4523-aaec-031e95021ee4.png" />

