$.post("php/userController.php", { 'key': 'isLogin' }, function(data) {
    data = eval("(" + data + ")");
    if (!data.success) {
        window.location.href = "login.html";
    } else { //登陆成功
        //修改登陆名
        
        $(".index-user-name").find("a:last").html(data.userXingming);
    }
});

function logout() {
    $.post("php/userController.php", { 'key': 'logout' }, function(data) {});
}
 
function addMember() {
    $("#members").append("<tr><td><button><img src='img/delete.png'></button></td><td>***</td><td>*</td><td>***********</td><td>***系</td><td>**1501</td><td>*****</td><td>*****</td><td>***@***.com</td></tr>");
    $("#members tr:last td").attr("onclick", "ShowElement(this);");
    $("#members tr:last td:first").attr("onclick", "deleteMember(this);");
}

function submitMessage() { //上传信息按钮绑定事件
    var member = new Array();
    var partner = $("#members tr");
    var award1, award2;
    
        award1 = $("#award1").val();
        award2 = $("#award2").val(); 

    for (var i = 0; i <= partner.length - 1; i++) {
        var information = $(partner[i]).find("td");
        member[i] = {
            "name": $(information[1]).html(),
            "gender": $(information[2]).html(),
            "contact": $(information[3]).html(),
            "insititute": $(information[4]).html(),
            "class": $(information[5]).html(),
            "stunum": $(information[6]).html(),
            "idcard": $(information[7]).html(),
            "email": $(information[8]).html(),
        };
    }
    var json = {
        //比赛Id要传
        "key": "editTeam",
        "gameId": $("#gameId").val(),
        "priName": $("#priName").val(),
        "priContact": $("#priContact").val(),
        "proIntro": $("#proIntro").val(),
        "proName": $("#proName").val(),
        "teachContact": $("#teachContact").val(),
        "teachName": $("#teachName").val(),
        "teamName": $("#teamName").val(),
        "teamId": $("#teamId").html(),
        "award1": award1, 
        "award2": award2,//团队获奖情况
        "members": member,
        //"attachment":......
    };
    $.post("php/teamController.php", json, function(data) {
        data = eval("(" + data + ")");
        alert(data.notice);
    });

}

$.post("php/teamController.php", { "key": "getTeam" }, function(data) {
    data = eval("(" + data + ")");
console.log(data);
    var statusNumber = parseInt(data.status) + 1;
    var state = new Array('撤回', '未处理', '通过', '校内初赛通过', '校内决赛通过');
    $("#status").val(state[statusNumber]);
    $(".contain-text .main-right-match-title-text").html(data.gameName);
    $(".contain-bottom-text").html(data.pro_name);
    $("#priName").val(data.pri_name);
    $("#priContact").val(data.pri_contact);
    $("#proIntro").val(data.pro_intro);console.log($("#proIntro").html());
    $("#proName").val(data.pro_name);
    $("#teachContact").val(data.teach_contact);
    $("#teachName").val(data.teach_name);
    $("#teamName").val(data.team_name);

    $("#award1").val(data.award1);
    $("#award2").val(data.award2);//团队获奖情况

    $("#gameId").val(data.game_id);
    $("#teamId").html(data.id);
    for (var i = 0; i <= data.members.length - 1; i++) {
        $("#members").append("<tr><td><button><img src='img/delete.png'></button></td><td>" + data.members[i].name + "</td><td>" + data.members[i].gender + "</td><td>" + data.members[i].contact + "</td><td>" + data.members[i].insititute + "</td><td>" + data.members[i].class + "</td><td>" + data.members[i].stunum + "</td><td>" + data.members[i].idcard + "</td><td>" + data.members[i].email + "</td></tr>");
    }
     $("#members td").attr("onclick","ShowElement(this);");
            $("#members tr").find("td:first").attr("onclick","deleteMember(this);");
    var nowTime = new Date; //获取当前时间
    var endDateTemp = data.deadline.split(" ");
    var arrEndDate = endDateTemp[0].split("-");
    var arrEndTime = endDateTemp[1].split(":");
    var allEndDate = new Date(arrEndDate[0], arrEndDate[1] - 1, arrEndDate[2], arrEndTime[0], arrEndTime[1], arrEndTime[2]);

    if (nowTime.getTime() > allEndDate.getTime()) //现在的时间超过截止时间
    {
        $(".awardClass").css("display", "block");
        $(".statusClass").css("display", "none");
        $("#basic input").addClass("input-none");
        $("#basic input").css("cursor", "default");
        $("#basic input").attr("readonly", "readonly");
        $("#basic textarea").css("cursor", "default");
        $("#basic textarea").attr("readonly", "readonly");
        $("#award").css("cursor", "text");
        $("#award").removeAttr("readonly");

    } else {
        $("#members td").attr("onclick", "ShowElement(this);");
        $("#members tr").find("td:first").attr("onclick", "deleteMember(this);");

    }

});



function deleteMember(element) {
    $(element).parent().remove();
}


/*编辑成员信息*/
function ShowElement(element) {　
    if ($(element).find("input").length == 0) {

        var oldhtml = element.innerHTML; //获得元素之前的内容
        var newdata = $(element).attr("data");
        var newobj;
        newobj = document.createElement('input');
        newobj.type = "text";　 //为newobj元素添加类型
        newobj.value = "";
        newobj.style.width = "100%";
        newobj.style.paddingLeft = "2px";
        newobj.style.height = "20px";　　 //设置newobj失去焦点的事件
        newobj.onblur = function() {　　
            element.innerHTML = this.value ? this.value : oldhtml;　 //当触发时判断newobj的值是否为空，为空则不修改，并返回oldhtml。            　　
        }　
        element.innerHTML = '';　 //设置元素内容为空       　
        element.appendChild(newobj);　 //添加子元素      　　　　
        newobj.focus();　 //获得焦点
    }
}

function delButton(element) {
    var team_id = $("#teamId").html();
    var del_tr = $(element).parent().parent();
    var del_td = $(del_tr).find("td");
    var file_name = $(del_td[1]).find("a").attr("title");
    console.log(team_id, file_name);
    var json = {
        "key": "deleteFile",
        "teamId": team_id,
        "fileName": file_name
    };
    $.post("php/teamController.php", json, function(data) {
        data = eval("(" + data + ")");
        alert(data.notice);
        if (data.notice == "删除成功！") $(del_tr).remove();
    });
}
