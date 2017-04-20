    function register() {
        $("#logButton1").removeClass("login-right-head-choose");
        $("#regButton1").addClass("login-right-head-choose");
        $("#logButton2").removeClass("login-right-window-top-choose");
        $("#regButton2").addClass("login-right-window-top-choose");
        $("#login").css("display", "none");
        $("#register").css("display", "block");
    }

    function login() {
        $("#regButton1").removeClass("login-right-head-choose");
        $("#logButton1").addClass("login-right-head-choose");
        $("#regButton2").removeClass("login-right-window-top-choose");
        $("#logButton2").addClass("login-right-window-top-choose");
        $("#register").css("display", "none");
        $("#login").css("display", "block");
    }
    /*ajax 传送数据*/
    /*登录*/
    function loginbutton() {
        $.post("php/userController.php", $("#login").serialize(),
            function(data) {
                data = eval("(" + data + ")");
                if (data.success) {
                    window.location.href = "index.html";
                } else {
                    alert(data.notice);
                }
            });
    }

    $("#loginButton").click(function() {
        loginbutton();
    });
    /*注册*/

    function registerbutton() {
        $("#register-model-content").css("display",'block');
        $("#register-model").css("display",'block');
        $.post("php/registerController.php", $("#register").serialize(),
            function(data) {                
                data = eval("(" + data + ")");                 
                    alert(data.notice);
               if (data.notice=='已发送验证邮件，请查收') window.location.href = "login.html";
               $("#register-model").css("display",'none');
               $("#register-model-content").css("display",'none');
            });
    }
    $("#registerButton").click(function() {
        registerbutton();
    });

/*enter*/
    function keyLogin() {
        if (event.keyCode == 13)
            if ($("#login").css("display") == "block") loginbutton();
            else registerbutton();
    }
