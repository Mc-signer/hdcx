$.post("php/adminController.php",{'key':'isLogin'},function(data){
	data=eval("(" + data + ")");
	console.log(data);
	if(!data.success){
		window.location.href="admin.html";
	}
	else {//登陆成功
	//修改登陆名
		$(".index-user-name a:last").html(data.adminName);
	}
});
function logout(){
	$.post("php/adminController.php",{"key":"logout"},function(data){

	});
}