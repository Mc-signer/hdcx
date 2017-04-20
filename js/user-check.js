$.post("php/userController.php",{'key':'isLogin'},function(data){
	data=eval("(" + data + ")");
	if(!data.success){
		window.location.href="login.html";
	}
	else{//登陆成功
		//修改登陆名

		$(".index-user-name").find("a:last").html(data.userName);
		$("#name").val(data.userName);
		$("contact").val(data.contact);
		$("email").val(data.email);
		$("#userId").html(data.userId);
	} 
});
