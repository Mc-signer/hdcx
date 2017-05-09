/*$.post("php/gameController.php",{'key':'getGames'},function(data){
    data=eval("(" + data + ")");
    console.log(data);
    //展示比赛

});
$.post("php/gameController.php",{'key':'getOverGames','num':'0'},function(data){
    data=eval("(" + data + ")");
    console.log(data);
    //展示比赛

});
$.post("php/gameController.php",{'key':'addGame'},function(data){
    data=eval("(" + data + ")");
    //绑定id
})
*/
var admin_id;



$.post("php/adminController.php", { 'key': 'isLogin' }, function(data) {
    data = eval("(" + data + ")");
    admin_id = data.adminId;
    if (data.adminDegree != "1") $("#guanliyuan").css("display", "none");
    if (!data.success) {
        window.location.href = "admin.html";
    } else { //登陆成功
        //修改登陆名
        $(".index-user-name a:last").html(data.adminName);
    }
});

function logout() {
    $.post("php/adminController.php", { "key": "logout" }, function(data) {

    });
}



/*注册用户数*/
$.post("php/userController.php", { 'key': 'newUserNum' }, function(data) {
    data = eval("(" + data + ")");

    if (data.num != 0) {
        $("em").css("display", "block");
        $("em").html(data.num);
    }
});



function dotsOption(element) {
    var r = confirm("确定要删除该项比赛么？");
    if (r == true) {
    $(element).toggle();
    $(element).next().toggle();
        var father = $(element).parent().parent(); 
        var json = { 'key': 'del', "id": $(father).attr("id") };//删除选项的ID
        $.post("php/gameController.php", json, function(data) {
            data = eval("(" + data + ")");
            console.log(data.success);
            alert(data.notice);
            if (data.success) {
                $(father).remove();
            }
        });
        if ($(".main-right-match-finish .jiange .main-right-match-content-detail").length == 0) {
            $(".main-right-match-finish .jiange .none").css("display", "block");
            $(".detail-more").css("display", "none");
        }

    }
}

function dots(element) {
    $(element).next().toggle();
    $(element).next().next().toggle();
}





var flag = 0; //判断是否处于编辑状态

/* admin-view.js 正在报名展示*/
$.post("php/gameController.php", { 'key': 'getGames' }, function(data) {
    data = eval("(" + data + ")");
    console.log(data);
    if (data.length == 0) $(".main-right-match-now .jiange .none").css("display", "block");
    for (var i = 0; i <= data.length - 1; i++)
        $(".main-right-match-now .jiange").append("<div class='col-md-12 main-right-match-content-detail' id='" + data[i].id + "' style='display:block;' ><div class='col-md-12' id=" + data[i].admin_id + " onclick='viewNowTeam(this);'><div class='col-md-12 detail-head'><div class='col-md-5 detail-head-left' data='a'>" + data[i].name + "</div><div class='col-md-3 detail-head-middle-now' data='b'><span>" + data[i].date + "</span></div><div class='col-md-4 detail-head-right-now' data='b'>截止日期：<span>" + data[i].deadline + "</span></div></div><div class='col-md-12 detail-body-now' data='c'>" + data[i].intro + "</div></div><div class='col-md-12 detail-foot-button'><div class='sponsor'><div class='sponsor-section'>主办方：</div><div class='sponsor-content' data='a'>" + data[i].sponsor + "</div></div><div class='three-button'><a href='javascript:return false' onclick='edit(this);' data='" + data[i].id + "'><img src='img/edit.png'></a><a href='php/excel.php?gameId=" + data[i].id + "'><img src='img/download.png'></a><a href='javascript:return false' onclick='deleteItem(this);'><img src='img/delete.png'></a></div><div class='two-button'><a href='javascript:return false' onclick='recover(this);'><img src='img/cuo.png'></a><a href='javascript:return false' onclick='recover(this);editEnsure(this);'><img src='img/dui.png'></a></div></div></div>");
});





/* admin-view.js 报名结束展示*/
$.post("php/gameController.php", { 'key': 'getOverGames', 'num': '0' }, function(data) {
    data = eval("(" + data + ")");

    for (var i = 0; i <= data.length - 1; i++)
        $(".main-right-match-finish .jiange").append("<div class='col-md-12 main-right-match-content-detail' id='" + data[i].id + "'><div class='col-md-10' id=" + data[i].admin_id + " onclick='viewMatch(this);'><div class='col-md-12 detail-head'><div class='col-md-5 detail-head-left'>" + data[i].name + "</div><div class='col-md-3 detail-head-middle-now'>" + data[i].date + "</div><div class='col-md-4 detail-head-right-now'>截止日期：" + data[i].deadline + "</div></div><div class='col-md-12 detail-body-now'>" + data[i].intro + "</div></div><div class='col-md-2 three-dots'><button type='button' class=' three-dots-button' onclick='dots(this);'><img src='img/more.png'></button><div class='col-md-9 three-dots-option' onclick='dotsOption(this);'>删除</div><div class='col-md-9 three-dots-option'><a style='text-decoration:none;color:#333;' href='php/excel.php?gameId=" + data[i].id + "'>导出</a></div></div><img src='img/jieshu.png'class='detail-foot-img'></div>");
    $(".main-right-match-finish .jiange").append("  <div class='col-md-12 col-md-offset detail-more'><button type='button' class='detail-more-button' onclick='moreMatch();'>· · ·</button></div>");
    detailCenter();
    if (data.length == 0) {
        $(".detail-more").css("display", "none");
        $(".main-right-match-finish .jiange .none").css("display", "block");
    }
});

/*[邓嘉荣修改]将finish-match.html改为admin-match.html*/
function viewMatch(element) {
    var eleAdmin_id = $(element).attr("id");
    if ((admin_id == eleAdmin_id)||(admin_id == 1)) {
    window.location.href = "admin-match.html";
    sendGameId(element);
}
}

/*更多比赛*/
function moreMatch() {
    var num = $(".main-right-match-finish .main-right-match-content-detail").length;
    $.post("php/gameController.php", { 'key': 'getOverGames', 'num': num }, function(data) {
        data = eval("(" + data + ")");
        $(".detail-more").remove();
        for (var i = 0; i <= data.length - 1; i++)
            $(".main-right-match-finish .jiange").append("<div class='col-md-12 main-right-match-content-detail' id='" + data[i].id + "'><div class='col-md-10' onclick='viewMatch(this);'><div class='col-md-12 detail-head'><div class='col-md-5 detail-head-left'>" + data[i].name + "</div><div class='col-md-3 detail-head-middle-now'>" + data[i].date + "</div><div class='col-md-4 detail-head-right-now'>截止日期：" + data[i].deadline + "</div></div><div class='col-md-12 detail-body-now'>" + data[i].intro + "</div></div><div class='col-md-2 three-dots'><button type='button' class=' three-dots-button' onclick='dots(this);'><img src='img/more.png'></button><div class='col-md-9 three-dots-option' onclick='dotsOption(this);'>删除</div><div class='col-md-9 three-dots-option'><a style='text-decoration:none;color:#333;' href='php/excel.php?gameId=" + data[i].id + "'>导出</a></div></div><img src='img/jieshu.png'class='detail-foot-img'></div>");
        $(".main-right-match-finish .jiange").append("<div class='col-md-12 col-md-offset detail-more'><button type='button' class='detail-more-button' onclick='moreMatch();'>· · ·</button></div>");
    detailCenter();
    });
}

/*对勾按钮绑定事件*/
function editEnsure(element) {
    var father = $(element).parent().parent().parent();
    var inforA = $(father).find("div[data='a']");
    var inforS = $(father).find("span");
    var inforC = $(father).find("div[data='c']");

    var data = $(father).find("div[data='a']");
    var json = {
        "key": "edit",
        "id": $(father).attr("id"),
        "name": $(inforA[0]).html(),
        "sponsor": $(inforA[1]).html(),
        "date": $(inforS[0]).html(),
        "deadline": $(inforS[1]).html(),
        "intro": $(inforC).html(),
    };

    $.post("php/gameController.php", json, function(data) {
        data = eval("(" + data + ")");
        alert(data.notice);
    });
}

function edit(element) {
    /*启动编辑状态*/
    flag = 1;
    var father = $(element).parent();
    var grandpa = $(father).parent().parent();
    var dataa = $(element).attr("data");
    $(father).css("display", "none");
    $(father).next().css("display", "block");
    $(grandpa).find("div[data='a']").attr("onclick", "ShowElement(this);");
    $(grandpa).find("div[data='c']").attr("onclick", "ShowElement(this);");
    var content = $(grandpa).find("div[data='b'] span");
    for (var i = 0; i <= 1; i++) {
        var object = $(content[i]).html();
        $(content[i]).html("<input type='text' class='flatpickr' data-enable-time='true' value='" + object + " ' readonly='readonly'> ");
    }
    /*日历插件*/
    $(".flatpickr").flatpickr({
        deutc: true,
        enableTime: true,
    });

}



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
            newobj.style.width = "90%";
            newobj.style.resize = "none";

            var textCount = document.createElement('p');
            textCount.id = "textCount";
            textCount.className = "textCount";
            textCount.style.width = "90%";
            textCount.style.position = "relative";
            textCount.style.top = "-30px";
            textCount.style.textAlign = "right";
            textCount.style.color = "#999999";
            textCount.innerHTML = "剩余200字";
        }

        newobj.type = "text";　 //为newobj元素添加类型
        newobj.value = oldhtml;　　　 //设置newobj失去焦点的事件
        newobj.onblur = function() {　　
            element.innerHTML = this.value ? this.value : oldhtml;　 //当触发时判断newobj的值是否为空，为空则不修改，并返回oldhtml。            　　   
        }
        element.innerHTML = '';　 //设置元素内容为空       　
        element.appendChild(newobj);　 //添加子元素      　　　　
        newobj.focus();　 //获得焦点
        element.appendChild(textCount);

        //字数限制

        $(newobj).on("input propertychange", function() {
            var $this = $(this),
                _val = $this.val(),
                count = 200 - _val.length;
            if (count < 0) {
                $this.val(_val.substring(0, 200));
            }
            count = 200 - $this.val().length;
            $(textCount).text("剩余" + count + "字");
        });

    }
}




/*退出编辑状态*/
function recover(element) {
    var father = $(element).parent();
    var grandpa = $(father).parent().parent();
    var newdata = $(element).attr("data");
    $(father).css("display", "none");
    $(father).siblings().css("display", "block");
    $(grandpa).find("div[data='a']").removeAttr("onclick");
    $(grandpa).find("div[data='c']").removeAttr("onclick");
    var content = $(grandpa).find("div[data='b'] span");
    for (var i = 0; i <= 1; i++) {
        var object = $(content[i]).find("input").val();
        $(content[i]).html(object);
    }
    flag = 0;
}

/*添加比赛*/
function add() {
    var now = $(".main-right-match-now .main-right-match-content .jiange");
    $(".main-right-match-now .main-right-match-content .none").css("display", "none");
    $(now).append("<div class='col-md-12 main-right-match-content-detail' id='' style='display:block;' ><div class='col-md-12' id=" + admin_id + " onclick='viewNowTeam(this);'><div class='col-md-12 detail-head'><div class='col-md-5 detail-head-left' data='a'>输入比赛名称</div><div class='col-md-3 detail-head-middle-now' data='b'><span>点击编辑发布日期</span></div><div class='col-md-4 detail-head-right-now' data='b'>截止日期：<span>点击编辑</span></div></div><div class='col-md-12 detail-body-now' data='c'>请输入关于比赛的简介</div></div><div class='col-md-12 detail-foot-button'><div class='sponsor'><div class='sponsor-section'>主办方：</div><div class='sponsor-content' data='a'>XXX系</div></div><div class='three-button'><a href='javascript:return false' onclick='edit(this);' data=''><img src='img/edit.png'></a><a href='php/excel.php?gameId='><img src='img/download.png'></a><a href='javascript:return false' onclick='deleteItem(this);'><img src='img/delete.png'></a></div><div class='two-button'><a href='javascript:return false' onclick='editDelete(this);'><img src='img/cuo.png'></a><a href='javascript:return false' onclick='bangding(this);'><img src='img/dui.png'></a></div></div></div>");
    var editButton = $(now).find(".main-right-match-content-detail").last();
    var editButton_a = $(editButton).find("a");
    edit($(editButton_a[0]));
}
/*绑定id*/
function bangding(element) {
    var father = $(element).parent().parent().parent();
    var inforA = $(father).find("div[data='a']");
    var inforS = $(father).find("span");
    var inforC = $(father).find("div[data='c']");
    var data = $(father).find("div[data='a']");
    var json = {
        "key": "add",
        "name": $(inforA[0]).html(),
        "sponsor": $(inforA[1]).html(),
        "date": $(inforS[0]).find("input").val(),
        "deadline": $(inforS[1]).find("input").val(),
        "intro": $(inforC).html(),
    };
    console.log(json);
    $.post("php/gameController.php", json, function(data) {
        data = eval("(" + data + ")");
        /*$(now).append("<div class='col-md-12 main-right-match-content-detail' id='" + data.id + "' style='display:block;' ><div class='col-md-10' onclick='viewNowTeam(this);'><div class='col-md-12 detail-head'><div class='col-md-5 detail-head-left' data='a'>输入比赛名称</div><div class='col-md-3 detail-head-middle-now' data='b'><span>2016-12-31 23:00</span></div><div class='col-md-4 detail-head-right-now' data='b'>截止日期：<span>2017-01-01 00:00</span></div></div><div class='col-md-12 detail-body-now' data='c'>请输入关于比赛的简介</div></div><div class='col-md-12 detail-foot-button'><div class='sponsor'><div class='sponsor-section'>主办方：</div><div class='sponsor-content' data='a'>XXX系</div></div><div class='three-button'><a href='javascript:return false' onclick='edit(this);' data='" + data.id + "'><img src='img/edit.png'></a><a href='php/excel.php?gameId=" + data.id + "'><img src='img/download.png'></a><a href='javascript:return false' onclick='deleteItem(this);'><img src='img/delete.png'></a></div><div class='two-button'><a href='javascript:return false' onclick='recover(this);'><img src='img/cuo.png'></a><a href='javascript:return false' onclick='recover(this);editEnsure(this);'><img src='img/dui.png'></a></div></div></div>");
         */
        if (data.success) {
            var twoButton = $(element).parent();
            var detail = $(twoButton).parent().parent();
            $(detail).attr("id", data.id);
            var detail_a = $(detail).find("a");
            $(detail_a[0]).attr("data", data.id);
            $(detail_a[1]).attr('href', 'php/excel.php?gameId=' + data.id);
            $(element).attr("onclick", "recover(this);editEnsure(this);");
            recover(element);
        } else {
            alert(data.notice);
        }

    });

}

/*删除比赛*/
function deleteItem(element) {
    var r = confirm("确定要删除该选项么？");
    if (r == true) {
        var father = $(element).parent().parent().parent(); //删除选项的ID
        var json = { 'key': 'del', "id": $(father).attr("id") };

        $.post("php/gameController.php", json, function(data) {
            data = eval("(" + data + ")");
            alert(data.notice);
            if (data.success) {
                $(father).remove();
            }
        });
        if ($(".main-right-match-now .jiange .main-right-match-content-detail").length == 0) $(".main-right-match-now .jiange .none").css("display", "block");
    }
}

function editDelete(element) {
    var r = confirm("确定要放弃此次编辑？");
    if (r == true) {
        var father = $(element).parent().parent().parent(); //删除选项的ID
        $(father).remove();
        if ($(".main-right-match-now .jiange .main-right-match-content-detail").length == 0) $(".main-right-match-now .jiange .none").css("display", "block");
    }
    $(element).attr("onclick", "recover(this);");
}


/*点击查看报名队伍*/
function viewNowTeam(element) {
    if (flag == 0) {
        var eleAdmin_id = $(element).attr("id");
        if ((admin_id == eleAdmin_id)||(admin_id == 1)) {
            window.location.href = "admin-match.html";
            sendGameId(element);
        }
    }
}
/*传送比赛ID*/
function sendGameId(element) {
    var json = {
        "key": "sendGameId",
        "gameId": $(element).parent().attr("id")
    }
    $.post("php/teamController.php", json, function(data) {
        data = eval("(" + data + ")");
    });
}
/*更多按钮居中*/
function detailCenter(){
var detailButton=($(".content-view-add").width()-85)/2;

$(".main-right-match-finish .jiange .detail-more").css("margin-left", detailButton);
}
/*居中*/
var actualWidth = ($(window).width() - $(".content-view-add").width()) / 2 - 220;
$(".content-view-add").css("margin-left", actualWidth);
