$.post("php/userController.php", { 'key': 'isLogin' }, function(data) {
    data = eval("(" + data + ")");
    if (!data.success) {
        window.location.href = "login.html";
    } else { //登陆成功
        //修改登陆名
        console.log(data);
        $(".index-user-name").find("a:last").html(data.userXingming);
        $("#name").val(data.userName);
        $("#contact").val(data.userContact);
        $("#email").val(data.userEmail);
        $("#userId").html(data.userId);
        $("#xingming").val(data.userXingming);
    }
});

/*选中样式*/
function oneChoose() {
    $("#primary-two-choosed").removeClass("choosed");

    $("#primary-one-choosed").addClass("choosed");
}

function twoChoose() {
    $("#primary-one-choosed").removeClass("choosed");
    $("#primary-two-choosed").addClass("choosed");
}
/*内容显示*/
function oneShow() {
    $("#one").css("display", "block");
    $("#two").css("display", "none");
    $("#three").css("display", "none");
    $("#four").css("display", "none");
}

function twoShow() {
    $("#two").css("display", "block");
    $("#one").css("display", "none");
    $("#three").css("display", "none");
    $("#four").css("display", "none");
}

function threeShow() {
    $("#three").css("display", "block");
    $("#two").css("display", "none");
    $("#one").css("display", "none");
    $("#four").css("display", "none");
}

function fourShow() {
    $("#four").css("display", "block");
    $("#two").css("display", "none");
    $("#three").css("display", "none");
    $("#one").css("display", "none");
}
/*展示正在报名*/
$.post("php/gameController.php", { 'key': 'getUnRegGames' }, function(data) {
    data = eval("(" + data + ")");
    if (data.length == 0) $(".main-right-match-now .main-right-match-content .none").css("display", "block");
    for (var i = 0; i <= data.length - 1; i++)
        $(".main-right-match-now .main-right-match-content").append("<div class='col-md-12 main-right-match-content-detail' id='" + data[i].id + "' onclick='viewNowTeam(this);' ><div class='col-md-12 detail-head'><div class='col-md-5 detail-head-left'>" + data[i].name + "</div><div class='col-md-3 detail-head-middle-now'>" + data[i].date + "</div><div class='col-md-4 detail-head-right-now'>截止日期：" + data[i].deadline + "</div></div><div class='col-md-12 detail-body-now'>" + data[i].intro + "</div><div class='col-md-12 detail-foot-now'>主办方：" + data[i].sponsor + "</div></div>");
});
/*传输个人资料*/
function editUser() {
    var json = {
        "key": "editUser",
        "name": $("#name").val(),
        "contact": $("#contact").val(),
        "email": $("#email").val()
    };
    $.post("php/userController.php", json, function(data) {
        data = eval("(" + data + ")");
        alert(data.notice);
    });
}
/*修改密码*/
function editPassword() {
    var json = {
        "key": "editPassword",
        "name": $("#name").val(),
        "old": $("#oldpassword").val(),
        "new": $("#newpassword").val(),
        "again": $("#again").val()
    };
    $.post("php/userController.php", json, function(data) {
        data = eval("(" + data + ")");
        alert(data.notice);
    });
}

function logout() {
    $.post("php/userController.php", { 'key': 'logout' }, function(data) {

    });
}



/*点击查看报名队伍*/
function viewNowTeam(element) {
    window.location.href = 'registration.html';
    sendGameId(element);

}
/*传送比赛ID*/
function sendGameId(element) {
    var json = {
        "key": "sendGameId",
        "gameId": $(element).attr("id")
    }
    $.post("php/teamController.php", json, function(data) {
        data = eval("(" + data + ")");
    });
}


/*展示已经报名*/
$.post("php/gameController.php", { 'key': 'getRegGames' }, function(data) {
    data = eval("(" + data + ")");
    console.log(data);
    if (data.length == 0) $(".main-right-match-finish .main-right-match-content .none").css("display", "block");
    var state = new Array('撤回', '未处理', '通过', '校内初赛通过', '校内决赛通过');
    var nowTime = new Date; //获取当前时间
    for (var i = data.length - 1; i >=0 ; i--) {
        var statusNumber = parseInt(data[i].status) + 1;
        var endDateTemp = data[i].deadline.split(" ");
        var arrEndDate = endDateTemp[0].split("-");
        var arrEndTime = endDateTemp[1].split(":");
        var allEndDate = new Date(arrEndDate[0], arrEndDate[1]-1, arrEndDate[2], arrEndTime[0], arrEndTime[1], arrEndTime[2]);
        
        if (nowTime.getTime() > allEndDate.getTime()) //现在的时间超过截止时间
            $(".main-right-match-finish .main-right-match-content").append("<div class='col-md-12 main-right-match-content-detail' data='" + data[i].teamId + "' id='" + data[i].id + "' onclick='viewTeam(this);'><div class='col-md-12 detail-head'><div class='col-md-8 detail-head-left'>" + data[i].name + "</div></div><div class='col-md-12 detail-body-finish'><div class='col-md-8 detail-body-left-finish'>比赛课题：" + data[i].pro_name + "</div><div class='col-md-3 detail-body-right-finish'>" + state[statusNumber] + "</div></div></div>");
        else
            $(".main-right-match-finish .main-right-match-content").append("<div class='col-md-12 main-right-match-content-detail' data='" + data[i].teamId + "' id='" + data[i].id + "' onclick='viewTeam(this);'><div class='col-md-12 detail-head'><div class='col-md-8 detail-head-left'>" + data[i].name + "</div><div class='col-md-4 detail-head-right-now'>截止日期：" + data[i].deadline + "</div></div><div class='col-md-12 detail-body-finish'><div class='col-md-8 detail-body-left-finish'>比赛课题：" + data[i].pro_name + "</div><div class='col-md-3 detail-body-right-finish'>" + state[statusNumber] + "</div></div></div>");
    }
});

function ShowElement(element) {　
    if ($(element).find("input").length == 0) {
        var oldhtml = element.innerHTML; //获得元素之前的内容
        var newobj;
        newobj = document.createElement('input');
        newobj.type = "text";　 //为newobj元素添加类型
        newobj.value = oldhtml;　　　 //设置newobj失去焦点的事件
        newobj.onblur = function() {　　
            element.innerHTML = this.value ? this.value : oldhtml;　 //当触发时判断newobj的值是否为空，为空则不修改，并返回oldhtml。
            alert("修改成功！");　　
        }　
        element.innerHTML = '';　 //设置元素内容为空       　
        element.appendChild(newobj);　 //添加子元素      　　　　
        newobj.focus();　 //获得焦点
    }

    $.post(); //ajax空缺
}
/*点击查看队伍*/
function viewTeam(element) {
    window.location.href = "user-output.html";
    sendGameId(element);
    sendTeamId(element);

}

function sendTeamId(element) {
    var json = {
        "key": "sendTeamId",
        "teamId": $(element).attr("data")
    }
    $.post("php/teamController.php", json, function(data) {
        data = eval("(" + data + ")");
    });
}
