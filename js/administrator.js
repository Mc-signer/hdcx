/*添加管理员展示*/
$.post("php/adminController.php", { 'key': 'getAdmins' }, function(data) {
    data = eval("(" + data + ")");
    if (data.length == 0) $(".main-right-match-now .jiange .none").css("display", "block");
    for (var i = 0; i <= data.length-1; i++) 
    $(".jiange").append(" <div class='col-md-11 main-right-match-content-detail' id='" + data[i].id + "' style='display:block;'><div class='col-md-12 admin-title' data='a'>" + data[i].insititute + "</div>"+
        "<div class='col-md-5'><div class='col-md-5 admin-label'><div class='col-md-12'>用户名：</div><div class='col-md-12'>密码：</div></div><div class='col-md-7 admin-content'><div class='col-md-12' data='a'>" + data[i].name + "</div><div class='col-md-12' data='a'>" + data[i].password + "</div></div></div><div class='col-md-5'><div class='col-md-5 admin-label'><div class='col-md-12'>所属：</div><div class='col-md-12'>联系方式：</div></div><div class='col-md-7 admin-content'><div class='col-md-12' data='a'>" + data[i].insititute + "</div><div class='col-md-12' data='a'>" + data[i].contact + "</div></div></div><div class='col-md-2 admin-button'><div class='first-button'><a href='javascript:return false' onclick='edit(this);'><img src='img/edit.png' alt=''></a><a href='javascript:return false' onclick='deleteItem(this);'><img src='img/delete.png' alt=''></a></div><div class='second-button'><a href='javascript:return false' onclick='recover(this);'><img src='img/cuo.png' alt=''></a><a href='javascript:return false' onclick='recover(this);editEnsure(this);'><img src='img/dui.png' alt=''></a></div></div></div><!-- detail-end -->");
$(".main-right-match-content").append("<div class='col-md-12  admin-add'><button type='button' class='admin-add-button' onclick='add();'><img src='img/加号.png'></button></div>");
});





function deleteItem(element) {
        var father=$(element).parent().parent().parent();//删除选项的ID
        var json={'key':'del',"id":$(father).attr("id") };
        $.post("php/adminController.php",json,function(data){
            data=eval("(" + data + ")");
            alert(data.notice);
        });
        $(father).remove();
        if ($(".main-right-match-now .jiange .main-right-match-content-detail").length == 0) $(".main-right-match-now .jiange .none").css("display", "block");
    
    }


function edit(element) {
    /*启动编辑状态*/
    var father = $(element).parent();
    var grandpa = $(father).parent().parent();
    $(father).css("display", "none");
    $(father).next().css("display", "block");
    $(grandpa).find("div[data='a']").attr("onclick", "ShowElement(this);");

}

function ShowElement(element) {　
    if ($(element).find("input").length == 0) {
        var oldhtml = element.innerHTML; //获得元素之前的内容
        var newdata = $(element).attr("data");
        var newobj;
        newobj = document.createElement("input");
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

/*添加新管理员*/
function add() {
      $(".main-right-match-now .main-right-match-content .none").css("display", "none");
    $("#messageBox").fadeIn('fast');
}

function ensure() {
    var json = {
        "key":"add",
        "insititute": $("#department").val(),
        "name": $("#name").val(),
        "password": $("#password").val(),
        "contact": $("#contact").val(),
    }; 
    $.post("php/adminController.php",json,function(data){
        data=eval("("+data+")");
        alert(data.notice);
        if(data.success){
        $(".jiange").append("<!-- detail-begin --><div class='col-md-11 main-right-match-content-detail' style='display: block;'><div class='col-md-12 admin-title' data='a'>" + json.insititute + "</div><div class='col-md-5'><div class='col-md-5 admin-label'><div class='col-md-12'>用户名：</div><div class='col-md-12'>密码：</div></div><div class='col-md-7 admin-content'><div class='col-md-12' data='a'>" + json.name + "</div><div class='col-md-12' data='a'>" + json.password + "</div></div></div><div class='col-md-5'><div class='col-md-5 admin-label'><div class='col-md-12'>所属：</div><div class='col-md-12'>联系方式：</div></div><div class='col-md-7 admin-content'><div class='col-md-12' data='a'>" + json.insititute + "</div><div class='col-md-12' data='a'>" + json.contact + "</div></div></div><div class='col-md-2 admin-button'><div class='first-button'><a href='javascript:return false' onclick='edit(this);'><img src='img/edit.png'></a><a href='javascript:return false' onclick='deleteItem(this);'><img src='img/delete.png'></a></div><div class='second-button'><a href='javascript:return false' onclick='recover(this);'><img src='img/cuo.png'></a><a href='javascript:return false' onclick='recover(this);editEnsure(this);'><img src='img/dui.png'></a></div></div></div><!-- detail-end -->");
        }
    })
        $("#messageBox").fadeOut('fast');
}

function cancleAdd() {
    $("#messageBox").fadeOut('fast');
}


/*对勾按钮绑定事件*/
function editEnsure(element){
    var father=$(element).parent().parent().parent();
    var data=$(father).find("div[data='a']");
    var json={
        "key":"edit",
        "id":$(father).attr("id"),
        "name":$(data[1]).html(),
        "password":$(data[2]).html(),
        "insititute":$(data[0]).html(),
        "contact":$(data[4]).html(),
    };
    console.log(json);
    $.post("php/adminController.php",json,function(data){
        data=eval("("+data+")");
        console.log(data);
        alert(data.notice);
    });
}
/*退出编辑状态*/
 function recover(element) {
        var father = $(element).parent();
        var grandpa = $(father).parent().parent();
        var newdata = $(element).attr("data");
        $(father).css("display", "none");
        $(father).siblings().css("display", "block");
        $(grandpa).find("div[data='a']").removeAttr("onclick");
        var content = $(grandpa).find("div[data='b'] span");
        for (var i = 0; i <= 1; i++) {
            var object = $(content[i]).find("input").val();
            $(content[i]).html(object);
        }
    }

/*居中*/
var actualWidth = ($(window).width() - $(".main-right-match-content").width()) / 2 - 200;
$(".main-right-match-content").css("margin-left", actualWidth);
$(".main-right-match-content-detail").css("display", "block");


//撑开
var mainHeight=$(window).height()-$(".index-top").height()-$(".index-user").height()-$(".main").height()-360;
$("#chenggan").css("height",mainHeight);
