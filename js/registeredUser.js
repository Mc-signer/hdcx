function getRegUsers(){
	$.post("php/adminController.php",{'key':'getRegUsers'},function(data){
		data=eval("("+ data +")");
		console.log(data);
		$(".jiange #weizhuce").html("");
		//展示未注册用户
		for (var i =0;i<=data.length-1;i++) {
	       $(".main-right-match-content .jiange #weizhuce").append("<div class='col-md-11 main-right-match-content-detail '><div class='col-md-9'><div class='col-md-5 register-label'><div class='col-md-12'>学号：</div><div class='col-md-12'>姓名：</div><div class='col-md-12'>联系方式：</div><div class='col-md-12'>E-mail：</div></div><div class='col-md-7 register-content'><div class='col-md-12'>" + data[i].name + "</div><div class='col-md-12'>" + data[i].xingming + "</div><div class='col-md-12'>" + data[i].contact + "</div><div class='col-md-12'>" + data[i].email + "</div></div></div><div class='col-md-2 register-button'><button type='button' class=' col-md-12 btn btn-primary' onclick='agreeReg(this);'><input type='hidden' name='id' value='" + data[i].id + "'>同意</button><button type='button' class=' col-md-12 btn btn-default' style='background: #F2F2F2;' onclick='rejectReg(this);'>拒绝</button></div></div>");
		}
	});
}
//2017.4.28 毛帅男
//通过获取数已有条目数获得更多已注册用户数据的函数。从0开始计数
function getAllUsers(num){
	$.post("php/adminController.php",{'key':'getAllUsers','num':num},function(data){
		data=eval("("+ data +")");
		if(data.length==0) {alert("没有更多已注册用户！");$(".detail-more").remove();}
		//展示注册用户
		for (var i =0;i<=data.length-1;i++) {
	       $(".main-right-match-content .jiange #yizhuce").append("<div class='col-md-11 main-right-match-content-detail '><div class='col-md-9'><div class='col-md-5 register-label'><div class='col-md-12'>学号：</div><div class='col-md-12'>姓名：</div><div class='col-md-12'>联系方式：</div><div class='col-md-12'>E-mail：</div></div><div class='col-md-7 register-content'><div class='col-md-12'>" + data[i].name + "</div><div class='col-md-12'>" + data[i].xingming + "</div><div class='col-md-12'>" + data[i].contact + "</div><div class='col-md-12'>" + data[i].email + "</div></div></div><div class='col-md-2 register-button'><input type='hidden' name='id' value='" + data[i].id + "'></div></div>");
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
//2017.4.28 毛帅男
//更多按钮没有添加，注释部位代码添加后按钮位置会出错。另全为空时不展示按钮逻辑未写。
function moreUsers(){
	page+=10;
	$(".detail-more").remove();
	getAllUsers(page);
	$(".user-register-content .jiange1").append("<div class='col-md-11 col-md-offset detail-more' ><button type='button' class='detail-more-button' onclick='moreUsers();'>· · ·</button></div>");
    detailCenter();
}
getRegUsers();
var page=0;
getAllUsers(page);


$(".user-register-content .jiange1").append("<div class='col-md-11 col-md-offset detail-more' ><button type='button' class='detail-more-button' onclick='moreUsers();'>· · ·</button></div>");
detailCenter();


/*更多按钮居中*/
function detailCenter(){
var detailButton=($(".detail-more").width()-85)/2;
$(".jiange1 .detail-more").css("margin-left", detailButton);
}
/*居中*/
var actualWidth=($(window).width()-$(".main-right-match-content").width())/2-260;
$(".main-right-match-content").css("margin-left",actualWidth);

//撑开
var mainHeight=$(window).height()-$(".index-top").height()-$(".index-user").height()-$(".main").height()-320;
$("#chenggan").css("height",mainHeight);
