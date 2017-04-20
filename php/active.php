<?php
require_once("config/init.php");
$verify = stripslashes(trim($_GET['verify'])); 
$user=new Register();
$msg=$user->register($verify);
if($msg==1){
	header("refresh:5;url='../login.html'");
	$return=array(
		"msg"=>"激活成功！请等待管理员审核。5s后将跳转至登录页面"
		);
}else{
	$return=array(
		"msg"=>$result
		);
}
?>
<!DOCTYPE html>
<html>

<html>
<head lang="en">
    <title></title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <script src="../js/material.min.js"></script>
    
</head>

<body>
    <img src="../img/indexBackground.png" class="index-background col-md-12">
    <div class="index-top col-md-12">
        <img src="../img/indexTitle.png" class="col-md-5">
    </div>
    <div class="col-md-12 main">
        <div class="col-md-6 forgetPassword">
            <div class="col-md-12 col-md-offset-3 tips-text">
                <p id="countdown">激活成功，本页面将在 5 秒后跳转到登录页面...</p>
            </div>
        </div>
    </div>
    <!-- 版权说明 -->
    <footer class="col-md-12" style="position: absolute;">
        Copyright © 2006-2017 <strong><a href="协会首页\index.html" target="_blank">网络管理协会 NMC</a></strong> All Rights Reserved.<img src="../img/loginBackground.png" class="col-md-2 background-img">
    </footer>
    </div>
    <script type="text/javascript">
     /*倒计时*/
    var time = 5; //设置倒计时时间
    function reduce() {
          time=time-1;
          document.getElementById("countdown").innerHTML="激活成功，本页面将在 " + time + " 秒后跳转到登录页面...";
          if(time==0) window.location.href="../login.html";
    }
    setInterval('reduce()', 1000);
    </script>
</body>

</html>
