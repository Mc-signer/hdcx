function getRegUsers(){
	$.post("php/adminController.php",{'key':'getRegUsers'},function(data){
		data=eval("("+ data +")");
		console.log(data);
		$(".jiange").html("");
		if(data.length==0) $(".user-register-content .none").css("display","block");
		//展示未注册用户
		for (var i =0;i<=data.length-1;i++) {
	       $(".main-right-match-content .jiange").append("<div class='col-md-11 main-right-match-content-detail '><div class='col-md-9'><div class='col-md-5 register-label'><div class='col-md-12'>用户名：</div><div class='col-md-12'>联系方式：</div><div class='col-md-12'>E-mail：</div></div><div class='col-md-7 register-content'><div class='col-md-12'>" + data[i].name + "</div><div class='col-md-12'>" + data[i].contact + "</div><div class='col-md-12'>" + data[i].email + "</div></div></div><div class='col-md-2 register-button'><button type='button' class=' col-md-12 btn btn-primary' onclick='agreeReg(this);'><input type='hidden' name='id' value='" + data[i].id + "'>同意</button><button type='button' class=' col-md-12 btn btn-default' style='background: #F2F2F2;' onclick='rejectReg(this);'>拒绝</button></div></div>");
		}
	});
}
function agreeReg(btn){
	var json={
		'key':'judge',
		'agree':true,
		'id':$(btn).find("input").val(),
	};
	$.post("php/adminController.php",json ,function (data){
		data=eval("("+data+")");
		getRegUsers();
	});
}
function rejectReg(btn){
	var json={
		'key':'judge',
		'agree':false,
		'id':$(btn).find("input").val(),
	};
	console.log(json);
	$.post("php/adminController.php",json ,function (data){
		data=eval("("+data+")");
		console.log(data);
		getRegUsers();
	});
}
getRegUsers();

var actualWidth=($(window).width()-$(".main-right-match-content").width())/2-260;
$(".main-right-match-content").css("margin-left",actualWidth);



//撑开
var mainHeight=$(window).height()-$(".index-top").height()-$(".index-user").height()-$(".main").height()-320;
$("#chenggan").css("height",mainHeight);
