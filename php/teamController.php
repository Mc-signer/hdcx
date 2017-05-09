<?php
require_once('config/init.php');
if(!isset($_POST['key'])){
	$return=array(
		"success"=>false,
		"notice"=>"非法请求！"
		);
	echo json_encode($return);
	exit();
}
switch ($_POST['key']) {
	case 'addTeam':
		$team=new Team($_SESSION['gameId'],$_POST['teamName'],$_POST['proName'],$_POST['proIntro'],$_POST['priName'],$_POST['priContact'],$_POST['teachName'],$_POST['teachContact']);
		$result=$team->check();
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
			break;
		}
		$result=$team->checkMembers($_POST['members']);
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
			break;
		}
		$team->addTeam();
		$teamId=$team->getId();
		foreach ($_POST['members'] as $key => $value) {
			$member=new Member($value['name'],$value['gender'],$value['contact'],$value['insititute'],$value['class'],$value['stunum'],$value['idcard'],$value['email'],$teamId);
			$member->addMember();
		}
		$_SESSION['teamId']=$teamId;
		if(!is_dir(FILEPATH.$_SESSION['teamId'])){
			mkdir(FILEPATH.$_SESSION['teamId'],0777,true);
		}
		$return=array(
			"success"=>true,
			"notice"=>"报名成功！"
			);
		break;
	case 'editTeam':
		$team=new Team($_POST['gameId'],$_POST['teamName'],$_POST['proName'],$_POST['proIntro'],$_POST['priName'],$_POST['priContact'],$_POST['teachName'],$_POST['teachContact'],$_POST['teamId']);
		$result=$team->checkEdit();
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
			break;
		}
		$result=$team->checkMembers($_POST['members']);
		if($result!=1){
			$return=array(
				"success"=>false,
				"notice"=>$result
				);
			break;
		}
		$team->editTeam();
		$teamId=$team->getId();
		$member=new Member();
		$member->delByTeamId($teamId);
		foreach ($_POST['members'] as $key => $value) {
			$member=new Member($value['name'],$value['gender'],$value['contact'],$value['insititute'],$value['class'],$value['stunum'],$value['idcard'],$value['email'],$teamId);
			$member->addMember();
		}
		$result=$team->setAward($_POST['award1'],$_POST['award2']);
		$return=array(
			"success"=>true,
			"notice"=>"修改成功！"
			);
		break;
	case 'sendGameId':
		$_SESSION['gameId']=$_POST['gameId'];
		$return=array(
			"gameId"=>$_SESSION['gameId']
			);
		break;
	case 'sendTeamId':
		$_SESSION['teamId']=$_POST['teamId'];
		$return=array(
			"teamId"=>$_SESSION['teamId']
			);
		break;
	case 'getTeams':
		$team=new Team();
		$return=$team->getTeams($_SESSION['gameId']);
		break;
	case 'getTeam':
		$team=new Team();
		$return=$team->getTeam($_SESSION['teamId']);
		break;
	case 'deleteFile':
		$team=new Team();
		$result=$team->deleteFile($_POST['teamId'],$_POST['fileName']);
		$return=array(
			"notice"=>$result
			);
		break;
	default:
		$return=array(
			"success"=>false,
			"notice"=>"非法请求！"
			);
		break;
}
echo json_encode($return);