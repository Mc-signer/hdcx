<?php
/*传入数据格式
*key:控制器方法get/add/edit
*
*/
require_once('config/init.php');
if(!isset($_POST['key'])){
	$return=array(
		"success"=>"false",
		"notice"=>"非法请求！"
		);
	echo json_encode($return);
	exit();
}
switch($_POST['key']){
	case 'add':
		$game=new Game();
		if($game->checkInput($_POST['name'],$_POST['intro'],$_POST['date'],$_POST['deadline'],$_POST['sponsor'])){
			$id=$game->addGame($_POST['name'],$_POST['intro'],$_POST['date'],$_POST['deadline'],$_POST['sponsor']);
			$return=array(
				"success"=>true,
				"id"=>$id
				);
		}else {
			$return=array(
				"success"=>false,
				"notice"=>"请填写所有比赛信息!"
				);
		}
		break;
	case 'get':
		$game=new Game();
		$game->setGame($_POST['id']);
		$return=$game->getGame();
		break;
	case 'edit':
		$game=new Game();
		$game->setGame($_POST['id']);
		if($game->editGame($_POST['name'],$_POST['intro'],$_POST['date'],$_POST['deadline'],$_POST['sponsor'])){
			$return=array(
				"success"=>"true",
				"notice"=>"修改成功"
				);
		}else {
			$return=array(
				"success"=>"false",
				"notice"=>"修改失败，服务器错误"
				);
		}
		break;
	case 'del':
		if(!isset($_POST['id'])){
			$return=array(
				"success"=>false,
				"notice"=>"非法请求！"
				);
			break;
		}
		$game=new Game();
		$game->setId($_POST['id']);
		if($game->delGame()){
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
	case 'getUnRegGames'://用户界面正在报名比赛
		if(!isset($_SESSION['userId'])){
			$return=array(
				"success"=>false,
				"notice"=>"非法请求！"
				);
		}
		$game=new Game();
		$return=$game->getUnRegGames($_SESSION['userId']);
		break;
	case 'getRegGames'://用户界面已经报名比赛
		if(!isset($_SESSION['userId'])){
			$return=array(
				"success"=>false,
				"notice"=>"非法请求！"
				);
		}
		$game=new Game();
		$return=$game->getRegGames($_SESSION['userId']);
		break;	
	case 'getGameName':
		$game=new Game();
		$game->setGame($_SESSION['gameId']);
		$gameInfo=$game->getGame();
		$return=array(
			"name"=>$gameInfo['name']
			);
		break;
	case 'getOverGames':
		$game=new Game();
		$return=$game->getOverGames($_POST['num']);
		break;
	default:
		$return=array(
			"success"=>"false",
			"notice"=>"非法请求！"
			);
}
echo json_encode($return);