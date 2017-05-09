<?php
/**
* 
*/
class Team
{
	private $id;
	private $gameId;
	private $teamName;
	private $proName;
	private $proIntro;
	private $priName;
	private $priContact;
	private $teachName;
	private $teachContact;
	private $award1;
	private $award2;
	private $status;
	function __construct($gameId='',$teamName='',$proName='',$proIntro='',$priName='',$priContact='',$teachName='',$teachContact='',$id='')
	{
		$this->id=$id;
		$this->gameId=$gameId;
		$this->teamName=$teamName;
		$this->proName=$proName;
		$this->proIntro=$proIntro;
		$this->priName=$priName;
		$this->priContact=$priContact;
		$this->teachName=$teachName;
		$this->teachContact=$teachContact;
		$this->status=0;
	}
	function setById($id){
		$this->id=$id;
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from teams where id = $id");
		if(!$result){
			$mysqli->close();
			return false;
		}
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$this->gameId=$row['gameId'];
		$this->teamName=$row['teamName'];
		$this->proName=$row['proName'];
		$this->proIntro=$row['proIntro'];
		$this->priName=$row['priName'];
		$this->priContact=$row['priContact'];
		$this->teachName=$row['teachName'];
		$this->teachContact=$row['teachContact'];
		$this->award1=$row['award1'];
		$this->award2=$row['award2'];
		$mysqli->close();
		return true;
	}
	function getId(){
		return $this->id;
	}
	function addTeam(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$stmt=$mysqli->prepare("insert into teams(user_id,game_id,team_name,pro_name,pro_intro,pri_name,pri_contact,teach_name,teach_contact) values(?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("iisssssss",$_SESSION['userId'],$this->gameId,$this->teamName,$this->proName,$this->proIntro,$this->priName,$this->priContact,$this->teachName,$this->teachContact);
		$stmt->execute();
		$result=$mysqli->query("select id from teams order by id desc limit 1");
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$this->id=$row['id'];
		if(!$result){
			$mysqli->close();
			return false;
		}
		$mysqli->close();
		return true;
	}
	function check(){
		if(empty($this->gameId)||empty($this->teamName)||empty($this->proName)||empty($this->proIntro)||empty($this->priName)||empty($this->priContact)||empty($this->teachName)||empty($this->teachContact))
		{
			return "团队信息不能为空！";
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from teams where game_id = '{$this->gameId}' and user_id = '{$_SESSION['userId']}'");
		if($result->num_rows){
			$mysqli->close();
			return "请勿重复报名！";
		}
		$mysqli->close();
		return 1;
	}
	function checkEdit(){
		if(empty($this->gameId)||empty($this->teamName)||empty($this->proName)||empty($this->proIntro)||empty($this->priName)||empty($this->priContact)||empty($this->teachName)||empty($this->teachContact))
		{
			return "团队信息不能为空！";
		}
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from teams where game_id = '{$this->gameId}' and user_id = '{$_SESSION['userId']}'");
		if(!$result->num_rows){
			$mysqli->close();
			return "没有此队伍！";
		}
		$mysqli->close();
		return 1;
	}
	function checkMembers($members){
		if(empty($members))
			return "成员信息不能为空！";
		foreach ($members as $key => $value) {
			if(empty($value['name'])||empty($value['gender'])||empty($value['contact'])||empty($value['insititute'])||empty($value['class'])||empty($value['stunum'])||empty($value['idcard'])||empty($value['email'])){
				return "成员信息不能为空！";
			}
			foreach ($value as $key => $value1) {
				if(preg_match("/^\*$/",$value1)){
					return "请正确填写所有成员信息！";
				};
			}
		}
		return 1;
	}
	function editTeam(){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$stmt=$mysqli->prepare("update teams set team_name=?,pro_name=?,pro_intro=?,pri_name=?,pri_contact=?,teach_name=?,teach_contact=? where id = ?");
		$stmt->bind_param("sssssssi",$this->teamName,$this->proName,$this->proIntro,$this->priName,$this->priContact,$this->teachName,$this->teachContact,$this->id);
		$stmt->execute();
		$mysqli->close();
		return true;
	}
	function getTeams($gameId){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from teams where game_id='$gameId'");
		$rows=$result->fetch_all(MYSQLI_ASSOC);
		$result=$mysqli->query("select name,intro,deadline from games where id = '$gameId'");
		$game=$result->fetch_array(MYSQLI_ASSOC);
		$teams=array(
			"teams"=>$rows,
			"game"=>$game
			);
		$mysqli->close();
		return $teams;
	}
	function getTeam($teamId){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		$mysqli->query("set names utf8");
		$result=$mysqli->query("select * from teams where id='$teamId'");
		$row=$result->fetch_array(MYSQLI_ASSOC);
		$result=$mysqli->query("select * from members where team_id = '$teamId'");
		$row['members']=$result->fetch_all(MYSQLI_ASSOC);
		$result=$mysqli->query("select name,deadline from games where id = '{$_SESSION['gameId']}'");
		$temp=$result->fetch_array(MYSQLI_ASSOC);
		$row['gameName']=$temp['name'];
		$row['deadline']=$temp['deadline'];
		$mysqli->close();
		return $row;
	}
	function setAward($award1,$award2){
		$mysqli=new mysqli(DB_HOST,DB_USER,DB_PW,DB_NAME);
		if(!$mysqli){
			return false;
		}
		$mysqli->query("set names utf8");
		$stmt=$mysqli->prepare("update teams set award1 = ?,award2 = ? where id = ?");
		$stmt->bind_param("ssi",$award1,$award2,$_SESSION['teamId']);
		$stmt->execute();
		$mysqli->close();
		$this->award1=$award1;
		$this->award2=$award2;
		return true;
	}
	function deleteFile($teamId,$fileName){
		$result=unlink(FILEPATH.$teamId."/".$fileName);
		if($result){
			return "删除成功！";			
		}
		return "删除失败！";
	}
}