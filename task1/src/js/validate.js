function validate(e) {
	var username = document.getElementById("username").value
	var password = document.getElementById("password").value
	if (username.length < 5 || username.length > 15) {
		document.getElementById("error").innerHTML = "Tên đăng nhập không hợp lệ";
		return false;
	}
	if (password.length < 8 || password.length > 20) {
		document.getElementById("error").innerHTML = "Mật khẩu không hợp lệ";
		return false;
	}
	if(document.querySelector("form[name='form-register']")){
		var re_password = document.getElementById("re_password").value	
		if (password != re_password) {
			document.getElementById("error").innerHTML = "Mật khẩu chưa khớp";
			return false;
		}
		alert('Dữ liệu gửi đi thành công!');
	}
	
}