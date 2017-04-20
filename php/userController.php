<?php
require_once("config/init.php");
if(!isset($_POST['key'])){
	$return=array(
		"success"=>false,
		"notice"=>"非法请求！"
		);
	echo json_encode($return);
	exit();
}
switch ($_POST['key']) {
	case 'login':
		$user=new User($_POST['name'],$_POST['password']);
		$result=$user->login();
		if($result==1){
			$return=array(
				"success"=>true,
				"notice"=>""
				);
		} else{
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
		}
		break;
	case 'isLogin':
		if(isset($_SESSION['userId'])){
			$return=array(
				"success"=>true,
				"userId"=>$_SESSION['userId'],
				"userName"=>$_SESSION['userName'],
				"userContact"=>$_SESSION['contact'],
				"userEmail"=>$_SESSION['email']
				);
		}else{
			$return=array(
				"success"=>false,
				);
		}
		break;
	case 'logout':
		$_SESSION=array();
		if (isset($_COOKIE[session_name()])) {
       		setcookie(session_name(), '', time()-42000, '/');
     	}
     	$return=array("success"=>true);
		break;
	
	case 'editUser':
		$user=new User();
		$result=$user->checkEdit($_POST['name'],$_POST['contact'],$_POST['email']);
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
		}else {
			$user->editUser($_POST['name'],$_POST['contact'],$_POST['email']);
			$return=array(
				"success"=>true,
				"notice"=>"修改成功！"
				);
		}
		break;
	case 'editPassword':
		$user=new User();
		$result=$user->editPassword($_POST['name'],$_POST['old'],$_POST['new'],$_POST['again']);
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
		}else {
			$return=array(
				"success"=>true,
				"notice"=>"修改成功！"
				);
		}
		break;
		case 'forgetPassword':
		$user=new User();
		$result=$user->checkNameOrEmail($_POST['value']);
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
		}else {
			$user->sendEmail();
			$return=array(
				"success"=>true,
				"notice"=>"邮件发送成功！"
				);
		}
		break;
	case 'isReset':
		$user=new User();
		if(isset($_SESSION['resetId'])&&!empty($_SESSION['resetId'])){
			$return=array(
				"success"=>true
				);
		}else{
			$return=array(
				"success"=>false
				);
		}
		break;
	case 'reset':
		$user=new User();
		$result=$user->reset($_POST['new'],$_POST['again']);
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
		}else{
			$return=array(
				"success"=>true,
				"notice"=>"重置成功！"
				);
		}
		break;
	case 'newUserNum':
		$user=new User();
		$result=$user->getNewUserNum();
		$return=array(
			"num"=>$result
		);
		break;
	default:
		# code...
		break;
}
echo json_encode($return);