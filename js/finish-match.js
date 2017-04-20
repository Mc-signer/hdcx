
    /*双击编辑元素内容*/
    function ShowElement(element) {　
        if (($(element).find("input").length == 0) && ($(element).find("textarea").length == 0)) {
            var oldhtml = element.innerHTML; //获得元素之前的内容
            var newdata = $(element).attr("data");
            var newobj;

            if (newdata == 'a')　
                newobj = document.createElement('input');
            else {
                newobj = document.createElement('textarea');
                newobj.rows = 6;
                newobj.style.width = "100%";
                newobj.style.resize = "none";
            }　
            newobj.style.background="#DEF5F5";

            
            newobj.type = "text";　 //为newobj元素添加类型
            newobj.value = oldhtml;　　　 //设置newobj失去焦点的事件
            newobj.onblur = function() {　　
                element.innerHTML = this.value ? this.value : oldhtml;　 //当触发时判断newobj的值是否为空，为空则不修改，并返回oldhtml。            　　
            }　
            element.innerHTML = '';　 //设置元素内容为空       　
            element.appendChild(newobj);　 //添加子元素      　　　　
            newobj.focus();　 //获得焦点
        }
    }
    getTeams();
function getTeams(){
    //var state=
	$.post("php/teamController.php",{"key":"getTeams"},function(data){
		
        data=eval("("+data+")");     
        
        var state=new Array('撤回','未处理','通过','校内初赛通过','校内决赛通过');
       if (data.length == 0) $(".main-admin-match-team-content .none").css("display", "block"); 
		$(".contain-text .main-right-match-title-text").html(data.game.name);
        $("#timeInput").val(data.game.deadline);
            $(".contain-bottom .detail-body-now").html(data.game.intro);
		for (var i = 0;i <= data.teams.length - 1;i++) { 
            var statusNumber=parseInt(data.teams[i].status)+1;
			$(".main-admin-match-team-content").append("<div class='col-md-12 main-right-match-content-detail' id='" + data.teams[i].id + "' style='display:block;'><div class='col-md-9' onclick='viewTeam(this);'><div class='col-md-12 detail-head'><div class='col-md-12 detail-head-left'>" + data.teams[i].team_name + "</div></div><div class='col-md-12 detail-body-now'>" + data.teams[i].pro_intro + "</div></div><div class='col-md-3'></div>    </div>");

		}
	});
}




/*点击查看队伍*/
function viewTeam(element){
     window.location.href="finish-output.html";
     sendTeamId(element);

}

function sendTeamId(element){
	var json={
		"key":"sendTeamId",
		"teamId":$(element).parent().attr("id")
	}
	$.post("php/teamController.php",json,function(data){
		data=eval("("+data+")");
		
	});
}
/*撑开页面*/
var mainHeight=$(window).height()-$(".index-top").height()-$(".index-user").height()-230;
$("#chenggan").css("height",mainHeight)


/*居中*/
    var actualWidth=($(window).width()-$(".main-admin-match-team-content").width())/2-$(".main-right-match-title-text").width()+30;
    $(".main-admin-match-team-content").css("margin-left",actualWidth);