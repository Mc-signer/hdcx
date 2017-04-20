<?php
require_once('config/init.php');
if(isset($_POST)){
	$user=new Register($_POST['name'],$_POST['password'],$_POST['contact'],$_POST['email']);
} else {
	exit;
}
$return=$user->checkReg($_POST['password']);
if($return==1){
	$return1=$user->sendEmail();
	if($return1!=1){
		$result=array(
			"success"=>false,
			"notice"=>"服务器错误，请联系管理员"
			);
	}
	$result=array(
		"success"=>true,
		"notice"=>"已发送验证邮件，请查收"
		);
}
else {
	$result=array(
		"success"=>false,
		"notice"=>$return
		);
}
echo json_encode($result);