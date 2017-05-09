<?php
/*管理员控制器，实现管理动作
*通过用户注册，修改管理员信息，
*管理员登录后存储SESSION['adminId']SESSION['adminName']SESSION['degree']
*/
require_once('config/init.php');
if(!isset($_POST['key'])){
	$return=array(
		"success"=>false,
		"notice"=>"非法请求！"
		);
	echo json_encode($return);
	exit();
}
switch($_POST['key']){
	case 'login'://admin/password
		$admin=new Admin($_POST['admin'],$_POST['password']);
		$result=$admin->login();
		if($result==1){
			$return=array(
				"success"=>true,
				"notice"=>""
				);
		}else {
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
		}
		break;
	case 'isLogin':
		if(isset($_SESSION['adminId']))
			$return=array(
				"success"=>true,
				"adminId"=>$_SESSION['adminId'],
				"adminName"=>$_SESSION['adminName'],
				"adminDegree"=>$_SESSION['degree']
				);
		else $return=array(
				"success"=>false
				);
		break;
	case 'logout':
		$_SESSION=array();
		if (isset($_COOKIE[session_name()])) {
       		setcookie(session_name(), '', time()-42000, '/');
     	}
     	$return=array("success"=>true);
		break;
	case 'add':
		$admin=new admin($_POST['name'],$_POST['password'],$_POST['insititute'],$_POST['contact']);
		$result=$admin->checkReg();
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
		}else {
			$admin->add();
			$return=array(
				"success"=>true,
				"notice"=>"添加成功！"
				);
		}
		break;
	case 'edit':
		$admin=new Admin($_POST['name'],$_POST['password'],$_POST['insititute'],$_POST['contact'],'2',$_POST['id']);
		$result=$admin->checkEdit($_POST['name'],$_POST['password'],$_POST['insititute'],$_POST['contact']);
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
		}else {
			$admin->edit();
			$return=array(
				"success"=>true,
				"notice"=>"修改成功！"
				);
		}
		break;
	case 'del':
		$admin=new Admin();
		if($admin->del($_POST['id'])){
			$return=array(
				"success"=>true,
				"notice"=>"删除成功！"
				);
		}else {
			$return=array(
				"success"=>false,
				"notice"=>"删除失败！"
				);
		}
		break;
	case 'getRegUsers':
		$admin=new Admin();
		$return=$admin->getRegUsers();
		break;
	case 'getAllUsers':
		$admin=new Admin();
		$return=$admin->getAllUsers(isset($_POST['num'])?$_POST['num']:0);
		break;
	case 'judge':
		$admin=new Admin();
		if($_POST['agree']){
			if($admin->agree($_POST['id'])){
				$return=array(
					"success"=>true,
					"notice"=>"操作成功！"
					);
			}
			else {
				$return=array(
					"success"=>false,
					"notice"=>"服务器错误！"
					);
			}
		}else{
			if($admin->reject($_POST['id'])){
				$return=array(
					"success"=>true,
					"notice"=>"操作成功！"
					);
			}
			else {
				$return=array(
					"success"=>false,
					"notice"=>"服务器错误！"
					);
			}
		}
		break;
	case 'getAdmins':
		$admin=new Admin();
		$return=$admin->getAdmins();
		break;
	case 'pass'://通过比赛
		//传入数据为teamId,pass.未处理为0，若通过则pass为1，撤回为-1,通过校内初赛为2，通过校内决赛为3;
		$admin=new Admin();
		if($admin->pass($_POST['teamId'],$_POST['pass'])){
			$return=array(
				"success"=>true,
				"notice"=>"操作成功！"
				);
		}else {
			$return=array(
				"success"=>false,
				"notice"=>"服务器错误！"
				);
		}
		break;
	default:
		$return=array(
			"success"=>false,
			"notice"=>"非法请求！"
			);
		break;
}
echo json_encode($return);