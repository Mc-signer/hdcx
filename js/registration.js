/*下一步上一步按钮*/
function memberShow() {
    $("#member").css("display", "block");
    $("#basic").css("display", "none");
    $("#upload").css("display", "none");
    $("#second .beGrey").removeClass("beGrey");
}

function basicShow() {
    $("#basic").css("display", "block");
    $("#member").css("display", "none");
    $("#upload").css("display", "none");
    $("#second div").addClass("beGrey");
}

function uploadShow() {
    $("#upload").css("display", "block");
    $("#member").css("display", "none");
    $("#basic").css("display", "none");
    $("#canclethis").css("display","block");
    $("#third .beGrey").removeClass("beGrey");
}

function addMember(){
    $("#chengyuan").append("<tr><td><button><img src='img/delete.png'></button></td><td>***</td><td>*</td><td>***********</td><td>***系</td><td>**1501</td><td>*****</td><td>*****</td><td>***@***.com</td></tr>");
    $("#chengyuan tr:last td").attr("ondblclick","ShowElement(this);");
    $("#chengyuan tr:last td:first").attr("onclick","deleteMember(this);");   
}
/*双击编辑资料*/

    function ShowElement(element) {　
        if ($(element).find("input").length == 0)  {
            var oldhtml = element.innerHTML; //获得元素之前的内容
            var newdata = $(element).attr("data");
            var newobj;           
            newobj = document.createElement('input');
            newobj.type = "text";　 //为newobj元素添加类型
            newobj.value = "";
            newobj.style.paddingLeft="2px";
            newobj.style.width="100%";
            newobj.style.height="20px";
            　　//设置newobj失去焦点的事件
            newobj.onblur = function() {　　
                element.innerHTML = this.value ? this.value : oldhtml;　 //当触发时判断newobj的值是否为空，为空则不修改，并返回oldhtml。            　　
            }　
            element.innerHTML = '';　 //设置元素内容为空       　
            element.appendChild(newobj);　 //添加子元素      　　　　
            newobj.focus();　 //获得焦点
        }
    }
/*删除成员*/
 function deleteMember(element){
    $(element).parent().remove();
 }
$.post("php/userController.php",{'key':'isLogin'},function(data){
    data=eval("(" + data + ")");
    if(!data.success){
        window.location.href="login.html";
    }
    else{//登陆成功
        //修改登陆名

        $(".index-user-name").find("a:last").html(data.userName);
    } 
});

function logout() {
    $.post("php/userController.php", { 'key': 'logout' }, function(data) {

    });
}



var json;    //最终要传的数据
var member= new Array();  //成员列表
//var attachment  定义存放附件的变量


/*基本信息抓取数据*/
function firstStep() {
     json = {
        //比赛Id要传
        "key":"addTeam",
        "teamName": $("#teamName").val(),
        "proName": $("#proName").val(),
        "proIntro": $("#proIntro").val(),
        "priName": $("#priName").val(),
        "priContact": $("#priContact").val(),
        "teachName": $("#teachName").val(),
        "teachContact": $("#teachContact").val(),
        "members": member,
        //"attachment":......
    };
}

/*成员列表抓取数据*/
function secondStep(){
      var partner=$("#chengyuan tr");
      for (var i=0; i <= partner.length - 1; i++) {
         var information=$(partner[i]).find("td");
         member[i]={
            "name":$(information[1]).html(),
            "gender":$(information[2]).html(),
            "contact":$(information[3]).html(),
            "insititute":$(information[4]).html(),
            "class":$(information[5]).html(),
            "stunum":$(information[6]).html(),
            "idcard":$(information[7]).html(),
            "email":$(information[8]).html(),
         };
      }
      $.post("php/teamController.php",json,function(data){
            data=eval("("+data+")");
            alert(data.notice);
            if (data.success==true) uploadShow();            
        });
}

/*function canclethis(){
     var r = confirm("确定要取消此次报名？（若确定，则将丢失所有信息）");
    if (r == true) 
        window.location.href("index.html");
}*/
$.post("php/gameController.php",{"key":"getGameName"},function(data){
    data=eval("("+data+")");
    console.log(data);
    $("#matchName").html(data.name);
});

/*撑开页面*/
var mainHeight=$(window).height()-$(".index-top").height()-$(".index-user").height()-170;
$("#chenggan").css("height",mainHeight)