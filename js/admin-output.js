getTeam();
function getTeam(){
	$.post("php/teamController.php",{"key":"getTeam"},function(data){
		data=eval("("+data+")");
        console.log(data);
        var statusNumber=parseInt(data.status)+1;
		var obj=$("#dropdownMenu1");
        var state=new Array('报名未通过','未处理','报名通过','校内初赛通过','校内决赛通过');
        $(obj).html(state[statusNumber] + "<img src='img/left.png' class='left-img'><img src='img/down.png' class='down-img'>");
		$(".contain-text .main-right-match-title-text").html(data.gameName);
		$(".contain-bottom-text").html(data.pro_name);
		$("#priName").html(data.pri_name);
        $("#proContact").html(data.pro_contact);
        $("#proIntro").html(data.pro_intro);
        $("#proName").html(data.pro_name);
        $("#teachContact").html(data.teach_contact);
        $("#teachName").html(data.teach_name);
        $("#teamName").html(data.team_name);
        $("#teamId").html(data.id);
        $("#award").val(data.award);   //团队获奖情况
        for (var i = 0;i<=data.members.length - 1;i++) {
        	$("#members").append("<tr><td></td><td>" + data.members[i].name + "</td><td>" + data.members[i].gender + "</td><td>" + data.members[i].contact + "</td><td>" + data.members[i].insititute + "</td><td>" + data.members[i].class + "</td><td>" + data.members[i].stunum + "</td><td>" + data.members[i].idcard + "</td><td>" + data.members[i].email + "</td></tr>");
        }
        
	});
}


/*通过撤回按钮*/
function pass(element){
        var obj=$("#dropdownMenu1");
        var teamStatus=$(element).attr("data");// 修改后的状态
        $(obj).html($(element).text() + "<img src='img/left.png' class='left-img'><img src='img/down.png' class='down-img'>");
alert("该队伍  "+$(element).text()+"！" );

$.post("php/adminController.php",{"key":"pass","pass":teamStatus,"teamId":$("#teamId").html()},function(data){   //此处修改队伍状态
  
    data=eval("("+data+")");

});
}

$(".destroy").remove();