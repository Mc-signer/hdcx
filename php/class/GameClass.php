<?php
/**
* 
*/
class Game
{
	private $id;
	private $name;
	private $intro;
	private $date;
	private $deadline;
	private $sponsor;
	private $adminId;
	function __construct($name='',$intro='',$date='',$deadline='',$sponsor='',$id='')
	{
		$this->name=$name;
		$this->intro=$intro;
		$this->date=$date;
		$this->deadline=$deadline;
		$this->sponsor=$sponsor;
		$this->id=$id;
	}
	function setId($id){
		$this->id=$id;
	}
	function getId(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select id from games where name = '{$this->name}' and intro = '${$this->intro}' and deadline = '{$this->deadline}' and sponsor = '{$this->sponsor}'");
		if(!$result){
			$mysqli->close();
			return false;
		}
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$this->id=$row['id'];
		$mysqli->close();
		return true;
	}
	function setGame($id){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from games where id = '{$id}'");
		if(!$result){
			$mysqli->close();
			return false;
		}
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$this->id=$row['id'];
		$this->name=$row['name'];
		$this->intro=$row['intro'];
		$this->date=$row['date'];
		$this->deadline=$row['deadline'];
		$this->sponsor=$row['sponsor'];
		$this->adminId=$row['admin_id'];
		$mysqli->close();
		return true;
	}
	function isTheAdmin($adminId){
		return (($adminId==$this->adminId)||($adminId==1))?true:false;
	}
	function getGame(){
		$result=array(
			"id"=>$this->id,
			"name"=>$this->name,
			"intro"=>$this->intro,
			"date"=>$this->date,
			"deadline"=>$this->deadline,
			"sponsor"=>$this->sponsor
			);
		return $result;
	}
	function checkInput($name,$intro,$date,$deadline,$sponsor){
		if($name=="输入比赛名称"||$intro=="请输入关于比赛的简介"||$date=="2016-12-31 23:00:00"||$deadline=="2017-01-01 00:00:00"||$sponsor=="XXX系"){
			return false;
		}else {
			return true;
		}
	}
	function addGame($name,$intro,$date,$deadline,$sponsor){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("insert into games(name,intro,date,deadline,sponsor,admin_id) values('$name','$intro','$date','$deadline','$sponsor','{$_SESSION['adminId']}')");
		$result=$mysqli->query("select id from games order by id desc");
		$row=$result->fetch_array(MYSQLI_ASSOC);
		if(!$result){
			$mysqli->close();
			return false;
		}
		$mysqli->close();
		return $row['id'];
	}
	function delGame(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("delete from games where id = '{$this->id}'");
		if(!$result){
			$mysqli->close();
			return false;
		}
		$result=$mysqli->query("select * from teams where game_id = '{$this->id}'");
		$rows=$result->fetch_all(MYSQLI_ASSOC);
		foreach ($rows as $key => $value) {
			$result1=$mysqli->query("delete from members where team_id = '{$value['id']}'");
			$dir=dirname(dirname(__FILE__)).'/files/'.$value['id'];
		    //先删除目录下的文件：
		    $dh=opendir($dir);
		    while ($file=readdir($dh)) {
		        if($file!="." && $file!="..") {
		            $fullpath=$dir."/".$file;
		            if(!is_dir($fullpath)) {
		                unlink($fullpath);
		            } else {
		                deldir($fullpath);
		            }
		    	}
		  	}
		  
		    closedir($dh);
		    //删除当前文件夹：
		    if(rmdir($dir)) {
		    } else {
		    	return false;
		  	}
		}
		$result=$mysqli->query("delete from teams where game_id = '{$this->id}'");
		if(!$result){
			$mysqli->close();
			return false;
		}
		$mysqli->close();
		return true;
	}
	function editGame($name,$intro,$date,$deadline,$sponsor){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("update games set name='{$name}',intro='{$intro}',date='{$date}',deadline='{$deadline}',sponsor='{$sponsor}' where id = '{$this->id}'");
		if(!$result){
			$mysqli->close();
			return false;
		}
		$mysqli->close();
		return true;
	}
	function getUnRegGames($userId){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from games  where deadline > '".date('Y-m-d H:i:s')."' and not exists(select * from teams where teams.game_id=games.id and teams.user_id = '$userId')");
		$games=$result->fetch_all(MYSQLI_ASSOC);
		$mysqli->close();
		return $games;
	}
	function getRegGames($userId){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select games.id,games.name,games.intro,games.date,games.date,games.deadline,games.sponsor,teams.id as teamId,teams.pro_name,teams.status from games inner join teams where teams.game_id=games.id and teams.user_id = '$userId'");
		$games=$result->fetch_all(MYSQLI_ASSOC);
		$mysqli->close();
		return $games;
	}
	function getGames(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from games where deadline > '".date('Y-m-d H:i:s')."'");
		$games=$result->fetch_all(MYSQLI_ASSOC);
		$mysqli->close();
		return $games;
	}
	function getOverGames($number){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from games where deadline < '".date('Y-m-d H:i:s')."' order by id desc limit $number,2");
		$games=$result->fetch_all(MYSQLI_ASSOC);
		$mysqli->close();
		return $games;
	}
}